<h1>Users</h1>

<p>Sort by <a href="<?= $this->url("profiles?sort=name") ?>">name</a> |
           <a href="<?= $this->url("profiles?sort=rank") ?>">rank</a></p>

<?php
$profileUrl = $this->url("profile");
foreach ($users as $user) {
    if ($user->deleted) {
        continue;
    }
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
}
