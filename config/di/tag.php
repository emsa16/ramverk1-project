<?php
/**
 * Configuration file for DI container.
 */

return [
    // Services to add to the container.
    "services" => [
        "tagController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Emsa\Post\TagController();
                $obj->setDI($this);
                $obj->init();
                return $obj;
            }
        ],
    ],
];
