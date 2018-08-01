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
                $obj = new \Emsa\Comment\CommentController();
                $obj->setDI($this);
                $obj->init();
                return $obj;
            }
        ],
    ],
];
