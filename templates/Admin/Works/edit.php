<?php

use App\Model\Table\WorksTable;
use Cake\Core\Configure;

/**
 * 画像パス
 */
// 各々のユーザーによって決まるパス
$self_path = $auth->username . '/' . $work->id . '/' . $work->image_path;
// ルートからのパス
$root_image_path = WorksTable::ROOT_WORKS_IMAGE_PATH . $self_path;
// webrootからのパス
$image_path = WorksTable::WORKS_IMAGE_PATH . $self_path;

$data_default_file = null;
if (!is_null($work->image_path) && $work->image_path != '' && file_exists($root_image_path)) {
    $data_default_file = $this->Url->image($image_path);
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > <?= empty($work->id) ? '追加' : '編集' ?>
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css([
    'dropify/css/dropify.min.css',
    'admin/works'
]) ?>
<?php $this->end() ?>

<?php /* js */ ?>
<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script([
    'dropify/dropify.min.js',
    'dropify/works'
]) ?>
<?php $this->end() ?>

<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'edit', $work->id],
    'type' => 'file',
    'onSubmit' => 'return checkAdd()'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'required' => false]) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要', 'required' => false, 'style' => 'height: 114px;']) ?>
<p class="sub_title">関連リンク</p>
<?= $this->Form->control('url_path', ['label' => 'URL', 'required' => false]) ?>
<?= $this->Form->control('url_name', ['label' => 'URL名', 'required' => false]) ?>

<div class="sub_title flex">
    <p>関連画像</p>
    <?php if (!is_null($data_default_file)) : ?>
        <?= $this->Form->postLink(
            '画像の削除',
            ['controller' => 'Works', 'action' => 'deleteImage', $work->id],
            ['block' => true, 'class' => 'button list-button delete', 'confirm' => '画像を削除しますか', 'style' => 'width: 116px;']
        ) ?>
    <?php endif; ?>
</div>
<?= $this->Form->control('image_path', ['class' => 'dropify', 'type' => 'file', 'label' => false, 'data-default-file' => h($data_default_file)]) ?>

<?= $this->Form->submit(empty($work->id) ? Configure::read('button.add') : Configure::read('button.save'),['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>
<?= $this->fetch('postLink') ?>