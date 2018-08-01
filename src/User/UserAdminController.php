<?php

namespace Emsa\User;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \LRC\Form\ModelForm as Modelform;

/**
 * Controller for user administration.
 */
class UserAdminController implements InjectionAwareInterface
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
        $this->users = $this->di->manager->createRepository(User::class, [
            'db' => $this->di->db,
            'type' => 'db-soft',
            'table' => 'rv1proj_User'
        ]);
    }




    /**
     * Check for all admin/ routes
     *
     * @return void
     */
    public function isAdmin()
    {
        if (!$this->di->session->has("username")) {
            $this->di->response->redirect('login');
        } else if (!$this->di->session->has("admin")) {
            $this->di->response->redirect("user");
        }
    }



    /**
     * Show all items.
     *
     * @return void
     */
    public function getIndex()
    {
        $title      = "Users";

        $data = [
            'header' => $title,
            'users' => $this->users->getAll()
        ];

        $this->di->view->add('admin/crud/view-all', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return void
     */
    public function getPostCreateItem()
    {
        $title = "Add user";

        $form = new ModelForm('admin-create-user-form', User::class);

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
                $form->addError('email', "This adress already exists.");
            }
            if ($form->isValid()) {
                $user->hashPassword();
                $this->users->save($user);
                $this->di->response->redirect('admin');
            }
        }

        $data = [
            'header' => $title,
            'form' => $form,
            'user' => $form->getModel(),
            'submit' => 'Add'
        ];

        $this->di->view->add('admin/crud/form', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Handler with form to update an item.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getPostUpdateItem($id)
    {
        $title = "Edit user details";

        $oldUser = $this->users->find('id', $id);
        if (!$oldUser) {
            $this->di->response->redirect('admin');
        }

        $form = new ModelForm('admin-edit-user-form', $oldUser);
        if ($this->di->request->getMethod() == 'POST') {
            $user = $form->populateModel(null, ['id', 'password']);
            $form->validate();
            $oldUser = $this->users->find('id', $user->id);
            if ($oldUser->username !== $user->username && $this->users->find('username', $user->username)) {
                $form->addError('username', "This username is taken! Please choose another username.");
            }
            if ($oldUser->email !== $user->email && $this->users->find('email', $user->email)) {
                $form->addError('email', "This adress is already registered with another account.");
            }

            $password = $form->getExtra('password');
            $passwordConfirm = $form->getExtra('password_confirm');
            //Only process password fields if at least one is filled in
            if ($password || $passwordConfirm) {
                if (!$password) {
                    $form->addError('password', "A new password must be given.");
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
                $this->di->response->redirect('admin');
            }
        } else {
            $user = $oldUser;
        }

        $data = [
            'header' => $title,
            'form' => $form,
            'user' => $user,
            'submit' => 'Save'
        ];

        $this->di->view->add('admin/crud/form', $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return void
     */
    public function getPostDeleteItem($id)
    {
        $title = "Remove user";

        $user = $this->users->find('id', $id);
        if (!$user) {
            $this->di->response->redirect('admin');
        }

        if ($this->di->request->getMethod() == 'POST') {
            if ($this->di->request->getPost('action') == 'delete') {
                $this->users->deleteSoft($user);
                $this->di->response->redirect('admin');
            }
        }

        $data = [
            'header' => $title,
            'user' => $user
        ];

        $this->di->view->add("admin/crud/delete", $data);

        $this->di->pageRender->renderPage(["title" => $title]);
    }
}
