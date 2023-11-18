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
    </style>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Writers Portfolio ユーザー作成</h1>
    </header>
    <main class="main">
        <div class="container">

            <p class="confirm_text">ユーザー名：<span><?= $user['username'] ?></span></p>
            <p class="confirm_text">ポートフォリオアドレス：<span>https://writers-portfolio.jp/<?= $user['username'] ?></span></p>
            <p class="confirm_text">パスワード：<span>・・・・・・</span></p>

            <p class="caution_text">※一度確定したら変更できません</p>
            <?= $this->Form->create(null,['url' => ['controller' => 'CreateUsers', 'action' => 'confirm'], 'onclick' => 'return checkCreateUser()']) ?>
            <?= $this->Form->submit('確定する'); ?>
            <?= $this->Form->end() ?>
            <?= $this->Html->link('修正する', ['action' => 'create'],['class' => 'back_button']) ?>
        </div>
    </main>
    <script>
        function checkCreateUser() {
        if (confirm(`ユーザーを作成します。変更出来なくなりますがよろしいですか？`)) {
            return true;
        } else {
            return false;
        }
    }
    </script>
</body>

</html>