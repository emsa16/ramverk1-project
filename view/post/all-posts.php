<div class="posts">
    <h1>Posts</h1>

    <?php if ($isLoggedIn) : ?>
        <p><a href="<?= $this->url("post/create") ?>">Create new post</a></p>
    <?php else : ?>
        <p><a href="<?= $this->url('login') ?>">Login</a> to start posting.</p>
    <?php endif; ?>

    <p>Sort by <a href="<?= $this->url("post?sort=best") ?>">best</a> |
                  <a href="<?= $this->url("post?sort=old") ?>">oldest</a> |
                  <a href="<?= $this->url("post?sort=new") ?>">newest</a> |
                  <a href="<?= $this->url("post?sort=popular") ?>">most popular</a></p>

    <?php foreach ($posts as $post) : ?>
        <?php
        if (!$post->isUserAdmin && $post->deleted && strtotime($post->deleted) < time()) {
            continue;
        }
        $points = ( (int)$post->upvote - (int)$post->downvote );

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
            <?= $this->renderView("post/vote-buttons", ["post" => $post, "view" => 'overview']) ?>

            <table>
                <tr>
                    <td class='title'><a href="<?= $this->url("post/{$post->id}") ?>">
                        <?= !$post->deleted ? $title : "<p><i>deleted</i></p>" ?>
                    </a></td>
                    <td><span class='tags'><?= $tags ?></span></td>
                </tr>
            </table>

            <div class='stats'>
                <?= $post->commentCount ?> comments,
                <?= $points ?> points, created by <?= $username ?>,
                added <?= $created ?>
            </div>

            <?php if ($post->isUserOwner || $post->isUserAdmin) : ?>
                <div class='actions'>
                    <a href='<?= $this->url("post/{$post->id}/edit-post") ?>'>edit</a>
                    | <a href='<?= $this->url("post/{$post->id}/delete-post") ?>'>delete</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach ?>
</div>
