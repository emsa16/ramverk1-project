<p>Sort by <a href="<?= $this->url("profiles?sort=name") ?>">name</a> |
           <a href="<?= $this->url("profiles?sort=rank") ?>">rank</a></p>

<?php
$profileUrl = $this->url("profile");
foreach ($users as $user) {
    $gravatarString = md5(strtolower(trim($user->email)));
    ?>
    <p>
        <a href="<?= $profileUrl . "/" .  $user->username ?>">
            <img src='http://www.gravatar.com/avatar/<?= $gravatarString ?>.jpg?d=identicon&s=40'>
            <span><?= $user->username ?><span>
        </a>
    </p>

<?php
}
