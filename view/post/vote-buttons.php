<?php
switch ($view) {
    case 'overview':
        $actionUrl = $this->url("post/{$post->id}/vote-post-o");
        break;
    case 'post-page':
        $actionUrl = $this->url("post/{$post->id}/vote-post-i");
        break;
}

$upvoteClass = $downvoteClass = "";
if (property_exists($post, 'userVote')) {
    if ($post->userVote) {
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
