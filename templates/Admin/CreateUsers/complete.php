<?php

/** css */ ?>
<?php $this->start('css') ?>
<style>
    .container {
        width: 480px;
    }

    .title {
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 64px;
    }
</style>
<?php $this->end() ?>

<p class="title">ユーザーが作成されました</p>
<p class="confirm_text">ユーザー名：<span><?= $user->username ?></span></p>
<p class="confirm_text">ポートフォリオアドレス：<span><?= $_SERVER['SERVER_NAME'] . '/' . $user['username'] ?></span></p>
<p class="confirm_text">パスワード：<span>・・・・・・</span></p>

<p class="caution_text">再度ログインしてください</p>
<?= $this->Html->link('ログイン画面へ', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'button back', 'style' => 'margin-top: 4px;']) ?>