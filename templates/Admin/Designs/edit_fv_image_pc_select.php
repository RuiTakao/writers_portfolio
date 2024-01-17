<?php

use App\Model\Table\DesignsTable;
use Cake\Core\Configure;
?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイトデザイン設定', ['action' => 'index']) ?> > Topレイアウト編集
<?php $this->end() ?>
<?php /* css */ ?>
<?php $this->start('css') ?>
<style>
    input {
        width: auto;
    }

    .card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 95%;
    }

    .card:not(:first-child) {
        margin-top: 0;
    }

    .layout_item {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .left {
        width: 5%;
    }

    .default_image {
        display: block;
        width: 300px;
    }
</style>
<?php $this->end() ?>

<p class="fz22 fwb mt16">以下から画像を選択してください</p>
<?= $this->Form->create($design, ['url' => ['controller' => 'Designs', 'action' => 'editFvImagePcSelect'], 'onSubmit' => 'return checkEdit()']) ?>
<ul class="layoyt_list mt32">
    <?php for ($i = 1; $i <= DesignsTable::FV_DEFAULT_IMAGE_PATTERN_COUNT; $i++) : ?>
        <label for="default_image_<?= $i ?>">
            <li class="layout_item<?= $i > 0 ? ' mt32' : '' ?>">
                <div class="left">
                    <input type="radio" name="default_image" value="<?= $i ?>" id="default_image_<?= $i ?>">
                </div>
                <?= $this->Html->image(DesignsTable::FV_DEFAULT_IMAGE_PATH . 'default' . $i . '.jpg', ['class' => 'default_image']) ?>
            </li>
        </label>
    <?php endfor; ?>
</ul>
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>

<?php /* current data */ ?>
<?php $path = DesignsTable::FV_IMAGE_PC_PATH . $auth->username . '/' . $design->fv_image_path; ?>
<?php if (!is_null($path)) : ?>
    <p class="current_content_title mt64">現在の画像</p>
    <?= $this->Html->image($path, ['class' => 'rectangle_image']) ?>
<?php endif; ?>