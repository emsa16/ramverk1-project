<?php

namespace Emsa\User;

use \LRC\Form\BaseModel;

class User extends BaseModel
{
    public $id;
    public $username;
    public $email;
    public $password;
    // public $deleted;



    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->setValidation([
            'username' => [
                [
                    'rule' => 'required',
                    'message' => 'Username must be entered.'
                ],
                [
                    'rule' => 'alphanumeric',
                    'message' => 'Username can only include alphanumeric characters.'
                ],
                [
                    'rule' => 'forbidden-characters',
                    'value' => '&<>\"\'',
                    'message' => 'Forbidden characters used. The following characters are not allowed: & < > \' "'
                ],
                [
                    'rule' => 'maxlength',
                    'value' => 50,
                    'message' => 'The username cannot be more than 50 characters long.'
                ],
            ],
            'email' => [
                [
                    'rule' => 'required',
                    'message' => 'Email adress must be entered.'
                ],
                [
                    'rule' => 'email',
                    'message' => 'Email adress is in the wrong format.'
                ],
                [
                    'rule' => 'forbidden-characters',
                    'value' => '&<>\"\'',
                    'message' => 'Forbidden characters used. The following characters are not allowed: & < > \' "'
                ],
                [
                    'rule' => 'maxlength',
                    'value' => 50,
                    'message' => 'Email adress cannot be more than 50 characters long.'
                ],
            ],
            'password' => [
                [
                    'rule' => 'required',
                    'message' => 'Password must be entered.'
                ],
                // [
                //     'rule' => 'forbidden-characters',
                //     'value' => '&<>\"\'',
                //     'message' => 'Forbidden characters used. The following characters are not allowed: & < > \' "'
                // ],
            ],
        ]);
    }



    /**
     * Hash password.
     *
     * @return void
     */
    public function hashPassword()
    {
        if ($this->password) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }



    /**
     * Verify the acronym and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $inputPassword the password to use.
     *
     * @return boolean true if passwords match, else false.
     */
    public function verifyPassword($inputPassword)
    {
        return password_verify($inputPassword, $this->password);
    }



    /**
     * Check if user is an admin
     * @return bool true if user exists, otherwise false
     */
    public function isAdmin()
    {
        return ($this->username === "admin");
    }
}
