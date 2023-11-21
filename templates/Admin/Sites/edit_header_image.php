<?php

use App\Model\Table\SitesTable;

// ヘッダー画像が設定されているか判定
if (is_null($site->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
    $header_image_path = SitesTable::BLANK_HEADER_IMAGE_PATH;
} else {
    $header_image_path = SitesTable::HEADER_IMAGE_PATH . $auth->username . '/' . $site->header_image_path;
}
?>

<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイト設定', ['action' => 'index']) ?> > ヘッダー画像編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?= $this->Html->css('dropify/css/dropify.min.css') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script('dropify/dropify.min.js') ?>
<?= $this->Html->script('sites/header') ?>
<?php $this->end() ?>

<p class="content_title">ヘッダー画像編集<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
<?= $this->Form->create($site, [
    'url' => ['controller' => 'Sites', 'action' => 'editHeaderImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<div class="profile">
    <?= $this->Form->control('header_image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false, 'required' => false]) ?>
</div>
<?= $this->Form->submit('この内容で変更する',  ['class' => 'button']) ?>
<?= $this->Form->end() ?>

<p class="before_image_title">現在の画像</p>
<div class="header_image">
    <?= $this->Html->image($header_image_path) ?>
</div>