<?php
/**
 * Routes for pages with user created posts.
 */

return [
    "mount" => "post",
    "sort" => 250,
    "routes" => [
        [
            "info" => "Show all posts.",
            "requestMethod" => "get",
            "path" => "",
            "callable" => ["postController", "allPosts"]
        ],
        [
            "info" => "Show requested post.",
            "requestMethod" => "get",
            "path" => "{postid:digit}",
            "callable" => ["postController", "showPost"]
        ],
        [
            "info" => "Show requested post when posting a comment.",
            "requestMethod" => "get",
            "path" => "{postid:digit}/reply",
            "callable" => ["postController", "showPost"]
        ],
        [
            "info" => "Show requested post when editing a comment.",
            "requestMethod" => "get",
            "path" => "{postid:digit}/edit-comment",
            "callable" => ["postController", "showPost"]
        ],
        [
            "info" => "Show requested post when deleting a comment.",
            "requestMethod" => "get",
            "path" => "{postid:digit}/delete-comment",
            "callable" => ["postController", "showPost"]
        ],
        [
            "info" => "Create a new post",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["postController", "createPost"]
        ],
        [
            "info" => "Edit post",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}/edit-post",
            "callable" => ["postController", "editPost"]
        ],
        [
            "info" => "Delete post from dataset",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}/delete-post",
            "callable" => ["postController", "deletePost"]
        ],
        [
            "info" => "Up-/downvote post from overview",
            "requestMethod" => "post",
            "path" => "{postid:digit}/vote-post-o",
            "callable" => ["postController", "votePostOverview"]
        ],
        [
            "info" => "Up-/downvote post from post page",
            "requestMethod" => "post",
            "path" => "{postid:digit}/vote-post-i",
            "callable" => ["postController", "votePostInPage"]
        ],
    ],
];
