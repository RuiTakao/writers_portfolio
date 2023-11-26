<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > <?= $this->Html->link('詳細', ['action' => 'detail', $work->id]) ?> > 編集
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<?php $this->end() ?>

<p class="content_title">実績の編集<?= $this->Html->link('< 戻る', ['action' => 'detail', $work->id]) ?></p>
<?= $this->Form->create($work, ['url' => ['controller' => 'Works', 'action' => 'edit', $work->id]]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'style' => 'margin-bottom: 16px']) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要']) ?>
<?= $this->Form->submit('設定を保存', ['class' => 'button']) ?>
<?= $this->Form->end() ?>