<?php

use App\Model\Table\SitesTable;
use Cake\Core\Configure;

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

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
サイト設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?php $this->end() ?>

<table class="list_table">
    <tr>
        <th>サイトタイトル</th>
        <td><?= h($site->site_title) ?></td>
    </tr>
    <tr>
        <th>ディスクリプション</th>
        <td><?= !empty($site->site_description) ? nl2br(h($site->site_description)) : '' ?></td>
    </tr>
    <!-- <tr>
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
    </tr> -->
</table>

<?= $this->Html->link(Configure::read('button.edit'), ['action' => 'edit'], ['class' => 'button default mt16']) ?>

<table class="current_content_table mt64">
    <tr>
        <th>ファビコン<?= $this->Html->link(Configure::read('button.image_edit'), ['action' => 'editFaviconImage'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td><?= $this->Html->image($favicon_path, ['class' => 'square_image']) ?></td>
    </tr>
</table>

<table class="current_content_table mt32">
    <tr>
        <th>ヘッダー画像<?= $this->Html->link(Configure::read('button.image_edit'), ['action' => 'editHeaderImage'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td><?= $this->Html->image($header_image_path, ['class' => 'rectangle_image']) ?></td>
    </tr>
</table>