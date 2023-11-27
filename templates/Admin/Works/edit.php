<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > <?= $this->Html->link('詳細', ['action' => 'detail', $work->id]) ?> > 編集
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<style>
    .input.textarea {
        margin-top: 16px;
    }
</style>
<?php $this->end() ?>

<p class="content_title">実績の編集<?= $this->Html->link('< 戻る', ['action' => 'detail', $work->id]) ?></p>

<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'edit', $work->id],
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'required' => false]) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要', 'required' => false]) ?>
<?= $this->Form->submit('設定を保存', ['class' => 'button', 'style' => 'margin-top: 16px;']) ?>
<?= $this->Form->end() ?>