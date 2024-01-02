<?php

use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('お問い合わせ', ['action' => 'index']) ?> &gt; <?= $this->Html->link('お問い合わせ項目', ['action' => 'list']) ?> &gt; <?= $this->Html->link('編集', ['action' => 'edit']) ?> &gt; URLの編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<style>
    .input {
        margin-bottom: 16px;
    }
</style>
<?php $this->end() ?>

<?php /* js */ ?>
<?php $this->start('script') ?>
<?= $this->Html->script(['works']) ?>
<?php $this->end() ?>

<?= $this->Form->create($contact, [
    'url' => ['controller' => 'Contacts', 'action' => 'editLink', $contact->id],
    'type' => 'file',
    'onSubmit' => 'return checkAdd()'
]) ?>
<?= $this->Form->control('url_path', ['label' => 'URL', 'required' => false]) ?>
<?= $this->Form->control('url_name', ['label' => 'URL名 (※表示するURLリンクを変更したい場合はこちらに入力して下さい。)', 'required' => false]) ?>
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default ']) ?>
    <?= $this->Html->link('戻る', ['action' => 'edit', $contact->id], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>