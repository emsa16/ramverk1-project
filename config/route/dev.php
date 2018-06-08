<?php
/**
 * Routes to ease development, learning, testing and debugging Anax.
 */
return [
    "routes" => [
        [
            "info" => "Development, learning, testing and debugging Anax.",
            "requestMethod" => null,
            "path" => "",
            "callable" => ["developController", "index"],
        ],
        [
            "info" => "Show loaded services in \$di.",
            "requestMethod" => null,
            "path" => "di",
            "callable" => ["developController", "di"],
        ],
        [
            "info" => "Detals on current request.",
            "requestMethod" => null,
            "path" => "request",
            "callable" => ["developController", "request"],
        ],
        [
            "info" => "Show loaded routes.",
            "requestMethod" => null,
            "path" => "route",
            "callable" => ["developController", "route"],
        ],
        [
            "info" => "Show session content.",
            "requestMethod" => null,
            "path" => "session",
            "callable" => ["developController", "session"],
        ],
        [
            "info" => "Work with views.",
            "requestMethod" => null,
            "path" => "view",
            "callable" => ["developController", "view"],
        ],
    ]
];
