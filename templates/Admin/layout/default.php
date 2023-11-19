<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面 - Writers Portfolio</title>
    <?= $this->Html->css([
        'reset',
        'base',
        'admin/layout'
    ]) ?>
    <?php /** css 各ページ */ ?>
    <?= $this->fetch('css') ?>
</head>

<body>
    <header class="header">
        <div class="header_container">
            <h1 class="header_title">Writers Portfolio 管理画面</h1>
            <li class="header_nav_item"><?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout'], ['class' => 'logout', 'onClick' => 'return logout();']) ?></li>
        </div>
    </header>
    <aside class="aside">
        <nav class="aside_navi">
            <ul class="aside_navi_list">
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
    <?php /** js 各ページ */ ?>
    <?= $this->fetch('script') ?>
</body>

</html>