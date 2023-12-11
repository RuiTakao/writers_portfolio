<?php

use App\Model\Table\SitesTable;
use Cake\Core\Configure;

// ファビコン画像が設定されているか判定
if (is_null($site->favicon_path) || !file_exists(SitesTable::ROOT_FAVICON_PATH)) {
    $favicon_path = SitesTable::BLANK_FAVICON_PATH;
} else {
    $favicon_path = SitesTable::FAVICON_PATH . $auth->username . '/' . $site->favicon_path;
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイト設定', ['action' => 'index']) ?> > ファビコン画像編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?= $this->Html->css('dropify/css/dropify.min.css') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script([
    'dropify/dropify.min.js',
    'dropify/favicon',
]) ?>
<?php $this->end() ?>

<?= $this->Form->create($site, [
    'url' => ['controller' => 'Sites', 'action' => 'editFaviconImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<div class="profile">
    <?= $this->Form->control('favicon_path', ['type' => 'file', 'class' => 'dropify', 'label' => false]) ?>
</div>
<?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>

<p class="before_image_title">現在の画像</p>
<div class="favicon_image">
    <?= $this->Html->image($favicon_path) ?>
</div>