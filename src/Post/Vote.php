<?php

namespace Emsa\Post;

use \LRC\Form\BaseModel;
use \LRC\Repository\SoftManagedModelInterface;
use \LRC\Repository\SoftManagedModelTrait;
use \Emsa\User\User;

/**
 * Vote class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Vote extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

    // public $id;
    public $user_id;
    public $post_id;
    public $vote_value;

    public function __construct()
    {
        $this->setReferences([
            'userObject' => [
                'attribute' => 'user_id',
                'model' => User::class,
                'magic' => true
            ],
            'postObject' => [
                'attribute' => 'post_id',
                'model' => Post::class,
                'magic' => true
            ]
        ]);
    }
}
