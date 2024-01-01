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
プロフィール設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/profiles') ?>
<?php $this->end() ?>

<div class="flex">
    <div class="flex_left">
        <?= $this->Html->image($profile_image_path, ['class' => 'square_image']) ?>
        <?= $this->Html->link(Configure::read('button.image_edit'), ['action' => 'edit_image'], ['class' => 'button edit_image_button mt8']) ?>
    </div>
    <ul class="flex_right adjust_padding">
        <li class="border_bottom">名前（表示名）<span class="ml32 fz32 fwb"><?= h($profile->view_name) ?></span></li>
        <li class="border_bottom mt16"> 肩書（仕事名）<span class="ml32 fz32 fwb"><?= h($profile->works) ?></span></li>
    </ul>
</div>

<?php /* プロフィール */ ?>
<p class="current_content_title mt32">プロフィール文</p>
<p class="text"><?= !empty($profile->profile_text) ? nl2br(h($profile->profile_text)) : '' ?></p>

<?php /* 編集ボタン */ ?>
<?= $this->Html->link(Configure::read('button.edit'), ['controller' => 'Profiles', 'action' => 'edit'], ['class' => 'button default mt32']) ?>