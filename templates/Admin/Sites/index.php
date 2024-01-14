<?php

use App\Model\Table\SitesTable;
use Cake\Core\Configure;
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
        <th colspan="3" class="table_head">サイトタイトル・サイトの説明</th>
    </tr>
    <tr>
        <th class="column_title">サイトタイトル</th>
        <td colspan="2"><?= h($site->site_title) ?></td>
    </tr>
    <tr>
        <th class="column_title">ディスクリプション</th>
        <td colspan="2"><?= !empty($site->site_description) ? nl2br(h($site->site_description)) : '' ?></td>
    </tr>
    <tr>
        <th colspan="3" class="table_head">各項目について</th>
    </tr>
    <tr>
        <th></th>
        <th>タイトル名</th>
        <th>表示・非表示</th>
    </tr>
    <!-- <tr>
        <th>日付のフォーマット</th>
        <td>yyyy/mm/dd</td>
    </tr> -->
    <tr>
        <th class="column_title">経歴</th>
        <td><?= $site->histories_title ?></td>
        <td>
            <span class="view_icon <?= $site->histories_flg ? "true" : "false" ?>"><?= $site->histories_flg ? "表示" : "非表示" ?></span>
        </td>
    </tr>
    <tr>
        <th class="column_title">実績</th>
        <td><?= $site->works_title ?></td>
        <td>
            <span class="view_icon <?= $site->works_flg ? "true" : "false" ?>"><?= $site->works_flg ? "表示" : "非表示" ?></span>
        </td>
    </tr>
    <tr>
        <th class="column_title">その他</th>
        <td><?= $site->others_title ?></td>
        <td>
            <span class="view_icon <?= $site->others_flg ? "true" : "false" ?>"><?= $site->others_flg ? "表示" : "非表示" ?></span>
        </td>
    </tr>
    <tr>
        <th class="column_title">お問い合わせ</th>
        <td><?= $site->contacts_title ?></td>
        <td>
            <span class="view_icon <?= $site->contacts_flg ? "true" : "false" ?>"><?= $site->contacts_flg ? "表示" : "非表示" ?></span>
        </td>
    </tr>
</table>

<?= $this->Html->link(Configure::read('button.edit'), ['action' => 'edit'], ['class' => 'button default mt16']) ?>

<table class="current_content_table mt64">
    <tr>
        <th>ファビコン<?= $this->Html->link(Configure::read('button.image_edit'), ['action' => 'editFaviconImage'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td>
            <?php $path = SitesTable::FAVICON_PATH . $auth->username . '/' . $site->favicon_path ?>
            <?= !empty($site->favicon_path) ? $this->Html->image($path, ['class' => 'rectangle_image']) : "未設定" ?>
        </td>
    </tr>
</table>