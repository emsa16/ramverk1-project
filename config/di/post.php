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
                $obj = new \Emsa\Post\PostController();
                $obj->setDI($this);
                $obj->init();
                return $obj;
            }
        ],
    ],
];
