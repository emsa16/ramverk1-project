<?php

namespace Emsa\Comment;

use \LRC\Form\BaseModel;
use \LRC\Repository\SoftManagedModelInterface;
use \LRC\Repository\SoftManagedModelTrait;
use \Emsa\User\User;
use \Emsa\Post\Post;

/**
 * Comment class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class Comment extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

    // public $id;
    public $post_id;
    public $parent_id;
    public $user;
    // public $created;
    // public $edited;
    public $content;
    // public $deleted;



    public function __construct()
    {
        $this->setReferences([
            'userObject' => [
                'attribute' => 'user',
                'model' => User::class,
                'magic' => true
            ],
            'postObject' => [
                'attribute' => 'post_id',
                'model' => Post::class,
                'magic' => true
            ]
        ]);

        // $this->setNullables(['edited']);
        $this->setValidation([
            'content' => [
                [
                    'rule' => 'required',
                    'message' => 'Comment cannot be empty.'
                ],
            ]
        ]);
    }



    public function timeElapsedString($datetime)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);

        $timeValues = array(
            'y' => ['year', 'years'],
            'm' => ['month', 'months'],
            'w' => ['week', 'weeks'],
            'd' => ['day', 'days'],
            'h' => ['hour', 'hours'],
            'i' => ['minute', 'minutes'],
            's' => ['second', 'seconds'],
        );

        foreach ($timeValues as $k => &$v) {
            if ($diff->$k != 0) {
                $singPlur = $diff->$k > 1 ? 1 : 0;
                $string = $diff->$k . ' ' . $v[$singPlur] . ' ago';
                break;
            }
        }

        return isset($string) ? $string : 'recently';
    }
}
