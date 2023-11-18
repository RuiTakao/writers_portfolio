<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー作成 - Writers Portfolio</title>
    <?= $this->Html->css('reset') ?>
    <?= $this->Html->css('admin/create_users') ?>
    <style>
        .container {
            width: 480px;
        }

        .title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 64px;
        }

        .back_button {
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Writers Portfolio ユーザー作成</h1>
    </header>
    <main class="main">
        <div class="container">

            <p class="title">ユーザーが作成されました</p>
            <p class="confirm_text">ユーザー名：<span><?= $user->username ?></span></p>
            <p class="confirm_text">ポートフォリオアドレス：<span>https://writers-portfolio.jp/<?= $user->username ?></span></p>
            <p class="confirm_text">パスワード：<span>・・・・・・</span></p>

            <p>再度ログインしてください</p>
            <?= $this->Html->link('ログイン画面へ', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'back_button']) ?>
        </div>
    </main>
</body>

</html>