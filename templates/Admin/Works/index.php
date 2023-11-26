<?php

use App\Model\Table\WorksTable;
?>
<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
実績
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<?php $this->end() ?>

<div class="flex" style="gap: 16px">
    <?= $this->Html->link('実績の追加', ['action' => 'add'], ['class' => 'button']) ?>
    <?= $this->Html->link('並び順変更', ['action' => 'order'], ['class' => 'button']) ?>
</div>

<ul style="margin-top: 32px;">
    <?php foreach ($works as $item) : ?>
        
        <?php
        /**
         * 画像パス
         */
        // 各々のユーザーによって決まるパス
        $self_path = $auth->username . '/' . $item->id . '/' . $item->image_path;
        // ルートからのパス
        $root_image_path = WorksTable::ROOT_WORKS_IMAGE_PATH . $self_path;
        // webrootからのパス
        $image_path = WorksTable::WORKS_IMAGE_PATH . $self_path;
        ?>

        <li class="card">
            <div class="flex">
                <div class="flex-left" style="width: 70%;">
                    <div class="flex">
                        <p style="font-weight: 600;"><?= h($item->title) ?><?= $this->Html->link('詳細', ['action' => 'detail', $item->id], ['class' => 'button list-button']) ?></p>
                    </div>
                    <p style="margin-top: 32px; line-height:1.8em;"><?= nl2br(h($item->overview)) ?></p>
                </div>

                <?php if (!is_null($item->image_path) && $item->image_path != '' && file_exists($root_image_path)) : ?>
                    <div class="flex-right" style="width: 30%;">
                        <div class="">
                            <?= $this->Html->image($image_path, ['style' => 'width: 80%; height:auto; display: block;']) ?>
                        </div>
                        <p style="margin-top: 8px;">
                            <?= $this->html->link($item->url, ['url' => '#']) ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        </li>
    <?php endforeach; ?>
</ul>