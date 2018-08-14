<div class="posts">
    <h1>Posts with tag: <?= $tag ?></h1>

    <p>Sort by <a href="<?= $this->url("tags/{$tag}?sort=best") ?>">best</a> |
                  <a href="<?= $this->url("tags/{$tag}?sort=old") ?>">oldest</a> |
                  <a href="<?= $this->url("tags/{$tag}?sort=new") ?>">newest</a> |
                  <a href="<?= $this->url("tags/{$tag}?sort=popular") ?>">most popular</a></p>

    <?php foreach ($tag_posts as $post) : ?>
        <?php
        if (!$post->isUserAdmin && $post->deleted && strtotime($post->deleted) < time()) {
            continue;
        }
        $points = ( (int)$post->upvote - (int)$post->downvote );

        if (!$post->userObject->deleted) {
            $username = $post->userObject->username;
            $username = "<a href='" . $this->url("profile") . "/$username'>$username</a>";
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

            <table class='title'>
                <tr>
                    <td><a href="<?= $this->url("post/{$post->id}") ?>">
                        <?= !$post->deleted ? $title : "<p><i>deleted</i></p>" ?>
                    </a></td>
                    <td><span class='tags'><?= $tags ?></span></td>
                </tr>
            </table>

            <div class='stats'>
                <?= $post->commentCount ?> comments,
                <?= $points ?> points,
                created by <?= $username ?>,
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
