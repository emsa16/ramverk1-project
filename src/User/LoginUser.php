<?php

namespace Emsa\User;

use \LRC\Form\BaseModel;

class LoginUser extends BaseModel
{
    public $id;
    public $username;
    public $password;



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
            'password' => [
                [
                    'rule' => 'required',
                    'message' => 'Password must be entered.'
                ],
                [
                    'rule' => 'forbidden-characters',
                    'value' => '&<>\"\'',
                    'message' => 'Forbidden characters used. The following characters are not allowed: & < > \' "'
                ],
            ],
        ]);
    }
}
