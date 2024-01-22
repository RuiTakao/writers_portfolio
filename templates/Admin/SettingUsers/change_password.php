<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
パスワード再設定
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<style>
    .page_content {
        width: 50%;
    }

    .card:not(:first-child) {
        margin: 0;
    }
</style>
<?php $this->end() ?>

<div class="mt32">
    <?= $this->Form->create($user, ['url' => ['controller' => 'SettingUsers', 'action' => 'changePassword'], 'onSubmit' => 'return checkEditPassword()']) ?>
    <p class="fwb">現在のパスワード</p>
    <div class="card mt4">
        <div>
            <?= $this->Form->control('password', ['label' => '現在のパスワードを入力してください。', 'value' => '']) ?>
        </div>
    </div>
    <p class="fwb mt32">新パスワード</p>
    <div class="card mt4">
        <div>
            <?= $this->Form->control('new_password', ['type' => 'password', 'label' => '新パスワード', 'value' => '']) ?>
            <?php if (!is_null($error_message)) : ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
        </div>
        <div class="mt16">
            <?= $this->Form->control('re_password', ['type' => 'password', 'label' => 'パスワード再入力', 'value' => '']) ?>
        </div>
    </div>
    <div class="button-container default mt16">
        <?= $this->Form->submit('設定する', ['class' => 'button default']) ?>
        <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>