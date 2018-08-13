<h1>Account page</h1>

<p>Welcome <b><?= $name ?></b></p>

<p><a href="<?= $this->url("profile/" . $name) ?>">View profile</a></p>
<p><a href="<?= $this->url("user/details") ?>">Edit account details</a></p>
<p class="delete"><a href="<?= $this->url("user/delete") ?>">Remove account</a></p>
