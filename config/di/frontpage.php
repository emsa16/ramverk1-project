<?php
/**
 * Configuration file for DI container.
 */

return [
    // Services to add to the container.
    "services" => [
        "frontpageController" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Emsa\Frontpage\FrontpageController();
                $obj->setDI($this);
                return $obj;
            }
        ],
    ],
];
