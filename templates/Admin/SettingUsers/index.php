<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
ユーザー設定
<?php $this->end() ?>

<ul class="mt32 ml32">
    <li class="fz22" style="list-style:disc;"><?= $this->Form->postlink('パスワード再設定', ['controller' => 'SettingUsers', 'action' => 'changePassword']) ?></li>
</ul>