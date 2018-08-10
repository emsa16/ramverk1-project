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
            "text" => "Home",
            "route" => "",
        ],
        "about" => [
            "text" => "About",
            "route" => "about",
        ],
        "posts" => [
            "text" => "Posts",
            "route" => "post",
        ],
        "profiles" => [
            "text" => "Users",
            "route" => "profiles",
        ],
        "login" => [
            "text" => "Log in",
            "route" => "login",
            "visibility" => "logout",
        ],
        "register" => [
            "text" => "Register",
            "route" => "register",
            "visibility" => "logout",
        ],
        "logout" => [
            "text" => "Log out",
            "route" => "logout",
            "visibility" => "login",
        ],
        "account" => [
            "text" => "Account",
            "route" => "user",
            "visibility" => "login",
        ],
    ]
];
