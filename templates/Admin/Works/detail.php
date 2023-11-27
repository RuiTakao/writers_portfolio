<?php

/** ページタイトル */ ?>
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
</style>
<?php $this->end() ?>

<div class="flex justify-between align-center mt32">
    <p class="content_title">実績の追加<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
    <div class="flex" style="gap: 16px">
        <?= $this->Html->link('編集', ['action' => 'edit', $work->id], ['class' => 'button']) ?>
        <?= $this->Form->postLink('削除', ['controller' => 'Works', 'action' => 'delete', $work->id], ['class' => 'button delete', 'confirm' => '削除しますか？']) ?>
    </div>

</div>

<p class="mt32" style="border-bottom: 1px solid #333; padding-bottom: 8px;margin-top:48px;"><?= h($work->title) ?></p>
<p style="margin-top: 32px;"><?= nl2br(h($work->overview)) ?></p>

<table style="margin-top: 56px;">
    <tr>
        <th>画像<?= $this->Html->link('画像の設定', ['action' => 'editImage', $work->id], ['class' => 'button list-button']) ?></th>
    </tr>
    <tr>
        <td>
            <div class="">
                <?= $this->Html->image('users/works/' . $auth->username . '/' . $work->id . '/' . $work->image_path, ['style' => 'height:180px; display: block;']) ?>
            </div>
            <p style="margin-top: 8px;">
                <?= $this->html->link($work->url, ['url' => '#']) ?>
            </p>
        </td>
    </tr>
</table>