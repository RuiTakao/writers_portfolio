<?php

use App\Model\Table\WorksTable;

// プロフィール画像が設定されているか判定
if (
    is_null($work->image_path) ||
    $work->image_path == "" ||
    !file_exists(WorksTable::ROOT_WORKS_IMAGE_PATH)
) {
    $work_image_path = WorksTable::BLANK_WORKS_IMAGE_PATH;
} else {
    $work_image_path = WorksTable::WORKS_IMAGE_PATH . $auth->username . '/' . $work->id . '/' . $work->image_path;
}
?>

<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > 詳細
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<style>
    .button {
        width: 80px;
    }

    .content_title {
        margin: 0;
    }

    .mt32 {
        margin-top: 32px;
    }

    .button {
        padding: 4px;
    }
</style>
<?php $this->end() ?>

<div class="flex justify-between align-center mt32">
    <p class="content_title">詳細画面<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
    <div class="flex" style="gap: 16px">
        <?= $this->Html->link('編集', ['action' => 'edit', $work->id], ['class' => 'button']) ?>
        <?= $this->Form->postLink('削除', ['controller' => 'Works', 'action' => 'delete', $work->id], ['class' => 'button delete', 'confirm' => '削除しますか？']) ?>
    </div>

</div>

<p class="mt32" style="border-bottom: 1px solid #333; padding-bottom: 8px;margin-top:48px;"><?= h($work->title) ?></p>
<?php if (!empty($work->url_path)) : ?>
    <p style="margin-top: 16px; font-size:14px;">関連リンク：
        <?php if (!empty($work->url_name)) : ?>
            <a href="<?= h($work->url_path) ?>"><?= h($work->url_name) ?></a>（<?= h($work->url_path) ?>）
        <?php else : ?>
            <a href="<?= h($work->url_path) ?>"><?= h($work->url_path) ?></a>
        <?php endif; ?>
    </p>
    <p style="margin-top: 16px;"><?= nl2br(h($work->overview)) ?></p>
<?php else : ?>
    <p style="margin-top: 32px;"><?= nl2br(h($work->overview)) ?></p>
<?php endif; ?>

<table style="margin-top: 56px;">
    <tr>
        <th>画像<?= $this->Html->link('画像の設定', ['action' => 'editImage', $work->id], ['class' => 'button list-button']) ?></th>
    </tr>
    <tr>
        <td>
            <div class="">
                <?= $this->Html->image($work_image_path, ['style' => 'height:180px; display: block; margin:0 auto;']) ?>
            </div>
        </td>
    </tr>
</table>