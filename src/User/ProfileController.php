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
        foreach ($users as $user) {
            $user->rank = $this->di->userController->calculateUserRank($user->id);
        }

        if (count($users) > 0) {
            $sortBy = $this->sortBy();
            $sortArray = array();
            foreach ($users as $key => $user) {
                switch ($sortBy) {
                    case 'rank':
                        $sortArray[$key] = $user->rank;
                        $sortOrder = SORT_DESC;
                        break;
                    case 'name':
                        //Intentional fall through
                    default:
                        $sortArray[$key] = $user->username;
                        $sortOrder = SORT_ASC;
                        break;
                }
            }
            array_multisort($sortArray, $sortOrder, $users);
        }

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

        $posts = $this->di->postController->getUserPosts($user->id);
        $comments = $this->di->commentController->getUserComments($user->id);
        $postVotes = $this->di->postController->getUserVotes($user->id);
        $commentVotes = $this->di->commentController->getUserVotes($user->id);
        $givenBadges = $this->di->commentController->getUserGivenBadges($user->id);
        $receivedBadges = $this->di->commentController->getUserReceivedBadges($user->id);
        $rank = $this->di->userController->calculateUserRank($user->id);

        $votes = array_merge($postVotes, $commentVotes);
        usort($votes, function ($vote1, $vote2) {
            if ($vote1->id == $vote2->id) {
                return 0;
            }
            return ($vote1->id < $vote2->id) ? -1 : 1;
        });

        $data = [
            'username' => $user->username,
            'gravatarString' => $gravatarString,
            'rank' => $rank,
            'userPosts' => $posts,
            'userComments' => $comments,
            'votes' => $votes,
            'given_badges' => $givenBadges,
            'received_badges' => $receivedBadges,
            'textfilter' => $this->di->textfilter,
        ];

        $this->di->view->add('user/profile', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    private function sortBy()
    {
        $sortRequest = $this->di->request->getGet("sort");
        $sortRules = ["name", "rank"];
        return in_array($sortRequest, $sortRules) ? $sortRequest : "name";
    }
}
