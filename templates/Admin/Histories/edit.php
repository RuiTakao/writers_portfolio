<?php

use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('経歴の設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/histories') ?>
<?php $this->end() ?>

<?php /* js */ ?>
<?php $this->start('script') ?>
<?= $this->Html->script('histories') ?>
<?php $this->end() ?>

<?= $this->Form->create($historie, ['url' => ['controller' => 'Histories', 'action' => 'edit', $historie->id], 'onSubmit' => 'return checkAdd()']) ?>
<table class="list_table">
    <tr>
        <th>タイトル</th>
        <td class="title_column">
            <?= $this->Form->control('title', ['label' => false, 'required' => false]) ?>
        </td>
        <th>概要</th>
        <td>
            <?= $this->Form->control('overview', ['type' => 'textarea', 'label' => false, 'required' => false]) ?>
        </td>
    </tr>
    <tr>
        <th>期間</th>
        <td colspan="3">
            <div class="period">
                <?= $this->Form->control('start', ['type' => 'month', 'label' => false, 'required' => false, 'error' => false]) ?>
                ～
                <?= $this->Form->control('end', ['type' => 'month', 'label' => false, 'required' => false, 'error' => false]) ?>
                <div class="checkbox">
                    <?= $this->Form->checkbox('to_now', ['id' => 'to_now', 'error' => false]) ?><label for="to_now">現在まで</label>
                </div>
            </div>
            <?php if ($this->Form->isFieldError('start')) : ?>
                <?= $this->Form->error('start') ?>
            <?php endif; ?>
        </td>
    </tr>
</table>
<div class="button-container default mt16">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>