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
<?= $this->Html->link('プロフィール設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/profiles') ?>
<?php $this->end() ?>

<?= $this->Form->create($profile, ['url' => ['controller' => 'Profiles', 'action' => 'edit'], 'onSubmit' => 'return checkEdit()']) ?>

<div class="flex">
    <?= $this->Html->image($profile_image_path, ['class' => 'square_image']) ?>
    <ul class="flex_right">
        <li><?= $this->Form->control('view_name', ['label' => '名前（表示名）', 'value' => $profile->view_name]) ?></li>
        <li class="mt16"><?= $this->Form->control('works', ['label' => '肩書（仕事名）', 'value' => $profile->works]) ?></li>
    </ul>
</div>

<div class="mt32">
    <?= $this->Form->control('profile_text', ['type' => 'textarea', 'label' => 'プロフィール文', 'value' => $profile->profile_text]) ?>
</div>

<?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>