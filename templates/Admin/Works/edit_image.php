<?php

use App\Model\Table\WorksTable;

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

<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > <?= $this->Html->link('詳細', ['action' => 'detail', $work->id]) ?> > 画像の編集
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css([
    'dropify/css/dropify.min.css',
    'admin/works'
]) ?>
<style>
    .button {
        width: 100%;
    }

    .button.delete,
    .submit {
        width: calc(100% / 2 - 8px);
    }

    .input.text {
        width: 50%;
        margin-bottom: 16px;
    }

    .input.text input {
        height: 32px;
    }

    .input.file {
        margin-bottom: 16px;
    }

    .mt32 {
        margin-top: 32px;
    }
</style>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script([
    'dropify/dropify.min.js',
    'profiles/profiles'
]) ?>
<?php $this->end() ?>

<p class="content_title">画像の編集<?= $this->Html->link('< 戻る', ['action' => 'detail', $work->id]) ?></p>

<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'editImage', $work->id],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('image_path', ['class' => 'dropify mb16', 'type' => 'file', 'label' => false, 'data-default-file' => h($data_default_file)]) ?>
<div class="flex mt32" style="gap: 16px; width: 50%">
    <?= $this->Form->submit('設定を保存', ['class' => 'button']) ?>
    <?= $this->Form->postLink('画像の削除', ['controller' => 'Works', 'action' => 'deleteImage', $work->id], ['block' => true, 'class' => 'button delete', 'confirm' => '削除しますか？']) ?>
</div>
<?= $this->Form->end() ?>
<?= $this->fetch('postLink') ?>

<p style="border-bottom: 1px solid #333; padding-bottom: 8px;margin-top:56px;"><?= h($work->title) ?></p>
<p style="margin-top: 32px;"><?= nl2br(h($work->overview)) ?></p>