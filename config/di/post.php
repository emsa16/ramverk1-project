<?php
/**
 * Configuration file for DI container.
 */

return [
    // Services to add to the container.
    "services" => [
        "postController" => [
            "shared" => true,
            "callback" => function () {
                $comment = new \Emsa\Post\PostController();
                $comment->setDI($this);
                $comment->init();
                return $comment;
            }
        ],
    ],
];
