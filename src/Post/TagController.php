<?php

namespace Emsa\Post;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * Controller for viewing user profiles.
 */
class TagController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * Configuration.
     */
    public function init()
    {
        $tagRepository = $this->di->manager->createRepository(Tag::class, [
            'db' => $this->di->db,
            'type' => 'db',
            'table' => 'rv1proj_Tag'
        ]);
        $this->tags = $tagRepository;

        $postsTagsRepository = $this->di->manager->createRepository(PostsTags::class, [
            'db' => $this->di->db,
            'type' => 'db',
            'table' => 'rv1proj_Posts_tags'
        ]);
        $this->postsTags = $postsTagsRepository;
    }



    public function allTags()
    {
        $title = "Tags";

        $tags = $this->tags->getAll();

        $tags = $this->getTagPopularity($tags);

        if (count($tags) > 0) {
            $sortBy = $this->sortBy();
            $sortArray = array();
            foreach ($tags as $key => $tag) {
                switch ($sortBy) {
                    case 'popularity':
                        $sortArray[$key] = $tag->count;
                        $sortOrder = SORT_DESC;
                        break;
                    case 'name':
                        //Intentional fall through
                    default:
                        $sortArray[$key] = $tag->title;
                        $sortOrder = SORT_ASC;
                        break;
                }
            }
            array_multisort($sortArray, $sortOrder, $tags);
        }

        $data = [
            'tags' => $tags,
            "textfilter" => $this->di->textfilter,
        ];

        $this->di->view->add('post/all-tags', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    public function showTag($tag)
    {
        $tag = $this->tags->find('title', $tag);
        if (!$tag) {
            $this->di->response->redirect('tags');
        }

        $title = "Tag: " . $this->di->textfilter->parse($tag->title, ["htmlentities"])->text;

        $loggedInUser = $this->di->userController->getLoggedInUserId();

        $postIds = $this->postsTags->getAll("tag_id = ?", [$tag->id]);

        $posts = array();
        foreach ($postIds as $postId) {
            $posts[] = $this->di->postController->getPost($postId->post_id, $loggedInUser);
        }

        if (count($posts) > 0) {
            $sortBy = $this->di->postController->sortBy();
            $sortOrder = SORT_DESC;
            $sortArray = array();
            foreach ($posts as $key => $post) {
                $post->commentCount = count($this->di->commentController->getComments($post->id, ""));
                switch ($sortBy) {
                    case 'old':
                        $sortOrder = SORT_ASC;
                        //Intentional fall through
                    case 'new':
                        $sortArray[$key] = $post->created;
                        break;
                    case 'popular':
                        $sortArray[$key] = $post->commentCount;
                        break;
                    case 'best':
                        //Intentional fall through
                    default:
                        $sortArray[$key] = ($post->upvote - $post->downvote);
                        break;
                }
            }
            array_multisort($sortArray, $sortOrder, $posts);
        }

        $data = [
            "tag" => $this->di->textfilter->parse($tag->title, ["htmlentities"])->text,
            "tag_posts" => $posts,
            'textfilter' => $this->di->textfilter,
        ];

        $this->di->view->add('post/tag', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    private function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["name", "popularity"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "name";
    }



    public function getTagPopularity($tags)
    {
        foreach ($tags as $tag) {
            $tag->count = $this->postsTags->count("tag_id = ?", [$tag->id]);
        }
        return $tags;
    }



    public function getTags($post)
    {
        $tagObjects = $this->postsTags->getAll("post_id = ?", [$post->id]);
        $tags = array();
        foreach ($tagObjects as $object) {
            $tags[] = $this->tags->find('id', $object->tag_id);
        }
        return $tags;
    }



    public function saveTags($post, $tagString = "")
    {
        if (!property_exists($post, 'tag_string') && empty($tagString)) {
            return;
        }

        if (property_exists($post, 'tag_string')) {
            $tagString = $post->tag_string;
        }

        $newTags = preg_replace('/\s+/', '', strip_tags($tagString));
        $newTags = array_unique(array_filter(explode(",", $newTags), 'strlen'));

        $this->removeOldTags($post, $newTags);
        $this->addNewTags($post, $newTags);
    }



    public function removeOldTags($post, $newTags)
    {
        // Remove old tags if not present among new tags
        if (property_exists($post, 'tags')) {
            foreach ($post->tags as $oldTag) {
                if (!in_array($oldTag->title, $newTags)) {
                    $oldPostTags = $this->postsTags->getAll("post_id = ? AND tag_id = ?", [$post->id, $oldTag->id]);
                    foreach ($oldPostTags as $oldPostTag) {
                        $this->postsTags->delete($oldPostTag);
                    }
                    if (!$this->postsTags->getAll("tag_id = ?", [$oldTag->id])) {
                        $this->tags->delete($oldTag);
                    }
                }
            }
        }
    }



    public function addNewTags($post, $newTags)
    {
        foreach ($newTags as $tag) {
            $tagObject = $this->tags->find("title", $tag);
            if (!$tagObject) {
                $tagObject = new Tag();
                $tagObject->title = $tag;
                $this->tags->save($tagObject);
            }
            $postTags = $this->postsTags->getAll("post_id = ? AND tag_id = ?", [$post->id, $tagObject->id]);
            if (!$postTags) {
                $postTagObject = new PostsTags();
                $postTagObject->post_id = $post->id;
                $postTagObject->tag_id = $tagObject->id;
                $this->postsTags->save($postTagObject);
            } elseif (count($postTags) > 1) {
                //There SHOULD never be more than 1 unique post-tag pair, but just in case...
                array_pop($postTags);
                foreach ($postTags as $postTag) {
                    $this->postsTags->delete($postTag);
                }
            }
        }
    }



    public function getAll()
    {
        return $this->tags->getAll();
    }
}
