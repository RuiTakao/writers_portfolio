<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー作成 - Smart Profile</title>
    <?= $this->Html->css([
        'reset',
        'base',
        'admin/create_users'
    ]) ?>
    <?php /** css 各ページ */ ?>
    <?= $this->fetch('css') ?>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Smart Profile ユーザー作成</h1>
    </header>
    <main class="main">
        <div class="container">
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <?= $this->element('flash/message') ?>
    <?php /** js 各ページ */ ?>
    <?= $this->fetch('script') ?>
</body>

</html>