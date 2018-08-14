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
        usort($tags, function($a, $b) {
            if ($a->count == $b->count) {
                return 0;
            }
            return ($a->count < $b->count) ? 1 : -1;
        });
        $tags = array_slice($tags, 0, 5);

        $users = $this->di->userController->getAll();
        foreach ($users as $user) {
            $user->rank = $this->di->userController->calculateUserRank($user->id);
        }
        usort($users, function($a, $b) {
            if ($a->rank == $b->rank) {
                return 0;
            }
            return ($a->rank < $b->rank) ? 1 : -1;
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
