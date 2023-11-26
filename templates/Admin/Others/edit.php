<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('その他の設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/others') ?>
<?php $this->end() ?>

<div class="flex justify-between align-center">
    <p class="content_title">その他の編集<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
    <?= $this->Form->postLink('削除', ['controller' => 'Others', 'action' => 'delete', $other->id], ['class' => 'button delete']) ?>
</div>

<?= $this->Form->create($other, ['url' => ['controller' => 'Others', 'action' => 'edit', $other->id]]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'style' => 'margin-bottom: 16px']) ?>
<p>項目</p>
<div class="other_content_list">
    <?php for ($i = 1; $i <= 10; $i++) : ?>
        <?= $this->Form->control('content' . $i, ['label' => false]) ?>
    <?php endfor; ?>
</div>
<?= $this->Form->submit('設定を保存', ['class' => 'button', 'style' => 'margin-top: 16px;']) ?>
<?= $this->Form->end() ?>