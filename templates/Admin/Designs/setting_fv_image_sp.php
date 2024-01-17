<?php

use App\Model\Table\ProfilesTable;
use App\Model\Table\DesignsTable;
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->css(['all.min', 'portfolios', 'fv/pattern' . $design->fv_design, 'admin/setting_header']) ?>
    <?php $path = DesignsTable::FV_IMAGE_SP_PATH . $auth->username . '/' . $design->fv_image_sp_path ?>
    <style>
        .fv_bg {
            background-image: url('<?= $this->Url->image($path) ?>');
        }

        .fv {
            width: 420px;
            margin: 0 auto;
            height: 272px;
        }

        .fv_bg_cover {
            height: 240px;
        }

        .fv_bg {
            height: 240px;
        }

        .fv_container {
            gap: 32px;
            width: 90%;
        }

        .fv_user_icon {
            width: 144px;
            height: 144px;
        }

        .fv_user_icon img {
            width: 144px;
            height: 144px;
        }

        .fv_user_icon i {
            width: 144px;
            height: 144px;
            line-height: 144px;
            font-size: 96px;
        }

        .fv_user_name {
            font-size: 24px;
        }

        .fv_user_works {
            font-size: 16px;
            margin-top: 8px;
        }
    </style>
    <title>管理画面 - Writers Portfolio</title>
</head>

<body>
    <div class="fv">
        <div class="fv_bg_cover" id="bg_cover"></div>
        <div class="fv_bg" id="bg"></div>
        <div class="fv_container">
            <div class="fv_user_icon">
                <?php if (!empty($profile->image_path)) : ?>
                    <?php $path = ProfilesTable::PROFILE_IMAGE_PATH .  $auth->username . '/' . $profile->image_path ?>
                    <?= $this->Html->image($path) ?>
                <?php else : ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="fv_user_content">
                <p class="fv_user_name"><?= h($profile->view_name) ?></p>
                <p class="fv_user_works"><?= h($profile->works) ?></p>
            </div>
        </div>
    </div>
    <div class="container">
        <?= $this->Form->create($design,  [
            'url' => ['controller' => 'Designs', 'action' => 'settingFvImageSp'],
            'onSubmit' => 'return checkEdit()'
        ]) ?>
        <div class="flex">
            <div class="flex_left">
                <?= $this->Form->control('fv_image_sp_positionX', [
                    'type' => 'range',
                    'id' => 'positionX',
                    'label' => '画像の左右の調整',
                    'min' => 0,
                    'max' => 100,
                    'value' => h($design->fv_image_sp_positionX)
                ]) ?>
                <?= $this->Form->control('fv_image_sp_positionY', [
                    'type' => 'range',
                    'id' => 'positionY',
                    'label' => '画像の上下の調整',
                    'min' => 0,
                    'max' => 100,
                    'value' => h($design->fv_image_sp_positionY)
                ]) ?>
            </div>
            <div class="flex_right">
                <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button default']) ?>
                <?= $this->Html->link('戻る', ['action' => 'editFvImageSp'], ['class' => 'button default back mt16']) ?>
            </div>
        </div>
        <?= $this->Form->end() ?>

    </div>

    <script>
        const bg = document.getElementById('bg');
        const bg_cover = document.getElementById('bg_cover');

        const opacity = document.getElementById('opacity');
        const positionX = document.getElementById('positionX');
        const positionY = document.getElementById('positionY');

        bg.style.backgroundPositionX = "<?= $design->fv_image_sp_positionX . '%' ?>";
        bg.style.backgroundPositionY = "<?= $design->fv_image_sp_positionY . '%' ?>";
        bg_cover.style.opacity = "60%";

        positionX.addEventListener('input', () => {
            bg.style.backgroundPositionX = `${positionX.value}%`;
        });
        positionY.addEventListener('input', () => {
            bg.style.backgroundPositionY = `${positionY.value}%`;
        });

        <?php if ($session->read('message')) : ?>
            <?php /* 処理結果通知モーダル */ ?>
            window.onload = () => alert('<?= $session->read('message') ?>');
            <?php $session->delete('message') ?>
        <?php endif; ?>
    </script>
</body>

</html>