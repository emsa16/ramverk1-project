<?php foreach ($comments as $comment) : ?>
    <?php
    $email = !$comment->userObject->deleted ? $comment->userObject->email : 'deleted@example.com';
    $gravatarString = md5(strtolower(trim($email)));
    $points = ( (int)$comment->upvote - (int)$comment->downvote );
    $created = $comment->timeElapsedString($comment->created);
    $edited = $comment->edited ? ", edited " . $comment->timeElapsedString($comment->edited) : "";
    $content = $textfilter->parse($comment->content, ["htmlentities", "markdown"])->text;
    $cid = $comment->id;
    ?>

    <div class='entry'>

        <a name='<?= $cid ?>'></a>

        <?= $this->renderView("comment/vote-buttons", ["comment" => $comment]) ?>

        <?php
        $gravatarImg = "<img src='http://www.gravatar.com/avatar/$gravatarString.jpg?d=identicon&s=40'>";

        if (!$comment->userObject->deleted) {
            $userUrl = $this->url("profile") . "/" .  $comment->userObject->username;
            $username = "<a href='$userUrl'>" . $comment->userObject->username . "</a> (" . $comment->user_rank . ")";
            $gravatarImg = "<a href='$userUrl'>$gravatarImg</a>";
        } else {
            $username = "[deleted]";
        }

        echo $gravatarImg;
        ?>

        <div class='stats'>
            <?= $points ?> points | by <?= $username ?> | added <?= $created . $edited ?>
            <?php if ($comment->stars) : ?>
                <span class="rewarded">&#9734;</span>
            <?php endif; ?>
        </div>

        <?php if ($action == "edit" && $actionID == $cid) : ?>
            <?= $this->renderView('comment/form', [
                "method" => "edit-comment?id=$cid",
                "submit" => "Save",
                "postid" => $postid,
                "comment" => $comment,
                "form" => $form
            ]) ?>
        <?php elseif ($comment->deleted) : ?>
            <div class='text'><p><i>deleted</i></p></div>
        <?php else : ?>
            <div class='text'><?= $content ?></div>
        <?php endif; ?>

        <div class='actions'>
            <?php if ($isLoggedIn) : ?>
                <a href='<?= $this->url("post/$postid/reply?id=$cid#$cid") ?>'>reply</a>
                <?php if (!$comment->deleted && ($comment->isUserOwner || $comment->isUserAdmin)) : ?>
                    | <a href='<?= $this->url("post/$postid/edit-comment?id=$cid#$cid") ?>'>edit</a>
                    | <a href='<?= $this->url("post/$postid/delete-comment?id=$cid#$cid") ?>'>delete</a>
                <?php endif; ?>

                <?php if ($comment->isUserPostOwner) : ?>
                    <?php $actionUrl = $this->url("post/{$postid}/reward?id=$cid"); ?>
                    <form class='reward' method="post" action="<?= $actionUrl ?>">
                        <input type="submit" name="reward" value="Reward">
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($action == "reply" && $actionID == $cid) : ?>
            <?= $this->renderView('comment/form', [
                "method" => "reply?id=$cid",
                "submit" => "Send",
                "postid" => $postid,
                "parent_id" => $cid,
                "form" => $form
            ]) ?>
        <?php elseif ($action == "delete" && $actionID == $cid) : ?>
            <?= $this->renderView("comment/delete", [
                "comment" => $comment,
                "method" => "delete-comment?id=$cid"
            ]) ?>
        <?php endif; ?>

        <div class='children'>
            <?php if (!empty($comment->children)) : ?>
                <?= $this->renderView('comment/comment-tree', [
                    "comments" => $comment->children,
                    "textfilter" => $textfilter,
                    "postid" => $postid,
                    "action" => $action,
                    "actionID" => $actionID,
                    "form" => $form,
                    "isLoggedIn" => $isLoggedIn
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

<?php endforeach ?>
