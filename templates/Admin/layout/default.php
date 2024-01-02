<?php

use App\Model\Table\ProfilesTable;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex" />
    <title>管理画面 - Smart Profile</title>
    <?= $this->Html->css([
        'all.min',
        'reset',
        'base',
        'admin/layout'
    ]) ?>
    <?php /* css 各ページ */ ?>
    <?= $this->fetch('css') ?>
</head>

<body>
    <header class="header">
        <div class="header_container">
            <h1 class="header_title">Writers Portfolio 管理画面</h1>
            <ul class="header_nav_list">
                <li class="header_nav_item">
                    <p class="header_nav_text">ログインユーザー：<?= $auth->username ?></p>
                </li>
                <li class="header_nav_item">
                    <p class="header_nav_icon" id="page-setting">
                        <?php if (!empty($profile_icon)) : ?>
                            <?= $this->Html->image(ProfilesTable::PROFILE_IMAGE_PATH . $auth->username . '/' . $profile_icon) ?>
                        <?php else : ?>
                            <i class="fa-solid fa-circle-user"></i>
                        <?php endif; ?>
                    </p>
                </li>
                <li class="header_nav_item"><?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout'], ['class' => 'logout', 'onClick' => 'return logout();']) ?></li>
            </ul>
        </div>
    </header>
    <aside class="aside">
        <nav class="aside_navi">
            <ul class="aside_navi_list">
                <li class="aside_navi_item"><?= $this->Html->link('プロフィール設定', ['controller' => 'Profiles', 'action' => 'index']) ?></li>
                <li class="aside_navi_item"><?= $this->Html->link('サイト設定', ['controller' => 'Sites', 'action' => 'index']) ?></li>
                <li class="aside_navi_item"><?= $this->Html->link('経歴の設定', ['controller' => 'Histories', 'action' => 'index']) ?></li>
                <li class="aside_navi_item"><?= $this->Html->link('実績の設定', ['controller' => 'Works', 'action' => 'index']) ?></li>
                <li class="aside_navi_item"><?= $this->Html->link('その他の設定', ['controller' => 'Others', 'action' => 'index']) ?></li>
                <li class="aside_navi_item"><?= $this->Html->link('お問い合わせ', ['controller' => 'Contacts', 'action' => 'index']) ?></li>
            </ul>
        </nav>
    </aside>
    <main class="main">
        <div class="main_container">
            <p class="page_title"><?= $this->fetch('page_title') ?></p>
            <div class="page_content">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </main>
    <?= $this->element('flash/message') ?>
    <?php /* js 各ページ */ ?>
    <?= $this->fetch('script') ?>
</body>

</html>