<?php
/**
 * Configuration file for DI container.
 */

return [
    // Services to add to the container.
    "services" => [
        "profileController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Emsa\User\ProfileController();
                $obj->setDI($this);
                return $obj;
            }
        ],
    ],
];
