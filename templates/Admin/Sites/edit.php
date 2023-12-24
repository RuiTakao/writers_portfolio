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
<?php $this->end() ?>

<?= $this->Form->create($site, ['url' => ['controller' => 'Sites', 'action' => 'edit'], 'onSubmit' => 'return checkEdit()']) ?>
<table class="list_table">
    <tr>
        <th>サイトタイトル</th>
        <td><?= $this->Form->control('site_title', ['label' => false, 'value' => $site->site_title]) ?></td>
    </tr>
    <tr>
        <th>ディスクリプション</th>
        <td><?= $this->Form->control('site_description', ['type' => 'textarea', 'label' => false, 'value' => $site->site_description]) ?></td>
    </tr>
</table>
<div class="button-container default mt16">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>