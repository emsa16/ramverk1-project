<?php
switch ($view) {
    case 'overview':
        $actionUrl = $this->url("post/{$post->id}/vote-post-o");
        break;
    case 'post-page':
        $actionUrl = $this->url("post/{$post->id}/vote-post-i");
        break;
}
?>

<form class='vote-buttons' method="post" action="<?= $actionUrl ?>">
    <input type="submit" name="upvote" value="&uarr;"><br>
    <input type="submit" name="downvote" value="&darr;">
</form>
