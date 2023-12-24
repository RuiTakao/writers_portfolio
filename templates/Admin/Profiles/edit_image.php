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
<?= $this->Form->create($profile, [
    'url' => ['controller' => 'Profiles', 'action' => 'editImage'],
    'type' => 'file',
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('image_path', ['type' => 'file', 'class' => 'dropify', 'label' => false,]) ?>
<?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<p class="current_content_title mt64">現在の画像</p>
<?= $this->Html->image($profile_image_path, ['class' => 'square_image']) ?>