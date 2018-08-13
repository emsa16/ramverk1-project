<?php
$email = !$post->userObject->deleted ? $post->userObject->email : 'deleted@example.com';
$gravatarString = md5(strtolower(trim($email)));
$points = ( (int)$post->upvote - (int)$post->downvote );
$created = $post->timeElapsedString($post->created);
$edited = $post->edited ? ", edited " . $post->timeElapsedString($post->edited) : "";
$title = $textfilter->parse($post->title, ["htmlentities", "markdown"])->text;
$tags = "";
if (!empty($post->tags)) {
    foreach ($post->tags as $tag) {
        $tag_url = $this->url("tags/" . $textfilter->parse($tag->title, ["htmlentities"])->text);
        $tags .= "<a href='$tag_url'>#" . $textfilter->parse($tag->title, ["htmlentities"])->text . "</a> ";
    }
}
$content = $textfilter->parse($post->content, ["htmlentities", "markdown"])->text;
?>
<div class='entry'>
    <table class='title'>
        <tr>
            <td><h2><?= $title ?></h2></a></td>
            <td><span class='tags'><?= $tags ?></span></td>
        </tr>
    </table>

    <?= $this->renderView("post/vote-buttons", ["post" => $post, "view" => 'post-page']) ?>

    <?php if ($action == "edit") : ?>
        <?= $this->renderView('post/edit', ["post" => $post, "form" => $form]) ?>
    <?php elseif ($post->deleted && strtotime($post->deleted) < time()) : ?>
        <div class='text'><p><i>deleted</i></p></div>
    <?php else : ?>
        <div class='text'><?= $content ?></div>
    <?php endif; ?>

    <?php
    $gravatarImg = "<img src='http://www.gravatar.com/avatar/$gravatarString.jpg?d=identicon&s=40'>";

    if (!$post->userObject->deleted) {
        $userUrl = $this->url("profile") . "/" .  $post->userObject->username;
        $username = "<a href='$userUrl'>" . $post->userObject->username . "</a>";
        $gravatarImg = "<a href='$userUrl'>$gravatarImg</a>";
    } else {
        $username = "[deleted]";
    }

    echo $gravatarImg;
    ?>

    <div class='stats'>
        <?= $points ?> points, created by <?= $username ?>, added <?= $created . $edited ?>
    </div>

    <?php if (!($post->deleted && strtotime($post->deleted) < time()) && ($post->isUserOwner || $post->isUserAdmin)) : ?>
        <div class='actions'>
            <a href='<?= $this->url("post/{$post->id}/edit-post") ?>'>edit</a>
            | <a href='<?= $this->url("post/{$post->id}/delete-post") ?>'>delete</a>
        </div>
    <?php endif; ?>

    <?php if ($action == "delete") : ?>
        <?= $this->renderView("post/delete", ["post" => $post]) ?>
    <?php endif; ?>
</div>
