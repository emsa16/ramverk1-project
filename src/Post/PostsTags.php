<?php

namespace Emsa\Post;

use \LRC\Form\BaseModel;
use \LRC\Repository\SoftManagedModelInterface;
use \LRC\Repository\SoftManagedModelTrait;

/**
 * PostsTags class.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class PostsTags extends BaseModel implements SoftManagedModelInterface
{
    use SoftManagedModelTrait;

    // public $id;
    public $tag_id;
    public $post_id;



    public function __construct()
    {
        $this->setReferences([
            'tagObject' => [
                'attribute' => 'tag_id',
                'model' => Tag::class,
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
