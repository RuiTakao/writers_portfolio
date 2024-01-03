<?php

use App\Model\Table\ContactsTable;
use Cake\Core\Configure;

/**
 * 画像パス
 */
// 各々のユーザーによって決まるパス
$self_path = $auth->username . '/' . $contact->id . '/' . $contact->image_path;
// ルートからのパス
$root_image_path = ContactsTable::ROOT_WORKS_IMAGE_PATH . $self_path;
// webrootからのパス
$image_path = ContactsTable::WORKS_IMAGE_PATH . $self_path;

if (!empty($contact->image_path) && file_exists($root_image_path)) {
    $image_flg = true;
} else {
    $image_flg = false;
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('お問い合わせの設定', ['action' => 'index']) ?> &gt; <?= $this->Html->link('お問い合わせ項目の設定', ['action' => 'list']) ?> &gt; <?= $this->Html->link('編集', ['action' => 'edit']) ?> &gt; 画像の編集
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
    'dropify/works'
]) ?>
<?php $this->end() ?>

<?php /* form */ ?>
<?= $this->Form->create($contact, [
    'url' => ['controller' => 'Contacts', 'action' => 'editImage', $contact->id],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false,]) ?>
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'edit', $contact->id], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>

<?php if ($image_flg) : ?>
    <?php /* current data */ ?>
    <p class="current_content_title mt64">現在の画像</p>
    <?= $this->Html->image($image_path, ['class' => 'rectangle_image']) ?>
<?php endif; ?>