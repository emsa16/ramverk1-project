<?php
$current = $this->currentUrl();
$visibility = $this->di->session->has("username") ? 'login' : 'logout';
?>

<nav
    <?php foreach ($navbar["config"] as $config => $configVal) : ?>
        <?php if ($config === 'navbar-class') : ?>
             class='<?= $configVal ?>'
        <?php endif; ?>
    <?php endforeach; ?>
>
    <ul>
        <?php foreach ($navbar["items"] as $item) : ?>
            <?php if (isset($item["visibility"]) && $visibility !== $item["visibility"]) {
                continue;
            }
            ?>
            <li
            <?php if ($this->url($item["route"]) === $current) : ?>
                 class='current'
            <?php endif; ?>
            ><a href='<?= $this->url($item["route"]) ?>'><?= $item["text"] ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
