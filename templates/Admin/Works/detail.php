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

    .content_title_sub {
        font-weight: 600;
        border-bottom: 1px solid #333;
        padding: 2px 4px;
        font-size: 18px;
    }

    .mt32 {
        margin-top: 32px;
    }

    .button {
        padding: 4px;
    }

    .works_content_image {
        width: 768px;
        height: auto;
        max-height: 368px;
        margin-top: 16px;
    }

    .works_content_image img {
        display: block;
        width: 100%;
        height: auto;
        max-height: 368px;
    }

    .works_content_link {
        margin-top: 16px;
        font-size: 14px;
    }

    .works_content_detail {
        font-size: 16px;
        letter-spacing: .2em;
        line-height: 1.8em;
        margin-top: 32px;
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

<h3 class="content_title_sub" style="margin-top: 48px;"><?= h($work->title) ?></h3>

<?php /** 画像パス */ ?>
<?php $path = $auth->username . '/' . $work->id . '/' . $work->image_path; ?>
<?php if (!is_null($work->image_path) && $work->image_path != '' && file_exists(WorksTable::ROOT_WORKS_IMAGE_PATH . $path)) : ?>
    <div class="works_content_image"><?= $this->Html->image(WorksTable::WORKS_IMAGE_PATH . $path) ?></div>
<?php endif; ?>

<?php if (!empty($work->url_path)) : ?>
    <p class="works_content_link">関連リンク：
        <?php if (!empty($work->url_name)) : ?>
            <a href="<?= h($work->url_path) ?>"><?= !empty($work->url_name) ? h($work->url_name) : h($work->url_path) ?></a>（<?= h($work->url_path) ?>）
        <?php else : ?>
            <a href="<?= h($work->url_path) ?>"><?= !empty($work->url_name) ? h($work->url_name) : h($work->url_path) ?></a>
        <?php endif; ?>
    </p>
<?php endif; ?>

<p class="works_content_detail"><?= h($work->overview) ?></p>