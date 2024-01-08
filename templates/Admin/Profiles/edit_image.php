<?php

use App\Model\Table\ProfilesTable;
use Cake\Core\Configure;
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
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<?php if (!empty($profile->image_path)) : ?>
    <p class="current_content_title mt64">現在の画像</p>
    <?php $path = ProfilesTable::PROFILE_IMAGE_PATH .  h($auth->username) . '/' . h($profile->image_path) ?>
    <?= $this->Html->image($path, ['class' => 'square_image']) ?>
<?php endif; ?>