<h1>Let's talk Seinfeld</h1>

<p>Welcome to our community, where we talk everything and anything about Seinfeld.</p>

<a href="<?= $this->url("post") ?>"><h3>New posts</h3></a>
<?php foreach ($posts as $post) : ?>
    <?php
    if (!$post->isUserAdmin && $post->deleted && strtotime($post->deleted) < time()) {
        continue;
    }

    if (!$post->userObject->deleted) {
        $username = "<a href='" . $this->url("profile") . "/" .  $post->userObject->username . "'>" . $post->userObject->username . "</a> (" . $post->user_rank . ")";
    } else {
        $username = "[deleted]";
    }

    $created = $post->timeElapsedString($post->created);
    $title = $textfilter->parse($post->title, ["htmlentities", "markdown"])->text;

    $tags = "";
    if (!empty($post->tags)) {
        foreach ($post->tags as $tag) {
            $tag_url = $this->url("tags/" . $textfilter->parse($tag->title, ["htmlentities"])->text);
            $tags .= "<a href='$tag_url'>#" . $textfilter->parse($tag->title, ["htmlentities"])->text . "</a> ";
        }
    }
    ?>

    <div class='post'>
        <table class='title'>
            <tr>
                <td><a href="<?= $this->url("post/{$post->id}") ?>"><?= !$post->deleted ? $title : "<p><i>deleted</i></p>" ?></a></td>
                <td><span class='tags'><?= $tags ?></span></td>
            </tr>
        </table>

        <div class='stats'>
            created by <?= $username ?> <?= $created ?>
        </div>
    </div>
<?php endforeach ?>



<a href="<?= $this->url("tags") ?>"><h3>Popular tags</h3></a>
<?php
$tagUrl = $this->url("tags");
foreach ($pop_tags as $tag) {
    ?>
    <p><a href="<?= $tagUrl . "/" .  $textfilter->parse($tag->title, ["htmlentities"])->text ?>"><?= $textfilter->parse($tag->title, ["htmlentities"])->text ?> (<?= $tag->count ?>)</a></p>
<?php
}
?>



<a href="<?= $this->url("profiles") ?>"><h3>Active users</h3></a>
<?php
$profileUrl = $this->url("profile");
foreach ($users as $user) {
    $gravatarString = md5(strtolower(trim($user->email)));
    ?>
    <table>
        <tr>
            <td>
                <a href="<?= $profileUrl . "/" .  $user->username ?>">
                    <img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=40'>
                    <span><?= $user->username ?></span>
                </a>
            </td>
            <td class="ranking">(rank: <?= $user->rank ?>)</td>
        </tr>
    </table>
<?php
}
