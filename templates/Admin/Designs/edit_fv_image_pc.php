<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('サイトデザイン設定', ['action' => 'index']) ?> > サイトTOP画像編集
<?php $this->end() ?>

<p class="mt32 fz22">以下の2つから設定方法を選んでください</p>
<ul class="mt32 ml32">
    <li class="fz22" style="list-style:disc;"><?= $this->Html->link('画像アップロード', ['action' => 'editFvImagePcUpload']) ?></li>
    <li class="fz22 mt32" style="list-style:disc;"><?= $this->Html->link('デフォルト画像を選択', ['action' => 'editFvImagePcSelect']) ?></li>
</ul>