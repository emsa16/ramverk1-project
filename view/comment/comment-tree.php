<?php foreach ($comments as $comment) : ?>
    <?php
    $email = !$comment->userObject->deleted ? $comment->userObject->email : 'deleted@example.com';
    $gravatarString = md5(strtolower(trim($email)));
    $points = ( (int)$comment->upvote - (int)$comment->downvote );
    $created = $comment->timeElapsedString($comment->created);
    $edited = $comment->edited ? ", edited " . $comment->timeElapsedString($comment->edited) : "";
    $content = $textfilter->parse($comment->content, ["htmlentities", "markdown"])->text;
    ?>

    <div class='entry'>

        <a name='<?= $comment->id ?>'></a>

        <?= $this->renderView("comment/vote-buttons", ["comment" => $comment]) ?>

        <img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=40'>

        <div class='stats'>
            <?= $points ?> points | by <?= !$comment->userObject->deleted ? $comment->userObject->username : '[deleted]' ?> | added <?= $created . $edited ?>
        </div>

        <?php if ($action == "edit" && $actionID == $comment->id) : ?>
            <?= $this->renderView('comment/form', ["method" => "edit-comment?id={$comment->id}", "submit" => "Save", "postid" => $postid, "comment" => $comment, "form" => $form]) ?>
        <?php elseif ($comment->deleted) : ?>
            <div class='text'><p><i>deleted</i></p></div>
        <?php else : ?>
            <div class='text'><?= $content ?></div>
        <?php endif; ?>

        <div class='actions'>
            <?php if ($isLoggedIn) : ?>
                <a href='<?= $this->url("post/$postid/reply?id={$comment->id}#{$comment->id}") ?>'>reply</a>
                <?php if (!$comment->deleted && ($comment->isUserOwner || $comment->isUserAdmin)) : ?>
                    | <a href='<?= $this->url("post/$postid/edit-comment?id={$comment->id}#{$comment->id}") ?>'>edit</a>
                    | <a href='<?= $this->url("post/$postid/delete-comment?id={$comment->id}#{$comment->id}") ?>'>delete</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($action == "reply" && $actionID == $comment->id) : ?>
            <?= $this->renderView('comment/form', ["method" => "reply?id={$comment->id}", "submit" => "Send", "postid" => $postid, "parent_id" => $comment->id, "form" => $form]) ?>
        <?php elseif ($action == "delete" && $actionID == $comment->id) : ?>
            <?= $this->renderView("comment/delete", ["comment" => $comment, "method" => "delete-comment?id={$comment->id}"]) ?>
        <?php endif; ?>

        <div class='children'>
            <?php if (!empty($comment->children)) : ?>
                <?= $this->renderView('comment/comment-tree', ["comments" => $comment->children, "textfilter" => $textfilter, "postid" => $postid, "action" => $action, "actionID" => $actionID, "form" => $form, "isLoggedIn" => $isLoggedIn]) ?>
            <?php endif; ?>
        </div>
    </div>

<?php endforeach ?>
