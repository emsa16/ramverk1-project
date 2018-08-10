<h1><?= $username ?></h1>

<img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=40'>


<h3>Posts</h3>
<?php foreach ($userPosts as $post) {
    $url = $this->url("post/" . $post->id);
    $title = $textfilter->parse($post->title, ["htmlentities", "markdown"])->text;
    echo "<a href='$url'>$title</a>";
}
?>

<h3>Comments</h3>
<?php foreach ($userComments as $comment) {
    $comment_url = $this->url("post/" . $comment->post_id . "#" . $comment->id);
    $content = mb_strlen($comment->content) > 50 ? substr($comment->content, 0, 47) . "..." : $comment->content;
    $post_url = $this->url("post/" . $comment->post_id);
    $post_title = $comment->postObject->title;
    echo "<p><a href='$comment_url'>$content</a> in the post: <a href='$post_url'>$post_title</a><p>";
}
?>

<h3>Votes</h3>
<?php foreach ($votes as $vote) {
    if ($vote->vote_value) {
        $arrow = "&uarr;";
    } else {
        $arrow = "&darr;";
    }

    if (property_exists($vote, 'post_id')) {
        $url = $this->url("post/" . $vote->post_id);
        $title = $vote->postObject->title;
        echo "<p>$arrow POST: <a href='$url'>$title</a></p>";
    } else if (property_exists($vote, 'comment_id')) {
        $url = $this->url("post/" . $vote->commentObject->post_id . "#" . $vote->comment_id);
        $content = $vote->commentObject->content;
        $post_url = $this->url("post/" . $vote->commentObject->post_id);
        $post_title = $vote->commentObject->postObject->title;
        echo "<p>$arrow COMMENT: <a href='$url'>$content</a> in the post: <a href='$post_url'>$post_title</a></p>";
    }
}
?>

<h3>Received badges</h3>

LATER

<h3>Given badges</h3>

LATER
