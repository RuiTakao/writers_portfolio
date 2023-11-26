<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
実績 編集
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<style>
    .button {
        width: 80px;
    }
</style>
<?php $this->end() ?>

<div class="flex justify-end" style="gap: 16px">
    <?= $this->Html->link('編集', ['action' => 'edit'], ['class' => 'button']) ?>
    <?= $this->Html->link('削除', ['action' => 'delete'], ['class' => 'button delete']) ?>
</div>

<p style="border-bottom: 1px solid #333; padding-bottom: 8px;margin-top:16px;"><?= h($work->title) ?></p>
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