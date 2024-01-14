<?php

use App\Model\Table\DesignsTable;
use Cake\Core\Configure;
?>
<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
サイトデザイン設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('admin/sites') ?>
<?php $this->end() ?>

<table class="current_content_table mt32">
    <tr>
        <th>サイトTOPレイアウト<?= $this->Html->link('編集', ['action' => 'editFvDesign'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td>
            <?= $this->Html->image($top_layout, ['class' => 'rectangle_image']) ?>
        </td>
    </tr>
</table>

<table class="current_content_table mt32">
    <tr>
        <th>サイトTOP画像<?= $this->Html->link('編集', ['action' => 'editFvImagePc'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td>
            <?php $path = DesignsTable::FV_IMAGE_PC_PATH . $auth->username . '/' . $design->fv_image_path ?>
            <?= !empty($design->fv_image_path) ? $this->Html->image($path, ['class' => 'rectangle_image']) : "未設定" ?>
        </td>
    </tr>
</table>

<table class="current_content_table mt32">
    <tr>
        <th>サイトTOP画像（モバイル）<?= $this->Html->link('編集', ['action' => 'editFvImageSp'], ['class' => 'button']) ?></th>
    </tr>
    <tr>
        <td>
            <?php $path = DesignsTable::FV_IMAGE_SP_PATH . $auth->username . '/' . $design->fv_image_sp_path ?>
            <?= !empty($design->fv_image_sp_path) ? $this->Html->image($path, ['class' => 'rectangle_image']) : "未設定" ?>
        </td>
    </tr>
</table>