<?php

use App\Model\Table\ProfilesTable;
use Cake\Core\Configure;

// プロフィール画像が設定されているか判定
if (is_null($profile->image_path) || !file_exists(ProfilesTable::ROOT_PROFILE_IMAGE_PATH)) {
    $profile_image_path = ProfilesTable::BLANK_PROFILE_IMAGE_PATH;
} else {
    $profile_image_path = ProfilesTable::PROFILE_IMAGE_PATH .  $auth->username . '/' . $profile->image_path;
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('プロフィール設定', ['action' => 'index']) ?> > プロフィール画像編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css([
    'dropify/css/dropify.min.css',
    'admin/profiles'
]) ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?= $this->Html->script([
    'dropify/dropify.min.js',
    'profiles/profiles'
]) ?>
<?php $this->end() ?>

<?= $this->Form->create($profile, [
    'url' => ['controller' => 'Profiles', 'action' => 'editImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<div class="profile">
    <?= $this->Form->control('image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false,]) ?>
</div>
<?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button']) ?>
<?= $this->Form->end() ?>

<p class="before_image_title">現在の画像</p>
<div class="profile_image">
    <?= $this->Html->image($profile_image_path) ?>
</div>