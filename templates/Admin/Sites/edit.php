<?php

use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイト設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<style>
    input[type=radio] {
        width: 4%;
    }
</style>
<?php $this->end() ?>

<?= $this->Form->create($site, ['url' => ['controller' => 'Sites', 'action' => 'edit'], 'onSubmit' => 'return checkEdit()']) ?>
<table class="list_table">
    <tr>
        <th colspan="3" class="table_head">サイトタイトル・サイトの説明</th>
    </tr>
    <tr>
        <th class="column_title">サイトタイトル</th>
        <td><?= $this->Form->control('site_title', ['label' => false, 'value' => $site->site_title]) ?></td>
    </tr>
    <tr>
        <th class="column_title">ディスクリプション</th>
        <td><?= $this->Form->control('site_description', ['type' => 'textarea', 'label' => false, 'value' => $site->site_description]) ?></td>
    </tr>
    <tr>
        <th colspan="3" class="table_head">各項目のタイトルの表示テキスト</th>
    </tr>
    <tr>
        <th class="column_title">経歴</th>
        <td><?= $this->Form->control('histories_title', ['label' => false, 'value' => $site->histories_title]) ?></td>
    </tr>
    <tr>
        <th class="column_title">実績</th>
        <td><?= $this->Form->control('works_title', ['label' => false, 'value' => $site->works_title]) ?></td>
    </tr>
    <tr>
        <th class="column_title">その他</th>
        <td><?= $this->Form->control('others_title', ['label' => false, 'value' => $site->others_title]) ?></td>
    </tr>
    <tr>
        <th colspan="3" class="table_head">各項目の表示・非表示の設定</th>
    </tr>
    <tr>
        <th class="column_title">経歴</th>
        <td><?= $this->Form->radio('histories_flg', [
                ['value' => 1, 'text' => '表示'],
                ['value' => 0, 'text' => '非表示']
        ],
        ['value' => $site->histories_flg]) ?></td>
    </tr>
    <tr>
        <th class="column_title">実績</th>
        <td><?= $this->Form->radio('works_flg', [
            ['value' => 1, 'text' => '表示'],
            ['value' => 0, 'text' => '非表示']
        ],
        ['value' => $site->works_flg]) ?></td>
    </tr>
    <tr>
        <th class="column_title">その他</th>
        <td><?= $this->Form->radio('others_flg', [
            ['value' => 1, 'text' => '表示'],
            ['value' => 0, 'text' => '非表示']
        ],
        ['value' => $site->others_flg]) ?></td>
    </tr>
</table>
<div class="button-container default mt16">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>