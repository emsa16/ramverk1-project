<?php

namespace Emsa\User;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * Controller for user system.
 */
class UserController implements InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * @var \LRC\Repository\DbRepository  User repository.
     */
    private $users;



    /**
     * Configuration.
     */
    public function init()
    {
        if (!$this->users) {
            $this->users = $this->di->manager->createRepository(User::class, [
                'db' => $this->di->db,
                'type' => 'db-soft',
                'table' => 'rv1proj_User'
            ]);
        }
        return $this->users;
    }



    /**
     * Description.
     *
     * @return void
     */
    public function loginUser()
    {
        $title = "Login";

        // Make sure no one is logged in.
        if ($this->di->session->has("username")) {
            $this->di->response->redirect('user');
        }

        $form = new ModelForm('login-form', LoginUser::class);

        if ($this->di->request->getMethod() == 'POST') {
            $loginUser = $form->populateModel();
            $form->validate();
            $user = $this->users->findSoft('username', $loginUser->username);
            if (!$user || !$user->verifyPassword($loginUser->password)) {
                $form->addError('password', "Username or password is incorrect.");
            }
            if ($form->isValid()) {
                $this->di->session->set("username", $user->username);
                if ($user->isAdmin()) {
                    $this->di->session->set("admin", $user->username);
                }
                $this->di->response->redirect('user');
            }
        }

        $data = [
            'header' => $title,
            'form' => $form
        ];

        $this->di->view->add('user/login', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Description.
     *
     * @return void
     */
    public function createUser()
    {
        $title = "Register new user";
        $created = false;

        // Make sure no one is logged in.
        if ($this->di->session->has("username")) {
            $this->di->response->redirect('user');
        }

        $form = new ModelForm('create-user-form', User::class);

        if ($this->di->request->getMethod() == 'POST') {
            $user = $form->populateModel();
            $form->validate();
            if ($form->getExtra('password_confirm') !== $user->password) {
                $form->addError('password_confirm', "The passwords do not match!");
            }
            if ($this->users->find('username', $user->username)) {
                $form->addError('username', "This username is taken! Please choose another username.");
            }
            if ($this->users->find('email', $user->email)) {
                $form->addError('email', "This adress already exists! If you already have an account you can login.");
            }
            if ($form->isValid()) {
                $user->hashPassword();
                $this->users->save($user);
                $created = true;
            }
        }

        $data = [
            'header' => $title,
            'form' => $form,
            'created' => $created
        ];

        $this->di->view->add('user/register', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Description.
     *
     * @return void
     */
    public function logoutUser()
    {
        // Check if someone is logged in
        if ($this->di->session->has("username")) {
            $this->di->session->destroy();
        } else {
            $this->di->response->redirect('login');
        }

        // Check if session is active
        $hasSession = session_status() == PHP_SESSION_ACTIVE;

        if (!$hasSession) {
            $this->di->response->redirect('login');
        } else {
            echo "ERROR: Session still exists"; // Should never be visible
            // die;
        }
    }



    /**
     * Check for all user/ routes
     *
     * @return void
     */
    public function isLoggedIn()
    {
        if (!$this->di->session->has("username")) {
            $this->di->response->redirect('login');
        }
    }



    /**
     * Description.
     *
     * @return void
     */
    public function userIndex()
    {
        $username = $this->di->session->get("username");
        $user = $this->users->findSoft('username', $username);
        if (!$user) {
            $this->di->response->redirect('logout');
        }

        $title = "Account";

        $data = [
            "name" => $user->username
        ];
        $this->di->view->add('user/home', $data);
        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Description.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function editUser()
    {
        $username = $this->di->session->get("username");
        $user = $this->users->findSoft('username', $username);
        if (!$user) {
            $this->di->response->redirect('logout');
        }

        $title = "Account details";
        $updated = false;

        $form = new ModelForm('edit-user-form', $user);

        if ($this->di->request->getMethod() == 'POST') {
            $user = $form->populateModel(null, ['id', 'username', 'password']);
            $form->validate();
            $oldUser = $this->users->find('id', $user->id);
            if ($oldUser->email !== $user->email && $this->users->find('email', $user->email)) {
                $form->addError('email', "This adress is already registered with another account.");
            }

            $oldPassword = $form->getExtra('old_password');
            $password = $form->getExtra('password');
            $passwordConfirm = $form->getExtra('password_confirm');
            //Only process password fields if at least one is filled in
            if ($oldPassword || $password || $passwordConfirm) {
                if (!$oldUser->verifyPassword($oldPassword)) {
                    $form->addError('old_password', "The old password is incorrect.");
                }

                if (!$password) {
                    $form->addError('password', "A new password must be entered.");
                }

                if ($passwordConfirm !== $password) {
                    $form->addError('password_confirm', "The passwords do not match.");
                }
            }

            if ($form->isValid()) {
                if ($password) {
                    $user->password = $password;
                    $user->hashPassword();
                }
                $this->users->save($user);
                $updated = true;
            }
        }

        $data = [
            'form' => $form,
            'updated' => $updated
        ];

        $this->di->view->add('user/edit', $data);
        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Description.
     *
     * @return void
     */
    public function deleteUser()
    {
        $username = $this->di->session->get("username");
        $user = $this->users->findSoft('username', $username);
        if (!$user) {
            $this->di->response->redirect('logout');
        }

        $title = "Remove account";

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('action') == 'delete') {
                $this->users->deleteSoft($user);
                $this->di->response->redirect('logout');
            }
        }

        $data = [
            'user' => $user
        ];

        $this->di->view->add("user/delete", $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Description.
     *
     * @return void
     */
    public function getLoggedInUserId()
    {
        $username = $this->di->session->get("username");
        $loggedInUser = $this->users->findSoft('username', $username);
        return $loggedInUser ? $loggedInUser->id : null;
    }



    public function getAll()
    {
        return $this->users->getAll();
    }



    public function findSoft($key, $value)
    {
        return $this->users->findSoft($key, $value);
    }



    /**
     * calculates the rank of given user.
     *
     * Rank is decided by the following, in order of importance:
     * 1. received badges
     * 2. points from user posts and comments that have received votes
     * 3. number of user posts and comments
     *
     * @return integer $rank
     */
    public function calculateUserRank($user_id)
    {
        $rank = 0;

        $badged_comments = $this->di->commentController->getUserReceivedBadges($user_id);
        foreach ($badged_comments as $comment) {
            $rank += $comment->stars * 25;
        }

        $posts = $this->di->postController->getUserPosts($user_id);
        foreach ($posts as $post) {
            $points = $this->di->postController->getPoints($post->id);
            $rank += $points * 5;
        }

        $comments = $this->di->commentController->getUserComments($user_id);
        foreach ($comments as $comment) {
            $points = $this->di->commentController->getPoints($comment->id);
            $rank += $points * 5;
        }

        $rank += count($posts) + count($comments);

        return $rank;
    }
}
