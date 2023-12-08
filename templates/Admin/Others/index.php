<?php

use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
その他の設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/others') ?>
<?php $this->end() ?>

<div class="flex" style="gap: 16px">
    <?= $this->Html->link('その他の追加', ['action' => 'edit'], ['class' => 'button']) ?>
    <?= $this->Html->link('並び順変更', ['action' => 'order'], ['class' => 'button']) ?>
</div>

<ul style="margin-top: 32px;">
    <?php foreach ($others as $item) : ?>

        <?php
        /**
         * 削除メッセージ
         */
        $delete_message = $item->title . 'を削除しますか？';
        ?>

        <li class="card">
            <div class="other_title flex" style="padding-bottom: 8px;">
                <?= h($item->title) ?>
                <div class="flex" style="gap: 8px; margin-left: 16px">
                    <?= $this->Html->link(Configure::read('button.edit'), ['action' => 'edit', $item->id], ['class' => 'button list-button', 'style' => 'width: auto; margin: 0;']) ?>
                    <?= $this->Form->postLink(
                        Configure::read('button.delete'),
                        ['controller' => 'Others', 'action' => 'delete', $item->id],
                        ['class' => 'button list-button delete', 'confirm' => $delete_message, 'style' => 'width: auto; margin: 0;']
                    ) ?>
                </div>
            </div>
            <ul style="margin-top: 8px; padding-left: 8px;">
                <?php for ($i = 1; $i <= 10; $i++) : ?>
                    <?php if (!is_null($item['content' . $i]) && $item['content' . $i] != '') : ?>
                        <li style="list-style: inside;"><?= $item['content' . $i] ?></li>
                    <?php endif; ?>
                <?php endfor; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>