<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
お問い合わせ
<?php $this->end() ?>

<div class="button-container default">
    <?= $this->Html->link('お問い合わせ項目', ['action' => 'list'], ['class' => 'button default']) ?>
    <?= $this->Html->link('メールフォーム', ['controller' => 'MailForms', 'action' => 'order'], ['class' => 'button default']) ?>
</div>

<ul class="mt32">


        <li class="card">
            <div class="head">
                <p class="fwb">ああああ</p>
                <div class="button-container item">
                </div>
            </div>
            <div class="content flex">
                <div style="width:65%;">

                        <p style="font-size:14px;">お問い合わせリンク：
                            
                        </p>

                    <p style="margin-top: 16px; line-height:1.8em;">ああああああ</p>
                </div>

                    <div style="width: 30%;">
                        <div style="margin-top: 16px;">
                        </div>
                    </div>

            </div>
        </li>
</ul>