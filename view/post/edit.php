<?php
$actionUrl = $this->url("post/{$post->id}/edit-post");
?>

<form id="<?= $form->id ?>" class="form" method="post" action="<?= $actionUrl ?>">
    <input type="hidden" name="post_id" value="<?= $post->id ?>">

    <div class="form-control">
        <div class="form-label"><?= $form->label('title', 'Title:') ?></label></div>
        <div class="form-input">
            <?= $form->text("title", ['required' => true]) ?>

            <?php if ($form->hasError('title')) : ?>
                <div class="form-error"><?= $form->getError('title') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-control">
        <div class="form-label"><?= $form->label('content', 'Content:') ?></label></div>
        <div class="form-input">
            <?= $form->textarea("content", ['rows' => '6', 'cols' => '60', 'required' => true]) ?>

            <?php if ($form->hasError('content')) : ?>
                <div class="form-error"><?= $form->getError('content') ?></div>
            <?php endif; ?>
        </div>
    </div>

    <br>

    <div class="form-control">
        <div class="form-label"></div>
        <div class="form-input">
            <input type="submit" value="Save">
        </div>
    </div>
</form>
