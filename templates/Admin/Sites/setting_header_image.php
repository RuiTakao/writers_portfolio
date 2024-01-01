<?php

use App\Model\Table\ProfilesTable;
use App\Model\Table\SitesTable;
use Cake\Core\Configure;

if (is_null($site->header_image_path) || !file_exists(SitesTable::ROOT_HEADER_IMAGE_PATH)) {
    $header_image = SitesTable::BLANK_HEADER_IMAGE_PATH;
} else {
    $header_image = SitesTable::HEADER_IMAGE_PATH . $auth->username . '/' . $site->header_image_path;
}

if (is_null($profile->image_path) || !file_exists(ProfilesTable::ROOT_PROFILE_IMAGE_PATH)) {
    $profile_image = ProfilesTable::BLANK_PROFILE_IMAGE_PATH;
} else {
    $profile_image = ProfilesTable::PROFILE_IMAGE_PATH .  $auth->username . '/' . $profile->image_path;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->css('portfolios') ?>
    <style>
        .fv_bg {
            background-image: url('<?= $this->Url->image($header_image) ?>');
        }

        .input label {
            display: block;
            font-size: 18px;
            font-weight: 600;
        }

        .input input {
            display: block;
            width: 100%;
            margin-top: 8px;
        }

        .input:not(:first-child) {
            margin-top: 32px;
        }

        .container {
            margin-top: 80px;
        }

        .button {
            background: #0284BB;
            color: #fff;
            cursor: pointer;
            border: none;
            outline: none;
            text-decoration: none;
            text-align: center;
            display: block;
            width: 156px;
            margin-top: 64px;
            padding: 4px;
            font-weight: 600;
        }
    </style>
    <title>管理画面 - Writers Portfolio</title>
</head>

<body>
    <div class="fv">
        <div class="fv_bg_cover" id="bg_cover"></div>
        <div class="fv_bg" id="bg"></div>
        <div class="fv_container">
            <div class="fv_user_icon"><?= $this->Html->image($profile_image) ?></div>
            <div class="fv_user_content">
                <p class="fv_user_name"><?= h($profile->view_name) ?></p>
                <p class="fv_user_works"><?= h($profile->works) ?></p>
            </div>
        </div>
    </div>
    <div class="container">
        <?= $this->Form->create($site,  [
            'url' => ['controller' => 'Sites', 'action' => 'settingHeaderImage'],
            'onSubmit' => 'return checkEdit()'
        ]) ?>
        <?= $this->Form->control('header_image_positionX', [
            'type' => 'range',
            'id' => 'positionX',
            'label' => '画像の左右の調整',
            'min' => 0,
            'max' => 100,
            'value' => h($site->header_image_positionX)
        ]) ?>
        <?= $this->Form->control('header_image_positionY', [
            'type' => 'range',
            'id' => 'positionY',
            'label' => '画像の上下の調整',
            'min' => 0,
            'max' => 100,
            'value' => h($site->header_image_positionY)
        ]) ?>
        <?= $this->Form->control('header_image_opacity', [
            'type' => 'range',
            'id' => 'opacity',
            'label' => '背景画像透過度',
            'min' => 0,
            'max' => 100,
            'value' => h($site->header_image_opacity)
        ]) ?>
        <?= $this->Form->submit(Configure::read('button.save'),  ['class' => 'button']) ?>
        <?= $this->Form->end() ?>

    </div>

    <script>
        const bg = document.getElementById('bg');
        const bg_cover = document.getElementById('bg_cover');

        const opacity = document.getElementById('opacity');
        const positionX = document.getElementById('positionX');
        const positionY = document.getElementById('positionY');

        bg.style.backgroundPositionX = "<?= $site->header_image_positionX . '%' ?>";
        bg.style.backgroundPositionY = "<?= $site->header_image_positionY . '%' ?>";
        bg_cover.style.opacity = "<?= $site->header_image_opacity . '%' ?>";

        opacity.addEventListener('input', () => {
            bg_cover.style.opacity = `${opacity.value}%`;
        });
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