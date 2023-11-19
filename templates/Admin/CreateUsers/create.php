<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー作成 - Writers Portfolio</title>
    <?= $this->Html->css('reset') ?>
    <?= $this->Html->css('admin/create_users') ?>
</head>

<body>
    <header class="header">
        <h1 class="header_title">Writers Portfolio ユーザー作成</h1>
    </header>
    <main class="main">
        <div class="container">
            <?php /** form */ ?>
            <?= $this->Form->create($user) ?>
            <?php /** username */ ?>
            <label for="username">ユーザー名入力<span>※半角英数字のみ</span></label>
            <?= $this->Form->control('username', [
                'label' => false,
                'placeholder' => '（例　taro'
            ]) ?>
            <label for="username">パスワード入力<span>※半角英数字のみ</span></label>
            <?php /** password */ ?>
            <?= $this->Form->control('password', [
                'type' => 'password',
                'label' => false
            ]) ?>
            <label for="username">パスワード再入力</label>
            <?= $this->Form->control('re_password', [
                'type' => 'password',
                'label' => false
            ]) ?>

            <p class="info_text">ユーザー名はポートフォリオのパスとして扱われます。<br />（例　https://writers-portfolio.jp/taro</p>

            <p class="caution_text">※一度確定したら変更できません</p>

            <?php /** submit */ ?>
            <?= $this->Form->submit('確認'); ?>
            <?= $this->Form->end() ?>
        </div>
    </main>
</body>

</html>