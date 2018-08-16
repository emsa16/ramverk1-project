<?php
$form = "";
if (isset($editForm)) {
    $form = $editForm;
} elseif (isset($replyForm)) {
    $form = $replyForm;
}
?>

<div class="comments">

<p><b>Write a comment</b></p>
<?php if ($isLoggedIn) {
    echo $this->renderView('comment/form', [
        "method" => "",
        "submit" => "Send",
        "postid" => $postid,
        "form" => $newForm,
        "parent_id" => 0
    ]);
} else { ?>
    <p><a href="<?= $this->url('login') ?>">Login</a> to leave a comment.</p>
<?php } ?>

<h2>Comments:</h2>
<p><?= $commentCount ?> comments</p>
<p>Sort by <a href="<?= $this->url("post/$postid?sort=best") ?>">best</a> |
              <a href="<?= $this->url("post/$postid?sort=old") ?>">oldest</a> |
              <a href="<?= $this->url("post/$postid?sort=new") ?>">newest</a></p>

<?php echo $this->renderView("comment/comment-tree", [
    "comments" => $comments,
    "textfilter" => $textfilter,
    "postid" => $postid,
    "action" => $action,
    "actionID" => $actionID,
    "form" => $form,
    "isLoggedIn" => $isLoggedIn
]); ?>

</div>
