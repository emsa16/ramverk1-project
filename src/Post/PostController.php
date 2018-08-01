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

        $editForm = new ModelForm('edit-post-form', $currentPost);

        if ($this->di->request->getMethod() == 'POST') {
            $post = $editForm->populateModel(null, ['id']);
            // //Prevent edited column from being set to NULL
            // unset($post->edited);
            // unset($post->isUserOwner);
            // unset($post->isUserAdmin);
            $editForm->validate();
            if ($editForm->isValid()) {
                //Prevent edited column from being set to NULL
                unset($post->edited);
                unset($post->isUserOwner);
                unset($post->isUserAdmin);
                $this->posts->save($post);
                $this->di->response->redirect("post/$postid");
            }
        }

        $currentPost = $this->getPost($postid, $loggedInUser);

        $viewData = [
            "post" => $currentPost,
            "textfilter" => $this->di->textfilter,
            "action" => "edit",
            "form" => $editForm,
        ];
        $this->di->view->add("post/post", $viewData, "main", 1);

        $this->di->pageRender->renderPage(["title" => "Edit post: " . $currentPost->title]);
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
                unset($currentPost->isUserOwner);
                unset($currentPost->isUserAdmin);
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
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $currentPost = $this->getPost($postid, $loggedInUser);
        if (!$currentPost || is_null($loggedInUser)) {
            $this->di->response->redirect("post");
        }

        if ($this->di->request->getPost("upvote")) {
            $currentPost->upvote += 1;
        } elseif ($this->di->request->getPost("downvote")) {
            $currentPost->downvote += 1;
        }
        unset($currentPost->isUserOwner);
        unset($currentPost->isUserAdmin);
        $this->posts->save($currentPost);
        $this->di->response->redirect("post");
    }



    public function votePostInPage($postid)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $currentPost = $this->getPost($postid, $loggedInUser);
        if (!$currentPost) {
            $this->di->response->redirect("post");
        }

        if (is_null($loggedInUser)) {
            $this->di->response->redirect("post/$postid");
        }

        if ($this->di->request->getPost("upvote")) {
            $currentPost->upvote += 1;
        } elseif ($this->di->request->getPost("downvote")) {
            $currentPost->downvote += 1;
        }
        unset($currentPost->isUserOwner);
        unset($currentPost->isUserAdmin);
        $this->posts->save($currentPost);
        $this->di->response->redirect("post/$postid");
    }



    public function getPost($postid, $loggedInUser)
    {
        //Changed from findSoft in order for posts to still be visible if admin wants to see the post page.
        $post = $this->posts->find('id', $postid);
        return ($post ? $this->checkPosterPrivileges($post, $loggedInUser) : "");
    }



    public function getAllPosts($loggedInUser)
    {
        $posts = $this->posts->getAll();
        foreach ($posts as $key => $post) {
            $posts[$key] = $this->checkPosterPrivileges($post, $loggedInUser);
        }
        return $posts;
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
