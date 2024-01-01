<?php

use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
経歴の設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/histories') ?>
<?php $this->end() ?>

<?php /* js */ ?>
<?php $this->start('script') ?>
<?= $this->Html->script('histories') ?>
<?php $this->end() ?>

<?= $this->Form->create($historie, ['url' => ['controller' => 'Histories', 'action' => 'index'], 'onSubmit' => 'return checkAdd()']) ?>
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
                <div>～</div>
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
    <tr>
        <th>追加位置</th>
        <td colspan="3">
            <?= $this->Form->select('add_place', $add_place, ['label' => false]) ?>
        </td>
    </tr>
</table>
<?= $this->Form->submit(Configure::read('button.add'), ['class' => 'button default mt16']) ?>
<?= $this->Form->end() ?>

<table class="list_table mt64">
    <?php foreach ($histories as $key => $value) : ?>
        <?php
        $start = new FrozenTime($value->start);
        $start = $start->i18nFormat('yyyy/M');

        if ($value->to_now == "1") {
            $end = "現在まで";
        } else {
            $end = new FrozenTime($value->end);
            $end = $end->i18nFormat('yyyy/M');
        }

        $confirm_text = $key + 1 . '行目の経歴を削除しますか？';
        ?>
        <tr>
            <th>経歴<?= $key + 1 ?></th>
            <td class="content_column">
                <div class="flex">
                    <div class="left">
                        <p class="mt14"><?= h($start) ?> ～ <?= h($end) ?></p>
                        <p class="mt4 fwb fz18"><?= h($value->title) ?></p>
                    </div>
                    <div class="right fz14">
                        <p><?= nl2br(h($value->overview)) ?></p>
                    </div>
                </div>
            </td>
            <td class="button_column">
                <div class="flex justify-between">
                    <?= $this->Html->link(Configure::read('button.edit'), ['action' => 'edit',  $value->id], ['class' => 'button table_button']) ?>
                    <?= $this->Form->postLink(Configure::read('button.delete'), ['controller' => 'Histories', 'action' => 'delete', $value->id], ['class' => 'button table_button delete', 'confirm' => $confirm_text]) ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>