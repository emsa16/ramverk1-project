<?php
/**
 * View to delete a user.
 */

// Create urls for navigation
$urlToViewItems = $this->url("admin");

?><h1><?= $header ?></h1>

<h4>Are you sure you want to delete this user? This will not remove the user's posts or comments.</h4>
<dl class="dl-small">
    <dt>ID:</dt>
    <dd><?= $user->id ?></dd>
    <dt>Username:</dt>
    <dd><?= htmlspecialchars($user->username) ?></dd>
    <dt>Email adress:</dt>
    <dd><?= htmlspecialchars($user->email) ?></dd>
</dl>

<form action="<?= $this->currentUrl() ?>" method="post">
    <input type="hidden" name="action" value="delete">
    <input type="submit" value="Remove">
    <a class="btn btn-2" href="<?= $this->url('admin') ?>">Cancel</a>
</form>

<p>
    <a href="<?= $urlToViewItems ?>">Show all</a>
</p>
