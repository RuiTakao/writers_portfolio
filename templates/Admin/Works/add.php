<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
実績 追加
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<?php $this->end() ?>

<?= $this->Form->create($work, ['url' => ['controller' => 'Works', 'action' => 'add']]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'style' => 'margin-bottom: 16px']) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要']) ?>
<?= $this->Form->submit('設定を保存', ['class' => 'button']) ?>
<?= $this->Form->end() ?>