<?php
/**
 * Config file for navbar.
 */

return [
    "config" => [
        "navbar-class" => "navbar",
    ],
    "items" => [
        "home" => [
            "text" => "Front",
            "route" => "",
        ],
        "posts" => [
            "text" => "Posts",
            "route" => "post",
        ],
        "tags" => [
            "text" => "Tags",
            "route" => "tags",
        ],
        "profiles" => [
            "text" => "Users",
            "route" => "profiles",
        ],
        "about" => [
            "text" => "About",
            "route" => "about",
        ],
        "account" => [
            "text" => "Account",
            "route" => "user",
            "visibility" => "login",
        ],
        "register" => [
            "text" => "Register",
            "route" => "register",
            "visibility" => "logout",
        ],
        "login" => [
            "text" => "Log in",
            "route" => "login",
            "visibility" => "logout",
        ],
        "logout" => [
            "text" => "Log out",
            "route" => "logout",
            "visibility" => "login",
        ],
    ]
];
