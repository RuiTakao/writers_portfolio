<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('経歴の設定', ['action' => 'index']) ?> > 編集
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

<?= $this->Form->create($historie, ['url' => ['controller' => 'Histories', 'action' => 'edit', $historie->id], 'onSubmit' => 'return checkAdd()']) ?>
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
</table>
<?= $this->Form->submit('経歴を編集', ['class' => 'button']) ?>
<?= $this->Form->end() ?>