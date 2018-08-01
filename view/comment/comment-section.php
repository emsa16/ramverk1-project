<?php
$form = "";
if (isset($editForm)) {
    $form = $editForm;
} elseif (isset($replyForm)) {
    $form = $replyForm;
}
?>

<div class="comments">

    <h4>Write a comment</h4>
    <?php if ($isLoggedIn) : ?>
        <?= $this->renderView('comment/form', ["method" => "", "submit" => "Send", "postid" => $postid, "form" => $newForm, "parent_id" => 0]) ?>
    <?php else : ?>
        <p><a href="<?= $this->url('login') ?>">Login</a> to leave a comment.</p>
    <?php endif; ?>

    <h3>Comments:</h3>
    <p>Sort by <a href="<?= $this->url("post/$postid?sort=best") ?>">best</a> |
                  <a href="<?= $this->url("post/$postid?sort=old") ?>">oldest</a> |
                  <a href="<?= $this->url("post/$postid?sort=new") ?>">newest</a></p>

    <?= $this->renderView("comment/comment-tree", ["comments" => $comments, "textfilter" => $textfilter, "postid" => $postid, "action" => $action, "actionID" => $actionID, "form" => $form, "isLoggedIn" => $isLoggedIn]) ?>
</div>
