<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
サイト設定 > ヘッダー画像編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?= $this->Html->css('dropify/css/dropify.min.css') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script('dropify/dropify.min.js') ?>
<?= $this->Html->script('dropify/index') ?>
<?php $this->end() ?>

<p class="content_title">ヘッダー画像編集<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
<?= $this->Form->create($site, [
    'url' => ['controller' => 'Sites', 'action' => 'editHeaderImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<div class="profile">
    <?= $this->Form->control('header_image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false,]) ?>
</div>
<?= $this->Form->submit('この内容で変更する',  ['class' => 'button']) ?>
<?= $this->Form->end() ?>

<p style="margin-top: 56px;">現在の画像</p>
<div class="profile_image">
<?php if (is_null($site->header_image_path) || !file_exists(WWW_ROOT . 'img/users/sites/headers/' . $auth->username . '/' . $site->header_image_path)) : ?>
        <div class="image_blank">画像無し</div>
    <?php else : ?>
        <?= $this->Html->image('users/sites/headers/' . $auth->username . '/' . $site->header_image_path) ?>
    <?php endif; ?>
</div>