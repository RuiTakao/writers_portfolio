<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
プロフィール設定
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/profiles') ?>
<?php $this->end() ?>

<div class="profile">
    <div class="flex">
        <div class="flex_left ">
            <div class="profile_image">
                <?php if (is_null($profiles->image_path) || !file_exists(WWW_ROOT . 'img/users/profiles/' . $auth->username . '/' . $profiles->image_path)) : ?>
                    <div class="image_blank"></div>
                <?php else : ?>
                    <?= $this->Html->image('users/profiles/' . $auth->username . '/' . $profiles->image_path) ?>
                <?php endif; ?>
                <?= $this->Html->link('画像の編集', ['action' => 'edit_image'], ['class' => 'edit_image']) ?>
            </div>
        </div>
        <div class="flex_right">
            <ul class="profile_content_list">
                <li class="profile_content_item">
                    <span class="title">名前（表示名）</span><span class="text"><?= $profiles->view_name ?></span>
                </li>
                <li class="profile_content_item">
                    <span class="title">肩書（仕事名）</span><span class="text"><?= $profiles->work ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile_text">
        <p class="title">プロフィール文</p>
        <p class="text"><?= $profiles->profile_text ?></p>
    </div>
</div>
<?= $this->Html->link('編集する', [
    'controller' => 'Profiles', 'action' => 'edit'
], [
    'class' => 'button'
]) ?>