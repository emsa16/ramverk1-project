<h1>Tags</h1>

<p>Sort by <a href="<?= $this->url("tags?sort=name") ?>">name</a> |
           <a href="<?= $this->url("tags?sort=popularity") ?>">popularity</a></p>

<?php
$tagUrl = $this->url("tags");
foreach ($tags as $tag) {
    ?>
    <p><a href="<?= $tagUrl . "/" .  $textfilter->parse($tag->title, ["htmlentities"])->text ?>"><?= $textfilter->parse($tag->title, ["htmlentities"])->text ?> (<?= $tag->count ?>)</a></p>
<?php
}
