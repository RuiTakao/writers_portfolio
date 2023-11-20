<?php /** css */ ?>
<?php $this->start('css') ?>
<style>
    .container {
        width: 480px;
    }
</style>
<?php $this->end() ?>

<?php /** js */ ?>
<?php $this->start('script') ?>
<script>
    function checkCreateUser() {
        if (confirm(`ユーザーを作成します。変更出来なくなりますがよろしいですか？`)) {
            return true;
        } else {
            return false;
        }
    }
</script>
<?php $this->end() ?>

<p class="confirm_text">ユーザー名：<span><?= $user['username'] ?></span></p>
<p class="confirm_text">ポートフォリオアドレス：<span>https://writers-portfolio.jp/<?= $user['username'] ?></span></p>
<p class="confirm_text">パスワード：<span>・・・・・・</span></p>

<p class="caution_text">※一度確定したら変更できません</p>
<?= $this->Form->create(null, ['url' => ['controller' => 'CreateUsers', 'action' => 'confirm'], 'onclick' => 'return checkCreateUser()']) ?>
<?= $this->Form->submit('確定する'); ?>
<?= $this->Form->end() ?>
<?= $this->Html->link('修正する', ['action' => 'create'], ['class' => 'back_button']) ?>
</div>
</main>