<h1>Welcome</h1>

<p>Welcome to our community, where we talk everything and anything about Seinfeld.</p>

<div class="front-sections">

<div class="new-posts">
<a href="<?= $this->url("post") ?>"><h2>New posts</h2></a>
<?php foreach ($posts as $post) : ?>
    <?php
    if (!$post->isUserAdmin && $post->deleted && strtotime($post->deleted) < time()) {
        continue;
    }

    if (!$post->userObject->deleted) {
        $username = $post->userObject->username;
        $username = "<a href='" . $this->url("profile") . "/$username'>$username</a> ({$post->user_rank})";
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
        <table>
            <tr>
                <td class='title'><a href="<?= $this->url("post/{$post->id}") ?>">
                    <?= !$post->deleted ? $title : "<p><i>deleted</i></p>" ?>
                </a></td>
                <td><span class='tags'><?= $tags ?></span></td>
            </tr>
        </table>

        <div class='stats'>
            created by <?= $username ?> <?= $created ?>
        </div>
    </div>
<?php endforeach ?>
</div>



<div class="pop-tags">
<a href="<?= $this->url("tags") ?>"><h2>Popular tags</h2></a>
<?php
$tagUrl = $this->url("tags");
foreach ($pop_tags as $tag) {
    ?>
    <p><a href="<?= $tagUrl . "/" .  $textfilter->parse($tag->title, ["htmlentities"])->text ?>">
        <?= $textfilter->parse($tag->title, ["htmlentities"])->text ?> (<?= $tag->count ?>)
    </a></p>
    <?php
}
?>
</div>



<div class="active-profiles">
<a href="<?= $this->url("profiles") ?>"><h2>Active users</h2></a>
<?php
$profileUrl = $this->url("profile");
foreach ($users as $user) {
    $gravatarString = md5(strtolower(trim($user->email)));
    ?>
    <table class="users">
        <tr>
            <td>
                <a href="<?= $profileUrl . "/" .  $user->username ?>">
                    <table>
                        <tr>
                            <td>
                                <img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=40'>
                            </td>
                            <td>
                                <?= $user->username ?>
                            </td>
                        </tr>
                    </table>
                </a>
            </td>
            <td class="ranking">(rank: <?= $user->rank ?>)</td>
        </tr>
    </table>
    <?php
} ?>
</div>

</div>
