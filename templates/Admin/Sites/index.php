<?php

use App\Model\Table\SitesTable;

// ファビコン画像が設定されているか判定
if (is_null($site->favicon_path) || !file_exists(SitesTable::ROOT_FAVICON_PATH)) {
    $favicon_path = SitesTable::BLANK_FAVICON_PATH;
} else {
    $favicon_path = SitesTable::FAVICON_PATH . $auth->username . '/' . $site->favicon_path;
}

// ヘッダー画像が設定されているか判定
if (is_null($site->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
    $header_image_path = SitesTable::BLANK_HEADER_ADMIN_IMAGE_PATH;
} else {
    $header_image_path = SitesTable::HEADER_IMAGE_PATH . $auth->username . '/' . $site->header_image_path;
}
?>

<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
サイト設定
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?php $this->end() ?>

<table class="text_table">
    <tr>
        <th>サイトタイトル</th>
        <td><?= h($site->site_title) ?></td>
    </tr>
    <tr>
        <th>ディスクリプション</th>
        <td><?= nl2br(h($site->site_description)) ?></td>
    </tr>
    <tr>
        <th colspan="2" style="text-align: center;padding: 0;">表示ステータス</th>
    </tr>
    <tr>
        <th>日付のフォーマット</th>
        <td>yyyy/mm/dd</td>
    </tr>
    <tr>
        <th>経歴</th>
        <td>表示</td>
    </tr>
    <tr>
        <th>実績</th>
        <td>表示</td>
    </tr>
    <tr>
        <th>仕事について</th>
        <td>表示</td>
    </tr>
</table>
<?= $this->Html->link('編集', ['action' => 'edit'], ['class' => 'button']) ?>

<table class="favicon_image_table image_table">
    <tr>
        <th>ファビコン<?= $this->Html->link('編集', ['action' => 'editFaviconImage'], ['class' => 'button edit_iamge']) ?></th>
    </tr>
    <tr>
        <td>
            <div class="favicon_image">
                <?= $this->Html->image($favicon_path) ?>
            </div>
        </td>
    </tr>
</table>

<table class="header_image_table image_table">
    <tr>
        <th>ヘッダー画像<?= $this->Html->link('編集', ['action' => 'editHeaderImage'], ['class' => 'button edit_iamge']) ?></th>
    </tr>
    <tr>
        <td>
            <div class="header_image">
                <?= $this->Html->image($header_image_path) ?>
            </div>
        </td>
    </tr>
</table>