<?php
/**
 * View to display all users.
 */

// Gather incoming variables and use default values if not set
$users = isset($users) ? $users : null;

// Create urls for navigation
$urlToCreate = $this->url("admin/create");

?><h1><?= $header ?></h1>

<p>
    <a href="<?= $urlToCreate ?>">Add</a>
</p>

<?php if (!$users) : ?>
    <p>There are no members.</p>
<?php
    return;
endif;
?>

<table>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Email adress</th>
        <th>Deleted</th>
    </tr>
    <?php foreach ($users as $user) : ?>
    <tr>
        <td>
            <a href="<?= $this->url("admin/update/{$user->id}") ?>"><?= $user->id ?></a>
        </td>
        <td><?= $user->username ?></td>
        <td><?= $user->email ?></td>
        <td><?= $user->deleted ? "X" : "-" ?></td>
        <td>
            <a href="<?= $this->url("admin/delete/{$user->id}") ?>">Remove</a>
        </td>

    </tr>
    <?php endforeach; ?>
</table>
