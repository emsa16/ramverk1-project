<?php

namespace Emsa\User;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * Controller for viewing user profiles.
 */
class ProfileController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    public function allUsers()
    {
        $title = "All users";

        $users = $this->di->userController->getAll();

        $sortBy = $this->sortBy();
        $sortArray = array();
        foreach ($users as $key => $user) {
            switch ($sortBy) {
                case 'rank':
                    $sortArray[$key] = "rank"; //TEMP ÄNDRA!!
                    break;
                case 'name':
                    //Intentional fall through
                default:
                    $sortArray[$key] = $user->username;
                    break;
            }
        }
        array_multisort($sortArray, SORT_ASC, $users);

        $data = [
            'users' => $users,
        ];

        $this->di->view->add('user/profiles', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    public function showProfile($username)
    {
        $user = $this->di->userController->findSoft('username', $username);
        if (!$user) {
            $this->di->response->redirect('');
        }

        $title = "Profile: " . $user->username;

        $gravatarString = md5(strtolower(trim($user->email)));

        $userPosts = $this->di->postController->getUserPosts($user->id);
        $userComments = $this->di->commentController->getUserComments($user->id);
        $userPostVotes = $this->di->postController->getUserVotes($user->id);
        $userCommentVotes = $this->di->commentController->getUserVotes($user->id);

        $votes = array_merge($userPostVotes, $userCommentVotes);
        usort($votes, function($a, $b) {
            if ($a->id == $b->id) {
                return 0;
            }
            return ($a->id < $b->id) ? -1 : 1;
        } );

        $data = [
            'username' => $user->username,
            'gravatarString' => $gravatarString,
            'userPosts' => $userPosts,
            'userComments' => $userComments,
            'votes' => $votes,
            'textfilter' => $this->di->textfilter,
        ];

        $this->di->view->add('user/profile', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    public function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["name", "rank"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "name";
    }
}
