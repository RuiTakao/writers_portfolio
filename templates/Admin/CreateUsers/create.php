<?php

/** form */ ?>
<?= $this->Form->create($user) ?>
<?php /** username */ ?>
<label for="username">ユーザー名入力<span class="req">必須</span><span>※半角英数字のみ</span></label>
<?= $this->Form->control('username', [
    'label' => false,
    'placeholder' => '（例　taro',
    'value' => $username,
    'required' => false
]) ?>
<p class="info_text">※ユーザー名はポートフォリオのパスとして扱われます。<br />（例　<?= $_SERVER['SERVER_NAME'] ?>/taro</p>
<label for="username">パスワード入力<span class="req">必須</span><span>※半角英数字のみ</span></label>
<?php /** password */ ?>
<?= $this->Form->control('password', [
    'type' => 'password',
    'label' => false,
    'required' => false
]) ?>
<label for="username">パスワード再入力<span class="req">必須</span></label>
<?= $this->Form->control('re_password', [
    'type' => 'password',
    'label' => false,
    'required' => false
]) ?>

<p class="caution_text">※一度確定したら変更できません</p>

<?php /** submit */ ?>
<?= $this->Form->submit('確認', ['class' => 'button']); ?>
<?= $this->Html->link('ユーザー作成を終了する', ['controller' => 'users', 'action' => 'logout'], ['class' => 'button back', 'onClick' => 'return createUserEnd();']) ?>
<?= $this->Form->end() ?>