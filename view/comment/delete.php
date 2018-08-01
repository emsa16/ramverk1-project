<?php
$actionUrl = $this->url("post/{$comment->post_id}/$method");
?>

<form class='delConfirm' method="post" action="<?= $actionUrl ?>">
    <input type='hidden' name='id' value='<?= $comment->id ?>'>
    Are you sure you want to delete this comment?
    <br>
    <input class='delButton' type="submit" name="delete" value="Yes">
    <input type="submit" name="cancel" value="No">
</form>
