<?php
/**
 * Routes for user pages that are accessible without being logged in.
 */

return [
    "mount" => null,
    "sort" => 100,
    "routes" => [
        [
            "info" => "Show all users.",
            "requestMethod" => "get",
            "path" => "profiles",
            "callable" => ["profileController", "allUsers"]
        ],
        [
            "info" => "Show user profile.",
            "requestMethod" => "get",
            "path" => "profile/{username:alphanum}",
            "callable" => ["profileController", "showProfile"]
        ],
    ],
];
