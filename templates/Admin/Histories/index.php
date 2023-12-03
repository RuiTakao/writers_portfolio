<?php

/** ページタイトル */

use Cake\I18n\FrozenTime;

 ?>
<?php $this->start('page_title') ?>
経歴
<?php $this->end() ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/histories') ?>
<?php $this->end() ?>

<?= $this->Form->create($historie, ['url' => ['controller' => 'Histories', 'action' => 'index'], 'onSubmit' => 'return checkAdd()']) ?>
<table>
    <tr>
        <th>
            タイトル
        </th>
        <td style="width: 25%;">
            <?= $this->Form->control('title', ['label' => false, 'required' => false]) ?>
        </td>
        <th>
            概要
        </th>
        <td>
            <?= $this->Form->control('overview', ['type' => 'textarea', 'label' => false, 'required' => false]) ?>
        </td>
    </tr>
    <tr>
        <th>期間</th>
        <td colspan="3" class="histories_span">
            <div class="flex align-center history_span_input">
                <?= $this->Form->control('start', ['type' => 'month', 'label' => false, 'required' => false]) ?>~<?= $this->Form->control('end', ['type' => 'month', 'label' => false, 'required' => false]) ?>
            </div>
            <?= $this->Form->checkbox('to_now', ['id' => 'to_now']) ?><label for="to_now">現在まで</label>
        </td>
    </tr>
    <tr>
        <th>追加位置</th>
        <td colspan="3">
            <?= $this->Form->select('add_place', $add_place, ['label' => false]) ?>
        </td>
    </tr>
</table>
<?= $this->Form->submit('経歴を追加', ['class' => 'button']) ?>
<?= $this->Form->end() ?>

<table style="margin-top: 56px;">
    <?php foreach ($histories as $key => $value) : ?>
        <tr>
            <th>経歴<?= $key + 1 ?></th>
            <td>
                <div class="flex">

                    <div class="flex_left" style="width: 216px;">
                        <?php
                        $start = new FrozenTime($value->start);
                        $end = new FrozenTime($value->end);
                        ?>
                        <p style="font-size: 14px;"><?= h($start->i18nFormat('yyyy/MM')) ?> ~ <?= h($end->i18nFormat('yyyy/MM')) ?></p>
                        <p style="margin-top: 4px; font-weight: 600; font-size: 18px;"><?= h($value->title) ?></p>
                    </div>
                    <div class="flex_right">
                        <p style="font-size: 14px; padding-top:8px;"><?= h(nl2br($value->overview)) ?></p>
                    </div>
                </div>
            </td>
            <td style="width: 136px;">
                <div class="flex justify-between">
                    <a href="" class="button table_button">編集</a>
                    <?php $confirm_text = $key + 1 . '行目の経歴を削除しますか？' ?>
                    <?= $this->Form->postLink(
                        '削除',
                        ['controller' => 'Histories', 'action' => 'delete', $value->id],
                        ['class' => 'button table_button delete', 'confirm' => $confirm_text]
                    ) ?>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
