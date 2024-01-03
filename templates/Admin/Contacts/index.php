<?php

use App\Model\Table\ContactsTable;
use Cake\Core\Configure;

?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
お問い合わせの設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<style>
    .page_content {
        width: 90%;
    }

    .card {
        min-height: 240px;
    }

    .card img {
        max-width: 100%;
        display: block;
        max-height: 192px;
    }
</style>
<?php $this->end() ?>

<div class="button-container default">
    <?= $this->Html->link('お問い合わせ項目の設定', ['action' => 'list'], ['class' => 'button default']) ?>
    <?php if (false) : ?>
        <?= $this->Html->link('メールフォーム', ['controller' => 'MailForms', 'action' => 'order'], ['class' => 'button default']) ?>
    <?php endif; ?>
</div>

<ul class="mt32">
    <?php foreach ($contacts as $item) : ?>

        <?php
        /**
         * 画像パス
         */
        // 各々のユーザーによって決まるパス
        $self_path = $auth->username . '/' . $item->id . '/' . $item->image_path;
        // ルートからのパス
        $root_image_path = ContactsTable::ROOT_WORKS_IMAGE_PATH . $self_path;
        // webrootからのパス
        $image_path = ContactsTable::WORKS_IMAGE_PATH . $self_path;

        /**
         * 画像有り無し判定
         */
        if (!is_null($item->image_path) && $item->image_path != '' && file_exists($root_image_path)) {
            $is_image = true;
        } else {
            $is_image = false;
        }

        /**
         * 削除メッセージ
         */
        $delete_message = $item->title . 'を削除しますか？';
        ?>

        <li class="card">
            <div class="head">
                <p class="fwb"><?= h($item->title) ?></p>
            </div>
            <div class="content flex">
                <div style="width: <?= $is_image ? '65' : '100' ?>%;">

                    <?php if (!empty($item->url_path)) : ?>
                        <p style="font-size:14px;">関連URL：
                            <?php if (!empty($item->url_name)) : ?>
                                <a href="<?= h($item->url_path) ?>"><?= h($item->url_name) ?></a>（<?= h($item->url_path) ?>）
                            <?php else : ?>
                                <a href="<?= h($item->url_path) ?>"><?= h($item->url_path) ?></a>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <p style="margin-top: 16px; line-height:1.8em;"><?= !empty($item->overview) ? nl2br(h($item->overview)) : '' ?></p>
                </div>

                <?php if ($is_image) : ?>
                    <div style="width: 30%;">
                        <div style="margin-top: 16px;">
                            <?= $this->Html->image($image_path) ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </li>
    <?php endforeach; ?>
</ul>