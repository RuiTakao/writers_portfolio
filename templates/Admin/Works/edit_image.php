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
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> &gt; <?= $this->Html->link('編集', ['action' => 'edit', $work->id]) ?> &gt; 画像の編集
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
    'dropify/profiles'
]) ?>
<?php $this->end() ?>

<?php /* form */ ?>
<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'editImage', $work->id],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false,]) ?>
<?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<p class="current_content_title mt64">現在の画像</p>
<?= $this->Html->image($image_path, ['class' => 'rectangle_image']) ?>