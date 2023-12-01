<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
その他
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/others') ?>
<?php $this->end() ?>

<div class="flex" style="gap: 16px">
    <?= $this->Html->link('その他の追加', ['action' => 'add'], ['class' => 'button']) ?>
    <?= $this->Html->link('並び順変更', ['action' => 'order'], ['class' => 'button']) ?>
</div>

<ul style="margin-top: 32px;">
    <?php foreach ($others as $item) : ?>

        <li class="card">
            <p class="other_title" style="padding-bottom: 8px;">
                <?= h($item->title) ?>
                <?= $this->Html->link('編集', ['action' => 'edit', $item->id], ['class' => 'button list-button']) ?>
            </p>
            <ul style="margin-top: 8px;">
                <?php for ($i = 1; $i <= 10; $i++) : ?>
                    <?php if (!is_null($item['content' . $i]) && $item['content' . $i] != '') : ?>
                        <li style="list-style: inside;"><?= $item['content' . $i] ?></li>
                    <?php endif; ?>
                <?php endfor; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>