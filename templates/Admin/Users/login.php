<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - Writers Portfolio</title>
    <?= $this->Html->css('reset') ?>
    <?= $this->Html->css('admin/login') ?>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Writers Portfolio</h1>
    </header>
    <main class="main">
        <div class="container">
            <?php /** form */ ?>
            <?= $this->Form->create() ?>
            <p class="login_title">ログイン画面</p>
            <div class="card">
                <?php /** username */ ?>
                <?= $this->Form->control('username', [
                    'label' => 'ユーザー名'
                ]) ?>
                <?php /** password */ ?>
                <?= $this->Form->control('password', [
                    'type' => 'password',
                    'label' => 'パスワード'
                ]) ?>
                <?php /** submit */ ?>
                <?= $this->Form->submit('ログイン'); ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </main>
</body>

</html>