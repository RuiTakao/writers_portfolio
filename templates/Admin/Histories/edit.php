<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
経歴
<?php $this->end() ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/histories') ?>
<?php $this->end() ?>

<?= $this->Form->create() ?>
<table>
    <tr>
        <th>
            タイトル
        </th>
        <td>
            <?= $this->Form->control('title', ['label' => false]) ?>
        </td>
        <th>
            説明
        </th>
        <td>
            <?= $this->Form->control('overview', ['type' => 'textarea', 'label' => false]) ?>
        </td>
    </tr>
    <tr>
        <th>期間</th>
        <td colspan="3">
        <?= $this->Form->control('start', ['label' => false]) ?> ～  <?= $this->Form->control('end', ['label' => false]) ?>
        </td>
    </tr>
    <tr>
        <th>追加位置</th>
        <td colspan="3">
        <?= $this->Form->select('add_place', ['label' => false]) ?>
        </td>
    </tr>
</table>
<?= $this->Form->submit('経歴を追加') ?>
<? $this->Form->end() ?>