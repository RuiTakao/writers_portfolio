<?php

/** ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('その他の設定', ['action' => 'index']) ?> > 並び順変更
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/others') ?>
<?= $this->Html->css('order') ?>
<?php $this->end() ?>

<?php /** js */ ?>
<?php $this->start('script') ?>
<?= $this->Html->script('order') ?>
<?php $this->end() ?>

<?= $this->Form->create($others,['url' => ['controller' => 'Others', 'action' => 'order']]) ?>
<div class="flex" style="width: 100%;">
    <div style="width: 70%; padding-right: 16px; border-right: 1px solid #333;">
        <ul id="productOrderList">
            <?php foreach ($others as $key => $item) : ?>
                <li class="product_order_item">
                    <div class="js-productOrder" draggable="true">
                        <div class="card">
                            <p style="font-weight: 600;"><?= h($item->title) ?></p>
                            <?= $this->Form->hidden('id[]', ['value' => $item->id]) ?>
                            <?= $this->Form->hidden('order[]') ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            <li class="product_order_item js-dropZone"></li>
        </ul>
    </div>
    <?= $this->Form->submit('設定を保存', ['class' => 'button', 'style' => 'margin-left: 16px; height: 32px;']) ?>
</div>