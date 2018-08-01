<div class="posts">
    <h3>Posts</h3>

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
        $created = $post->timeElapsedString($post->created);
        $title = $textfilter->parse($post->title, ["htmlentities", "markdown"])->text;
        ?>

        <div class='post'>
            <?= $this->renderView("post/vote-buttons", ["post" => $post, "view" => 'overview']) ?>

            <a href="<?= $this->url("post/{$post->id}") ?>"><div class='title'>
                <?= !$post->deleted ? $title : "<p><i>deleted</i></p>" ?>
            </div></a>

            <div class='stats'>
                <?= $post->commentCount ?> comments, <?= $points ?> points, created by <?= !$post->userObject->deleted ? $post->userObject->username : '[deleted]' ?>, added <?= $created ?>
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
