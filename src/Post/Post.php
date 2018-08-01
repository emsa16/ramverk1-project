<?php

namespace Emsa\Post;

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
class Post extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

    // public $id;
    public $user;
    // public $created;
    // public $edited;
    public $title;
    public $content;
    // public $upvote;
    // public $downvote;
    // public $deleted;



    public function __construct()
    {
        $this->setReferences([
            'userObject' => [
                'attribute' => 'user',
                'model' => User::class,
                'magic' => true
            ]
        ]);

        // $this->setNullables(['edited']);
        $this->setValidation([
            'title' => [
                [
                    'rule' => 'required',
                    'message' => 'You need to provide a title.'
                ],
                [
                    'rule' => 'maxlength',
                    'value' => 120,
                    'message' => 'Title length max 120 characters.'
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
