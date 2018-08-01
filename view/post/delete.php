<?php
$actionUrl = $this->url("post/{$post->id}/delete-post");
?>

<form class='delConfirm' method="post" action="<?= $actionUrl ?>">
    <input type='hidden' name='id' value='<?= $post->id ?>'>
    Are you sure you want to delete this post?
    <br>
    <input class='delButton' type="submit" name="delete" value="Yes">
    <input type="submit" name="cancel" value="No">
</form>
