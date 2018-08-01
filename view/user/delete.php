<?php
/**
 * View to delete user account.
 */
?>
<h1>Remove account</h1>

<h4>Are you sure you want to remove your account? NOTE: This will not remove your posts or comments.</h4>
<dl class="dl-small">
    <dt>Username:</dt>
    <dd><?= htmlspecialchars($user->username) ?></dd>
    <dt>Email adress:</dt>
    <dd><?= htmlspecialchars($user->email) ?></dd>
</dl>

<form action="<?= $this->currentUrl() ?>" method="post">
    <input type="hidden" name="action" value="delete">
    <input type="submit" value="Remove">
    <a class="btn btn-2" href="<?= $this->url('user') ?>">Cancel</a>
</form>
