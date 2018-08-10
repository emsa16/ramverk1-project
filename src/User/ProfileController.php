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



    /**
     * Configuration.
     */
    public function init()
    {
        $this->users = $this->di->manager->createRepository(User::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_User'
        ]);

        $this->posts = $this->di->manager->createRepository(\Emsa\Post\Post::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_Post'
        ]);

        $this->postVotes = $this->di->manager->createRepository(\Emsa\Post\Vote::class, [
            'db' => $this->di->db,
            'type' => 'db',
            'table' => 'rv1proj_Post_votes'
        ]);

        $this->comments = $this->di->manager->createRepository(\Emsa\Comment\Comment::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_Comment'
        ]);

        $this->commentVotes = $this->di->manager->createRepository(\Emsa\Comment\Vote::class, [
            'db' => $this->di->db,
            'type' => 'db',
            'table' => 'rv1proj_Comment_votes'
        ]);
    }



    public function allUsers()
    {
        $title = "All users";

        $users = $this->users->getAll();

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
        $user = $this->users->findSoft('username', $username);
        if (!$user) {
            $this->di->response->redirect('');
        }

        $title = "Profile: " . $user->username;

        $gravatarString = md5(strtolower(trim($user->email)));

        $userPosts = $this->posts->getAll('user = ?', [$user->id]);
        $userComments = $this->comments->getAll('user = ?', [$user->id]);
        $userPostVotes = $this->postVotes->getAll('user_id = ?', [$user->id]);
        $userCommentVotes = $this->commentVotes->getAll('user_id = ?', [$user->id]);

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
