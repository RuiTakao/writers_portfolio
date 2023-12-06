<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('その他の設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /** css */ ?>
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
<?= $this->Form->submit('設定を保存', ['class' => 'button', 'style' => 'margin-top: 16px;']) ?>
<?= $this->Form->end() ?>