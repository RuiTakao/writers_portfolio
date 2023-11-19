<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイト設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?php $this->end() ?>

<p class="content_title">サイト設定編集<?= $this->Html->link('< 戻る', ['action' => 'index']) ?></p>
<?= $this->Form->create($site, [
    'url' => ['controller' => 'Sites', 'action' => 'edit'],
    'onSubmit' => 'return checkEdit()'
]) ?>
<table class="text_table">
    <tr>
        <th>サイトタイトル</th>
        <td><?= $this->Form->control('site_title',['label' => false, 'value' => $site->site_title]) ?></td>
    </tr>
    <tr>
        <th>ディスクリプション</th>
        <td><?= $this->Form->control('site_description',['type' => 'textarea', 'label' => false, 'value' => $site->site_description]) ?></td>
    </tr>
</table>
<?= $this->Form->submit('この内容で変更する',  ['class' => 'button']) ?>
<?= $this->Form->end() ?>