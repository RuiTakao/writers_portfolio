<?php

use App\Model\Table\DesignsTable;
use Cake\Core\Configure;

// ヘッダー画像が設定されているか判定
$setting_button_class = "button default";
$path = null;
$setting_action = "#";
if (empty($design->fv_image_path)) {
    $setting_button_class .= ' disabled';
} else {
    $path = DesignsTable::FV_IMAGE_PC_PATH . $auth->username . '/' . $design->fv_image_path;
    $setting_action = "settingHeaderImage";
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイトデザイン設定', ['action' => 'index']) ?> > サイトTOP画像編集
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
<?= $this->Form->create($design, [
    'url' => ['controller' => 'Designs', 'action' => 'editFvImagePc'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('fv_image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false, 'required' => false]) ?>
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default']) ?>
    <?= $this->Html->link('サイトTOP画像の設定', ['action' => $setting_action], ['class' => $setting_button_class]) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<?php if (!is_null($path)) : ?>
    <p class="current_content_title mt64">現在の画像</p>
    <?= $this->Html->image($path, ['class' => 'rectangle_image']) ?>
<?php endif; ?>