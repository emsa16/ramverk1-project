<?php
/**
 * Routes for pages with comment sections.
 */

return [
    "mount" => "post",
    "sort" => 500,
    "routes" => [
        [
            "info" => "Show the comments for requested post.",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}",
            "callable" => ["commentController", "showComments"]
        ],
        [
            "info" => "Reply to an existing comment",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}/reply",
            "callable" => ["commentController", "replyComment"]
        ],
        [
            "info" => "Edit comment",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}/edit-comment",
            "callable" => ["commentController", "editComment"]
        ],
        [
            "info" => "Delete comment from dataset",
            "requestMethod" => "get|post",
            "path" => "{postid:digit}/delete-comment",
            "callable" => ["commentController", "deleteComment"]
        ],
        [
            "info" => "Up-/downvote content",
            "requestMethod" => "post",
            "path" => "{postid:digit}/vote-comment",
            "callable" => ["commentController", "voteComment"]
        ],
    ],
];
