<?php

namespace Emsa\Comment;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * A controller for the comment system.
 */
class CommentController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * @var \LRC\Repository\SoftDbRepository  Comment repository.
     */
    private $comments;



    /**
     * Configuration.
     */
    public function init()
    {
        $commentRepository = $this->di->manager->createRepository(Comment::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_Comment'
        ]);
        $this->comments = $commentRepository;
        return $commentRepository;
    }



    public function showComments($postid)
    {

        $loggedInUser = $this->di->userController->getLoggedInUserId();

        $newForm = new ModelForm('new-comment-form', Comment::class);

        if ($this->di->request->getMethod() == 'POST' && $loggedInUser) {
            $comment = $newForm->populateModel();
            $comment->user = $loggedInUser;
            $newForm->validate();
            if ($newForm->isValid()) {
                $this->comments->save($comment);
                $this->di->response->redirect("post/$postid#{$comment->id}");
            }
        }

        $post = $this->di->postController->getPost($postid, $loggedInUser);
        $post_title = $post->title;

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();

        $viewData = [
            "comments" => $this->buildCommentTree($comments, $sortBy),
            "commentCount" => count($comments),
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "",
            "actionID" => "",
            "newForm" => $newForm,
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
        $this->di->pageRender->renderPage(["title" => $post_title]);
    }



    public function replyComment($postid)
    {
        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if (is_null($loggedInUser)) {
            $this->di->response->redirect("post/$postid");
        }

        $actionID = (int)$this->di->request->getGet("id");
        if (!$this->comments->findSoft('id', $actionID)) {
            $this->di->response->redirect("post/$postid");
        }

        $replyForm = new ModelForm('reply-comment-form', Comment::class);

        if ($this->di->request->getMethod() == 'POST') {
            $comment = $replyForm->populateModel();
            $comment->user = $loggedInUser;
            $replyForm->validate();
            if ($replyForm->isValid()) {
                $this->comments->save($comment);
                $this->di->response->redirect("post/$postid#{$comment->id}");
            }
        }

        $post = $this->di->postController->getPost($postid, $loggedInUser);
        $post_title = $post->title;

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();

        $viewData = [
            "comments" => $this->buildCommentTree($comments, $sortBy),
            "commentCount" => count($comments),
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "reply",
            "actionID" => $actionID,
            "newForm" => new ModelForm('new-comment-form', Comment::class),
            "replyForm" => $replyForm,
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
        $this->di->pageRender->renderPage(["title" => "Reply: " . $post_title]);
    }



    public function editComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");
        $currentComment = $this->comments->findSoft('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("post/$postid");
        }

        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if (!$this->canComment($loggedInUser, $currentComment)) {
            $this->di->response->redirect("post/$postid");
        }

        $editForm = new ModelForm('edit-comment-form', $currentComment);

        if ($this->di->request->getMethod() == 'POST') {
            $comment = $editForm->populateModel(null, ['id', 'post_id', 'parent_id']);
            //Prevent edited column from being set to NULL
            unset($comment->edited);
            $editForm->validate();
            if ($editForm->isValid()) {
                $this->comments->save($comment);
                $this->di->response->redirect("post/$postid#{$comment->id}");
            }
        }

        $post = $this->di->postController->getPost($postid, $loggedInUser);
        $post_title = $post->title;

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();

        $viewData = [
            "comments" => $this->buildCommentTree($comments, $sortBy),
            "commentCount" => count($comments),
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "edit",
            "actionID" => $actionID,
            "newForm" => new ModelForm('new-comment-form', Comment::class),
            "editForm" => $editForm,
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
        $this->di->pageRender->renderPage(["title" => "Edit comment - " . $post_title]);
    }



    public function deleteComment($postid)
    {
        $actionID = (int)$this->di->request->getGet("id");
        $currentComment = $this->comments->findSoft('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("post/$postid");
        }

        $loggedInUser = $this->di->userController->getLoggedInUserId();
        if (!$this->canComment($loggedInUser, $currentComment)) {
            $this->di->response->redirect("post/$postid");
        }

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('delete') == 'Yes') {
                $this->comments->deleteSoft($currentComment);
            }
            $this->di->response->redirect("post/$postid#{$currentComment->id}");
        }

        $post = $this->di->postController->getPost($postid, $loggedInUser);
        $post_title = $post->title;

        $comments = $this->getComments($postid, $loggedInUser);
        $sortBy = $this->sortBy();

        $viewData = [
            "comments" => $this->buildCommentTree($comments, $sortBy),
            "commentCount" => count($comments),
            "textfilter" => $this->di->textfilter,
            "postid" => $postid,
            "action" => "delete",
            "actionID" => $actionID,
            "newForm" => new ModelForm('new-comment-form', Comment::class),
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add("comment/comment-section", $viewData, "main", 2);
        $this->di->pageRender->renderPage(["title" => "Delete comment - " . $post_title]);
    }



    public function voteComment($postid)
    {
        if (is_null($this->di->userController->getLoggedInUserId())) {
            $this->di->response->redirect("post/$postid");
        }

        $actionID = (int)$this->di->request->getGet("id");
        $currentComment = $this->comments->findSoft('id', $actionID);
        if (!$currentComment) {
            $this->di->response->redirect("post/$postid");
        }

        if ($this->di->request->getPost("upvote")) {
            $currentComment->upvote += 1;
        } elseif ($this->di->request->getPost("downvote")) {
            $currentComment->downvote += 1;
        }
        $this->comments->save($currentComment);
        $this->di->response->redirect("post/$postid#{$currentComment->id}");
    }



    public function getComments($postid, $loggedInUser)
    {
        $comments = $this->comments->getAll('post_id = ?', [$postid]);

        foreach ($comments as $comment) {
            $comment->isUserOwner = ($loggedInUser == $comment->userObject->id);
            $comment->isUserAdmin = $this->di->session->has("admin");
        }

        return $comments;
    }



    public function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["best", "old", "new"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "best";
    }



    public function buildCommentTree(array &$elements, $sortBy, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $element->children = $this->buildCommentTree($elements, $sortBy, $element->id);
                $branch[$element->id] = $element;
            }
        }
        return $this->sortBranchComments($branch, $sortBy);
    }



    public function sortBranchComments(array &$branch, $sortBy = "best")
    {
        $sortOrder = SORT_DESC;
        $sortArray = array();
        foreach ($branch as $key => $comment) {
            switch ($sortBy) {
                case 'old':
                    $sortOrder = SORT_ASC;
                    //Intentional fall through
                case 'new':
                    $sortArray[$key] = $comment->created;
                    break;
                case 'best':
                    //Intentional fall through
                default:
                    $sortArray[$key] = ($comment->upvote - $comment->downvote);
                    break;
            }
        }
        array_multisort($sortArray, $sortOrder, $branch);
        return $branch;
    }



    public function canComment($user, $comment)
    {
        return $user == $comment->user || $this->di->session->has("admin");
    }
}
