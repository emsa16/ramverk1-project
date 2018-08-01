<?php

namespace Emsa\Comment;

use \LRC\Form\BaseModel;
use \LRC\Repository\SoftManagedModelInterface;
use \LRC\Repository\SoftManagedModelTrait;
use \Emsa\User\User;

/**
 * Comment class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Vote extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

    // public $id;
    public $user_id;
    public $comment_id;
    public $vote_value;

    public function __construct()
    {
        $this->setReferences([
            'userObject' => [
                'attribute' => 'user_id',
                'model' => User::class,
                'magic' => true
            ],
            'commentObject' => [
                'attribute' => 'comment_id',
                'model' => Comment::class,
                'magic' => true
            ]
        ]);
    }
}
