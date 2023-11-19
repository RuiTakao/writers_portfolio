<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
サイト設定
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?php $this->end() ?>

<table class="text_table">
    <tr>
        <th>サイトタイトル</th>
        <td></td>
    </tr>
    <tr>
        <th>ディスクリプション</th>
        <td></td>
    </tr>
</table>
<?= $this->Html->link('編集', ['action' => 'edit'], ['class' => 'button']) ?>

<table class="favicon_image_table image_table">
    <tr>
        <th>ファビコン</th>
    </tr>
    <tr>
        <td>
            <div class="profile_image">
                <?php if (is_null($site->favicon_path) || !file_exists(WWW_ROOT . 'img/users/sites/favicons/' . $auth->username . '/' . $site->favicon_path)) : ?>
                    <div class="image_blank"></div>
                <?php else : ?>
                    <?= $this->Html->image('users/sites/favicons/' . $auth->username . '/' . $site->favicon_path) ?>
                <?php endif; ?>
                <?= $this->Html->link('画像の編集', ['action' => 'edit_image'], ['class' => 'edit_image']) ?>
            </div>
        </td>
    </tr>
</table>

<table class="header_image_table image_table">
    <tr>
        <th>ヘッダー画像</th>
    </tr>
    <tr>
        <td>
            <div class="profile_image">
                <?php if (is_null($site->header_image_path) || !file_exists(WWW_ROOT . 'img/users/sites/headers/' . $auth->username . '/' . $site->header_image_path)) : ?>
                    <div class="image_blank"></div>
                <?php else : ?>
                    <?= $this->Html->image('users/sites/headers/' . $auth->username . '/' . $site->header_image_path) ?>
                <?php endif; ?>
                <?= $this->Html->link('画像の編集', ['action' => 'edit_header_image'], ['class' => 'edit_image']) ?>
            </div>
        </td>
    </tr>
</table>