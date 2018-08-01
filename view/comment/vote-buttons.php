<?php
$actionUrl = $this->url("post/{$comment->post_id}/vote-comment?id={$comment->id}");

$upvoteClass = $downvoteClass = "";
if (property_exists($comment, 'userVote')) {
    if ($comment->userVote) {
        $upvoteClass = "voted";
    } else {
        $downvoteClass = "voted";
    }
}
?>

<form class='vote-buttons' method="post" action="<?= $actionUrl ?>">
    <input class="<?= $upvoteClass ?>" type="submit" name="upvote" value="&uarr;"><br>
    <input class="<?= $downvoteClass ?>" type="submit" name="downvote" value="&darr;">
</form>
