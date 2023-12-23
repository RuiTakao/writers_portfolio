<?php

use App\Model\Table\SitesTable;
use Cake\Core\Configure;

// ヘッダー画像が設定されているか判定
if (is_null($site->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
    $header_image_path = SitesTable::BLANK_HEADER_ADMIN_IMAGE_PATH;
    $setting_button = 'pointer-events: none; background: #005e85;';
} else {
    $header_image_path = SitesTable::HEADER_IMAGE_PATH . $auth->username . '/' . $site->header_image_path;
    $setting_button = '';
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイト設定', ['action' => 'index']) ?> > ヘッダー画像編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css(['dropify/css/dropify.min.css']) ?>
<?php $this->end() ?>

<?php /* js */ ?>
<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script([
    'dropify/dropify.min.js',
    'dropify/header',
]) ?>
<?php $this->end() ?>

<?php /* form */ ?>
<?= $this->Form->create($site, [
    'url' => ['controller' => 'Sites', 'action' => 'editHeaderImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('header_image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false, 'required' => false]) ?>
<div class="button-container default mt16">
    <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default']) ?>
    <?= $this->Html->link('ヘッダー画像の設定', ['action' => 'settingHeaderImage'], ['class' => 'button default', 'target' => '_blank', 'style' => $setting_button]) ?>
</div>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<p class="current_content_title mt64">現在の画像</p>
<?= $this->Html->image($header_image_path, ['class' => 'rectangle_image']) ?>