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
                <?php if (is_null($profile->image_path) || !file_exists(WWW_ROOT . 'img/users/profiles/' . $auth->username . '/' . $profile->image_path)) : ?>
                    <div class="image_blank"></div>
                <?php else : ?>
                    <?= $this->Html->image('users/profiles/' . $auth->username . '/' . $profile->image_path) ?>
                <?php endif; ?>
                <?= $this->Html->link('画像の編集', ['action' => 'edit_image'], ['class' => 'edit_image']) ?>
            </div>
        </div>
        <div class="flex_right">
            <ul class="profile_content_list">
                <li class="profile_content_item">
                    <span class="title">名前（表示名）</span><span class="text"><?= h($profile->view_name) ?></span>
                </li>
                <li class="profile_content_item">
                    <span class="title">肩書（仕事名）</span><span class="text"><?= h($profile->works) ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile_text">
        <p class="title">プロフィール文</p>
        <p class="text"><?= nl2br(h($profile->profile_text)) ?></p>
    </div>
</div>
<?= $this->Html->link('編集する', [
    'controller' => 'Profiles', 'action' => 'edit'
], [
    'class' => 'button'
]) ?>