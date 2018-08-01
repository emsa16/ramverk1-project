<?php
/**
 * Configuration file for DI container.
 */

return [
    // Services to add to the container.
    "services" => [
        "commentController" => [
            "shared" => true,
            "callback" => function () {
                $comment = new \Emsa\Comment\CommentController();
                $comment->setDI($this);
                $comment->init();
                return $comment;
            }
        ],
    ],
];
