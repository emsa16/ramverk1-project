<?php

namespace Emsa\Post;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * A controller for the post system.
 */
class PostController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * @var \LRC\Repository\SoftDbRepository  Post repository.
     */
    private $posts;



    /**
     * Configuration.
     */
    public function init()
    {
        $postRepository = $this->di->manager->createRepository(Post::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_Post'
        ]);
        $this->posts = $postRepository;

        $voteRepository = $this->di->manager->createRepository(Vote::class, [
            'db' => $this->di->db,
            'type' => 'db',
            'table' => 'rv1proj_Post_votes'
        ]);
        $this->votes = $voteRepository;

        return $postRepository;
    }



    public function allPosts()
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $posts = $this->getAllPosts($loggedInUser);
        $sortBy = $this->sortBy();
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

        $viewData = [
            "posts" => $posts,
            "textfilter" => $this->di->textfilter,
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add("post/all-posts", $viewData);

        $this->di->pageRender->renderPage(["title" => "All posts"]);
    }



    public function showPost($postid)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $post = $this->getPost($postid, $loggedInUser);
        if (!$post) {
            $this->di->response->redirect("post");
        }

        $viewData = [
            "post" => $post,
            "textfilter" => $this->di->textfilter,
            "action" => "",
        ];
        $this->di->view->add("post/post", $viewData, "main", 1);
    }



    public function createPost()
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if (is_null($loggedInUser)) {
            $this->di->response->redirect("post");
        }

        $createForm = new ModelForm('create-post-form', Post::class);

        if ($this->di->request->getMethod() == 'POST') {
            $post = $createForm->populateModel();
            $post->user = $loggedInUser;
            $createForm->validate();
            if ($createForm->isValid()) {
                $this->posts->save($post);
                $this->di->tagController->saveTags($post, $createForm->getExtra('tag_string'));
                $this->di->response->redirect("post/{$post->id}");
            }
        }

        $viewData = [
            "form" => $createForm,
        ];

        $this->di->view->add("post/create", $viewData);

        $this->di->pageRender->renderPage(["title" => "Create post"]);
    }



    public function editPost($postid)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $currentPost = $this->getPost($postid, $loggedInUser);
        if (!$currentPost) {
            $this->di->response->redirect("post");
        }

        if (!$currentPost->isUserOwner && !$currentPost->isUserAdmin) {
            $this->di->response->redirect("post");
        }

        if (!property_exists($currentPost, 'tag_string')) {
            $currentPost->tag_string = array_reduce($currentPost->tags, function($tag_string, $tag) {
                if (!is_null($tag_string)) {
                    return $tag_string . ", " . $tag->title;
                }
                return $tag->title;
            });
        }

        $editForm = new ModelForm('edit-post-form', $currentPost);

        if ($this->di->request->getMethod() == 'POST') {
            $post = $editForm->populateModel(null, ['id']);
            $editForm->validate();
            if ($editForm->isValid()) {
                $this->di->tagController->saveTags($post);
                //Prevent edited column from being set to NULL TEMP flyttat från innan validate()
                unset($post->edited);
                unset($post->isUserOwner);
                unset($post->isUserAdmin);
                unset($post->upvote);
                unset($post->downvote);
                unset($post->userVote);
                unset($post->tags);
                unset($post->tag_string);
                $this->posts->save($post);
                $this->di->response->redirect("post/$postid");
            }
        }

        $oldPost = $this->getPost($postid, $loggedInUser);

        $viewData = [
            "post" => $oldPost,
            "textfilter" => $this->di->textfilter,
            "action" => "edit",
            "form" => $editForm,
        ];
        $this->di->view->add("post/post", $viewData, "main", 1);

        $this->di->pageRender->renderPage(["title" => "Edit post: " . $oldPost->title]);
    }



    public function deletePost($postid)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $currentPost = $this->getPost($postid, $loggedInUser);
        if (!$currentPost) {
            $this->di->response->redirect("post");
        }

        if (!$currentPost->isUserOwner && !$currentPost->isUserAdmin) {
            $this->di->response->redirect("post");
        }

        $editForm = new ModelForm('edit-post-form', $currentPost);

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('delete') == 'Yes') {
                unset($currentPost->edited);
                unset($currentPost->isUserOwner);
                unset($currentPost->isUserAdmin);
                unset($currentPost->upvote);
                unset($currentPost->downvote);
                unset($currentPost->userVote);
                unset($currentPost->tags);
                $this->posts->deleteSoft($currentPost);
                $this->di->response->redirect("post");
            } else {
                $this->di->response->redirect("post/$postid");
            }
        }

        $viewData = [
            "post" => $currentPost,
            "textfilter" => $this->di->textfilter,
            "action" => "delete",
        ];
        $this->di->view->add("post/post", $viewData, "main", 1);

        $this->di->pageRender->renderPage(["title" => "Edit post: " . $currentPost->title]);
    }



    public function votePostOverview($postid)
    {
        $this->votePost($postid, 'overview');
    }



    public function votePostInPage($postid)
    {
        $this->votePost($postid, 'in-page');
    }



    private function votePost($postid, $location)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $currentPost = $this->getPost($postid, $loggedInUser);
        if (!$currentPost) {
            $this->di->response->redirect("post");
        }

        if (is_null($loggedInUser)) {
            if ('overview' == $location) {
                $this->di->response->redirect("post");
            } else if ('in-page' == $location) {
                $this->di->response->redirect("post/$postid");
            }
        }

        $vote_value = "";
        if ($this->di->request->getPost("upvote")) {
            $vote_value = 1;
        } elseif ($this->di->request->getPost("downvote")) {
            $vote_value = 0;
        }

        $result = $this->votes->getAll('post_id = ? AND user_id = ?', [$postid, $loggedInUser]);
        if (count($result) > 0) {
            if (count($result) > 1) {
                //There SHOULD never be more than one vote per user-post pair, but just in case...
                $temp_result = array(array_pop($result));
                foreach ($result as $vote) {
                    $this->votes->delete($vote);
                }
                $result = $temp_result;
            }
            $vote = $result[0];
            if ($vote->vote_value == $vote_value) {
                $this->votes->delete($vote);
            } else {
                $vote->vote_value = $vote_value;
                $this->votes->save($vote);
            }
        } else {
            $vote = new Vote();
            $vote->vote_value = $vote_value;
            $vote->user_id = $loggedInUser;
            $vote->post_id = $postid;
            $this->votes->save($vote);
        }

        if ('overview' == $location) {
            $this->di->response->redirect("post");
        } else if ('in-page' == $location) {
            $this->di->response->redirect("post/$postid");
        }
    }



    public function getPost($postid, $loggedInUser)
    {
        //Changed from findSoft in order for posts to still be visible if admin wants to see the post page.
        $post = $this->posts->find('id', $postid);
        if ($post) {
            $post = $this->getVoteStats($post, $loggedInUser);
            $post = $this->checkPosterPrivileges($post, $loggedInUser);
            $post->tags = $this->di->tagController->getTags($post);
        }
        return $post;
    }



    public function getAllPosts($loggedInUser)
    {
        $posts = $this->posts->getAll();
        foreach ($posts as $key => $post) {
            $post = $this->getVoteStats($post, $loggedInUser);
            $post = $this->checkPosterPrivileges($post, $loggedInUser);
            $post->tags = $this->di->tagController->getTags($post);
            $posts[$key] = $post;
        }
        return $posts;
    }



    public function getVoteStats($post, $loggedInUser)
    {
        $post->upvote = $this->votes->count('post_id = ? AND vote_value = ?', [$post->id, 1]);
        $post->downvote = $this->votes->count('post_id = ? AND vote_value = ?', [$post->id, 0]);

        $vote = $this->votes->getFirst('post_id = ? AND user_id = ?', [$post->id, $loggedInUser]);
        if ($vote) {
            $post->userVote = $vote->vote_value;
        }

        return $post;
    }



    public function checkPosterPrivileges($post, $loggedInUser)
    {
        $post->isUserOwner = ($loggedInUser == $post->userObject->id);
        $post->isUserAdmin = $this->di->session->has("admin");
        return $post;
    }



    public function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["best", "old", "new", "popular"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "best";
    }
}
