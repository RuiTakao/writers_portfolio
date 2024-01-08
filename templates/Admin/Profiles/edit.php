<?php

use App\Model\Table\ProfilesTable;
use Cake\Core\Configure;
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('プロフィール設定', ['action' => 'index']) ?> > 編集
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css(['all.min', 'admin/profiles']) ?>
<?php $this->end() ?>

<?= $this->Form->create($profile, ['url' => ['controller' => 'Profiles', 'action' => 'edit'], 'onSubmit' => 'return checkEdit()']) ?>

<div class="flex">
    <?php if (!empty($profile->image_path)) : ?>
        <?php $path = ProfilesTable::PROFILE_IMAGE_PATH .  h($auth->username) . '/' . h($profile->image_path) ?>
        <?= $this->Html->image($path, ['class' => 'square_image']) ?>
    <?php else : ?>
        <div class="fv_user_icon"><i class="fa-solid fa-user"></i></div>
    <?php endif; ?>
    <ul class="flex_right">
        <li><?= $this->Form->control('view_name', ['label' => '名前（表示名）']) ?></li>
        <li class="mt16"><?= $this->Form->control('works', ['label' => '肩書（仕事名）']) ?></li>
    </ul>
</div>

<div class="mt32">
    <?= $this->Form->control('profile_text', ['type' => 'textarea', 'label' => 'プロフィール文', 'rows' => 9]) ?>
</div>

<div class="button-container default mt16">
    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
    <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
</div>
<?= $this->Form->end() ?>