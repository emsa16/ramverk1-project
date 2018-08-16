<div class="profile-page">

<h1><?= $username ?></h1>

<table class="profile-table">
    <tr>
        <td>
            <img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=160'>
        </td>
        <td>
            <h2>Ranking</h2>
            <p><?= $rank ?> points</p>
        </td>
    </tr>
</table>


<h2>Posts</h2>
<?php foreach ($userPosts as $post) {
    $url = $this->url("post/" . $post->id);
    $title = $textfilter->parse($post->title, ["htmlentities", "markdown"])->text;
    echo "<a href='$url'>$title</a>";
}
?>

<h2>Comments</h2>
<?php foreach ($userComments as $comment) {
    $comment_url = $this->url("post/" . $comment->post_id . "#" . $comment->id);
    $content = mb_strlen($comment->content) > 50 ? substr($comment->content, 0, 47) . "..." : $comment->content;
    $content = $textfilter->parse($content, ["htmlentities", "markdown"])->text;
    $post_url = $this->url("post/" . $comment->post_id);
    $post_title = $textfilter->parse($comment->postObject->title, ["htmlentities", "markdown"])->text;
    ?>
    <table>
        <tr>
            <td><a href='<?= $comment_url ?>'><?= $content ?></a></td>
            <td> in the post: </td>
            <td><a href='<?= $post_url ?>'><?= $post_title ?></a></td>
        </tr>
    </table>
    <?php
}
?>

<h2>Received badges</h2>
<?php foreach ($received_badges as $comment) {
        $url = $this->url("post/" . $comment->post_id . "#" . $comment->id);
        $content = $comment->content;
        $content = mb_strlen($content) > 50 ? substr($content, 0, 47) . "..." : $content;
        $content = $textfilter->parse($content, ["htmlentities", "markdown"])->text;
        $post_url = $this->url("post/" . $comment->post_id);
        $post_title = $textfilter->parse($comment->postObject->title, ["htmlentities", "markdown"])->text;
    ?>
        <table>
            <tr>
                <td><a href='<?= $url ?>'><?= $content ?></a></td>
                <td> in the post: </td>
                <td><a href='<?= $post_url ?>'><?= $post_title ?></a></td>
            </tr>
        </table>
        <?php
}
?>

<h2>Given badges</h2>
<?php foreach ($given_badges as $badge) {
        $url = $this->url("post/" . $badge->commentObject->post_id . "#" . $badge->comment_id);
        $content = $badge->commentObject->content;
        $content = mb_strlen($content) > 50 ? substr($content, 0, 47) . "..." : $content;
        $content = $textfilter->parse($content, ["htmlentities", "markdown"])->text;
        $post_url = $this->url("post/" . $badge->commentObject->post_id);
        $post_title = $textfilter->parse($badge->commentObject->postObject->title, ["htmlentities", "markdown"])->text;
    ?>
        <table>
            <tr>
                <td><a href='<?= $url ?>'><?= $content ?></a></td>
                <td> in the post: </td>
                <td><a href='<?= $post_url ?>'><?= $post_title ?></a></td>
            </tr>
        </table>
        <?php
}
?>

<h2>Votes</h2>
<?php foreach ($votes as $vote) {
    if ($vote->vote_value) {
        $arrow = "&uarr;";
    } else {
        $arrow = "&darr;";
    }

    if (property_exists($vote, 'post_id')) {
        $url = $this->url("post/" . $vote->post_id);
        $title = $textfilter->parse($vote->postObject->title, ["htmlentities", "markdown"])->text;
        ?>
        <table>
            <tr>
                <td><?= $arrow ?> </td>
                <td><a href='<?= $url ?>'><?= $title ?></a></td>
            </tr>
        </table>
        <?php
    } elseif (property_exists($vote, 'comment_id')) {
        $url = $this->url("post/" . $vote->commentObject->post_id . "#" . $vote->comment_id);
        $content = $vote->commentObject->content;
        $content = mb_strlen($content) > 50 ? substr($content, 0, 47) . "..." : $content;
        $content = $textfilter->parse($content, ["htmlentities", "markdown"])->text;
        $post_url = $this->url("post/" . $vote->commentObject->post_id);
        $post_title = $textfilter->parse($vote->commentObject->postObject->title, ["htmlentities", "markdown"])->text;
        ?>
        <table>
            <tr>
                <td><?= $arrow ?> </td>
                <td><a href='<?= $url ?>'><?= $content ?></a></td>
                <td> in the post: </td>
                <td><a href='<?= $post_url ?>'><?= $post_title ?></a></td>
            </tr>
        </table>
        <?php
    }
}
?>

</div>
