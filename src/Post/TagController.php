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

        $sortBy = $this->sortBy();
        $sortArray = array();
        foreach ($tags as $key => $tag) {
            switch ($sortBy) {
                case 'popularity':
                    $sortArray[$key] = $tag->count;
                    $sort_order = SORT_DESC;
                    break;
                case 'name':
                    //Intentional fall through
                default:
                    $sortArray[$key] = $tag->title;
                    $sort_order = SORT_ASC;
                    break;
            }
        }
        array_multisort($sortArray, $sort_order, $tags);

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

        $post_ids = $this->postsTags->getAll("tag_id = ?", [$tag->id]);

        $posts = array();
        foreach ($post_ids as $post_id) {
            $posts[] = $this->di->postController->getPost($post_id->post_id, $loggedInUser);
        }

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

        $data = [
            "tag" => $this->di->textfilter->parse($tag->title, ["htmlentities"])->text,
            "tag_posts" => $posts,
            'textfilter' => $this->di->textfilter,
        ];

        $this->di->view->add('post/tag', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    public function sortBy()
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
        $tag_objects = $this->postsTags->getAll("post_id = ?", [$post->id]);
        $tags = array();
        foreach ($tag_objects as $object) {
            $tags[] = $this->tags->find('id', $object->tag_id);
        }
        return $tags;
    }



    public function saveTags($post, $tag_string = "")
    {
        if (!property_exists($post, 'tag_string') && empty($tag_string)) {
            return;
        }

        if (property_exists($post, 'tag_string')) {
            $tag_string = $post->tag_string;
        }

        $new_tags = array_unique(array_filter( explode(",", preg_replace('/\s+/', '', strip_tags($tag_string))), 'strlen' ));

        // Remove old tags if not present among new tags
        if (property_exists($post, 'tags')) {
            foreach ($post->tags as $old_tag) {
                if (!in_array($old_tag->title, $new_tags)) {
                    $old_post_tags = $this->postsTags->getAll("post_id = ? AND tag_id = ?", [$post->id, $old_tag->id]);
                    foreach ($old_post_tags as $old_post_tag) {
                        $this->postsTags->delete($old_post_tag);
                    }
                    if (!$this->postsTags->getAll("tag_id = ?", [$old_tag->id])) {
                        $this->tags->delete($old_tag);
                    }
                }
            }
        }

        //Add new tags
        foreach ($new_tags as $tag) {
            $tag_object = $this->tags->find("title", $tag);
            if (!$tag_object) {
                $tag_object = new Tag();
                $tag_object->title = $tag;
                $this->tags->save($tag_object);
            }
            $postTags = $this->postsTags->getAll("post_id = ? AND tag_id = ?", [$post->id, $tag_object->id]);
            if (!$postTags) {
                $post_tag_object = new PostsTags();
                $post_tag_object->post_id = $post->id;
                $post_tag_object->tag_id = $tag_object->id;
                $this->postsTags->save($post_tag_object);
            } else if (count($postTags) > 1) {
                //There SHOULD never be more than 1 unique post-tag pair, but just in case...
                array_pop($postTags);
                foreach ($postTags as $post_tag) {
                    $this->postsTags->delete($post_tag);
                }
            }
        }
    }



    public function getAll()
    {
        return $this->tags->getAll();
    }
}
