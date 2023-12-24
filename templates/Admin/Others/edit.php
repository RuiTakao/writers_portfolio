<?php

use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('その他の設定', ['action' => 'index']) ?> > <?= empty($other->id) ? '追加' : '編集' ?>
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/others') ?>
<?php $this->end() ?>

<?= $this->Form->create($other, [
    'url' => ['controller' => 'Others', 'action' => 'edit', $other->id],
    'onSubmit' => 'return checkEdit()'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'required' => false]) ?>
<p style="margin-top: 16px">項目</p>
<div class="other_content_list">
    <?php for ($i = 1; $i <= 10; $i++) : ?>
        <?= $this->Form->control('content' . $i, ['label' => false, 'required' => false]) ?>
    <?php endfor; ?>
</div>

<div class="button-container default mt16">
    <?= $this->Form->submit(empty($other->id) ? Configure::read('button.add') : Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>