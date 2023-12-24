<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex"/>
    <title>ログイン - Smart Profile</title>
    <?= $this->Html->css('reset') ?>
    <?= $this->Html->css('admin/login') ?>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Smart Profile</h1>
    </header>
    <main class="main">
        <div class="container">
            <?php /* form */ ?>
            <?= $this->Form->create() ?>
            <p class="login_title">ログイン画面</p>
            <div class="card">
                <?php /* username */ ?>
                <?= $this->Form->control('username', [
                    'label' => 'ユーザー名'
                ]) ?>
                <?php /* password */ ?>
                <?= $this->Form->control('password', [
                    'type' => 'password',
                    'label' => 'パスワード'
                ]) ?>
                <?php /* submit */ ?>
                <?= $this->Form->submit('ログイン'); ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </main>
    <?php if ($session->read('message')) : ?>
        <script>
            <?php /* 処理結果通知モーダル */ ?>
            window.onload = () => alert('<?= $session->read('message') ?>');
            <?php $session->delete('message') ?>
        </script>
    <?php endif; ?>
</body>

</html>