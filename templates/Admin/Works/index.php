<?php

use App\Model\Table\WorksTable;
?>
<?php /** ページタイトル */ ?>
<?php $this->start('page_title') ?>
実績の設定
<?php $this->end() ?>

<?php /** css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/works') ?>
<?php $this->end() ?>

<div class="flex" style="gap: 16px">
    <?= $this->Html->link('実績の追加', ['action' => 'edit'], ['class' => 'button']) ?>
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

        /**
         * 削除メッセージ
         */
        $delete_message = $item->title . 'を削除しますか？';
        ?>

        <li class="card">
            <div class="flex" style="border-bottom: 1px solid #333; padding-bottom: 8px;">
                <p style="font-weight: 600; "><?= h($item->title) ?></p>
                <div class="flex" style="gap: 8px; margin-left: 16px">
                    <?= $this->Html->link('編集', ['action' => 'edit', $item->id], ['class' => 'button list-button', 'style' => 'width: auto; margin: 0;']) ?>
                    <?= $this->Form->postLink('削除', ['controller' => 'Works', 'action' => 'delete', $item->id], ['class' => 'button list-button delete', 'confirm' => $delete_message, 'style' => 'width: auto; margin: 0;']) ?>
                </div>
            </div>
            <div class="flex">
                <div class="flex-left" style="width: 70%;">

                    <?php if (!empty($item->url_path)) : ?>
                        <p style="margin-top: 16px; font-size:14px;">関連リンク：
                            <?php if (!empty($item->url_name)) : ?>
                                <a href="<?= h($item->url_path) ?>"><?= h($item->url_name) ?></a>（<?= h($item->url_path) ?>）
                            <?php else : ?>
                                <a href="<?= h($item->url_path) ?>"><?= h($item->url_path) ?></a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <p style="margin-top: 16px; line-height:1.8em;"><?= nl2br(h($item->overview)) ?></p>
                </div>

                <?php if (!is_null($item->image_path) && $item->image_path != '' && file_exists($root_image_path)) : ?>
                    <div class="flex-right" style="width: 30%;">
                        <div style="margin-top: 16px;">
                            <?= $this->Html->image($image_path, ['style' => 'width: 80%; height: auto; max-height:192px; display: block;']) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </li>
    <?php endforeach; ?>
</ul>