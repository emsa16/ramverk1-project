<?php

namespace Emsa\Frontpage;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;

/**
 * Controller for viewing user profiles.
 */
class FrontpageController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    public function showPage()
    {
        $title = "Let's talk Seinfeld";

        $loggedInUser = $this->di->userController->getLoggedInUserId();
        $options = [
            'order' => "created DESC",
            'limit' => 5,
        ];
        $posts = $this->di->postController->getAllPosts($loggedInUser, $options);

        $tags = $this->di->tagController->getAll();
        $tags = $this->di->tagController->getTagPopularity($tags);
        usort($tags, function ($tag1, $tag2) {
            if ($tag1->count == $tag2->count) {
                return 0;
            }
            return ($tag1->count < $tag2->count) ? 1 : -1;
        });
        $tags = array_slice($tags, 0, 5);

        $users = $this->di->userController->getAll();
        foreach ($users as $user) {
            $user->rank = $this->di->userController->calculateUserRank($user->id);
        }
        usort($users, function ($user1, $user2) {
            if ($user1->rank == $user2->rank) {
                return 0;
            }
            return ($user1->rank < $user2->rank) ? 1 : -1;
        });
        $users = array_slice($users, 0, 5);

        $data = [
            "posts" => $posts,
            'pop_tags' => $tags,
            'users' => $users,
            "textfilter" => $this->di->textfilter,
            "isLoggedIn" => $loggedInUser
        ];

        $this->di->view->add('base/frontpage', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }
}
