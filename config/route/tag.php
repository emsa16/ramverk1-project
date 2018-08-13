<?php
/**
 * Routes for content tags.
 */

return [
    "mount" => null,
    "sort" => 200,
    "routes" => [
        [
            "info" => "Show all tags.",
            "requestMethod" => "get",
            "path" => "tags",
            "callable" => ["tagController", "allTags"]
        ],
        [
            "info" => "Show content connected to tag.",
            "requestMethod" => "get",
            "path" => "tags/{tag:alphanum}",
            "callable" => ["tagController", "showTag"]
        ],
    ],
];
