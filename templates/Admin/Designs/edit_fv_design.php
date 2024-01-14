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
</style>
<?php $this->end() ?>

<p class="fz22 fwb mt16">こちらでTOPのレイアウトの変更ができます</p>
<?= $this->Form->create($design, ['url' => ['controller' => 'Designs', 'action' => 'editFvDesign'], 'onSubmit' => 'return checkEdit()']) ?>
<ul class="layoyt_list mt32">
    <?php foreach (DesignsTable::FV_DESIGN_PATTERN_LIST as $key => $item) : ?>
        <label for="fv_design_<?= $item ?>">
            <li class="layout_item<?= $key > 0 ? ' mt32' : '' ?>">
                <div class="left">
                    <input type="radio" name="fv_design" value="<?= $item ?>" id="fv_design_<?= $item ?>" <?= $design->fv_design == $item ? "checked" : ''; ?>>
                </div>
                <div class="card">
                    <p><?= nl2br(DesignsTable::FV_DESIGN_TEXT[$item]) ?></p>
                    <?= $this->Html->image(DesignsTable::FV_DESIGN_PATH . 'pattern' . $item . '.jpg', ['class' => 'rectangle_image']) ?>
                </div>
            </li>
        </label>
    <?php endforeach; ?>
</ul>
<div class="button-container default mt32">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>