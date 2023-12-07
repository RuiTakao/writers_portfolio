<?php

use Cake\I18n\FrozenTime;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
経歴の設定
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/histories') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<script>
    const to_now = document.getElementById('to_now');
    const end = document.getElementById('end');

    <?php if ($historie->to_now == "1") : ?>
        end.value = '';
        end.style = 'background-color: #ccc;';
        end.setAttribute('disabled', 'disabled');
    <?php endif; ?>

    to_now.addEventListener('change', () => {
        if (to_now.checked) {
            end.value = '';
            end.style = 'background-color: #ccc;';
            end.setAttribute('disabled', 'disabled');
        } else {
            end.removeAttribute('disabled');
            end.removeAttribute('style');
        }
    })
</script>
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
            <div class="flex">
                <div class="flex align-center history_span_input" style="margin-right: 16px;">
                    <?= $this->Form->control('start', ['type' => 'month', 'label' => false, 'required' => false, 'error' => false]) ?>～<?= $this->Form->control('end', ['type' => 'month', 'label' => false, 'required' => false, 'error' => false]) ?>
                </div>
                <div class="flex" style="align-items: center;">
                    <?= $this->Form->checkbox('to_now', ['id' => 'to_now', 'style' => 'margin-right: 4px;', 'error' => false]) ?><label for="to_now" style="font-size: 14px;">現在まで</label>
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
                        $start = $start->i18nFormat('yyyy/M');

                        if ($value->to_now == "1") {
                            $end = "現在まで";
                        } else {
                            $end = new FrozenTime($value->end);
                            $end = $end->i18nFormat('yyyy/M');
                        }

                        ?>
                        <p style="font-size: 14px;"><?= h($start) ?> ～ <?= h($end) ?></p>
                        <p style="margin-top: 4px; font-weight: 600; font-size: 18px;"><?= h($value->title) ?></p>
                    </div>
                    <div class="flex_right">
                        <p style="font-size: 14px; padding-top:8px;"><?= nl2br(h($value->overview)) ?></p>
                    </div>
                </div>
            </td>
            <td style="width: 136px;">
                <div class="flex justify-between">
                    <?= $this->Html->link('編集', ['action' => 'edit',  $value->id], ['class' => 'button table_button']) ?>
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