<?php

use Cake\I18n\FrozenTime;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (!is_null($favicon)) : ?>
        <link rel="icon" href="<?= $this->Url->image($favicon) ?>">
    <?php endif; ?>
    <meta name="description" content="<?= h($site->site_description) ?>">
    <?= $this->Html->css(['all.min', 'portfolios']) ?>
    <?php if (!empty($header_image)) : ?>
        <style>
            .fv_bg_cover {
                background: #fff;
                opacity: <?= $site->header_image_opacity . '%' ?>;
            }

            .fv_bg {
                background-image: url('<?= $this->Url->image($header_image) ?>');
                background-position: <?= $site->header_image_positionX . '% ' . $site->header_image_positionY . '%' ?>;
            }
        </style>
        <?php if (!empty($header_image_sp)) : ?>
            <style>
                @media screen and (max-width: 640px) {
                    .fv_bg {
                        background-image: url('<?= $this->Url->image($header_image_sp) ?>');
                        background-position: <?= $site->header_image_sp_positionX . '% ' . $site->header_image_sp_positionY . '%' ?>;
                    }
                }
            </style>
        <?php endif; ?>
    <?php endif; ?>
    <title><?= h($site->site_title) ?></title>
</head>

<body>
    <main class="main">
        <?= $this->element('fv/pattern1') ?>

        <div class="pr">
            <div class="container">
                <div class="pr_content"><?= !empty($profile->profile_text) ? nl2br(h($profile->profile_text)) : '' ?></div>
            </div>
        </div>

        <?php /* 経歴 */ ?>
        <?php if ($site->histories_flg) : ?>
            <div class="career section">
                <div class="container">
                    <h2 class="section_title"><?= $site->histories_title ?></h2>
                    <ul class="career_list">

                        <?php foreach ($histories as $history) : ?>

                            <?php
                            $start = new FrozenTime($history->start);
                            $start = $start->i18nFormat('yyyy/M');

                            if ($history->to_now == "1") {
                                $end = "現在まで";
                            } else {
                                $end = new FrozenTime($history->end);
                                $end = $end->i18nFormat('yyyy/M');
                            }
                            ?>

                            <li class="career_item">
                                <div class="career_item_title">
                                    <p class="career_term"><?= h($start) ?> ～ <?= h($end) ?></p>
                                    <p class="career_work"><?= h($history->title) ?></p>
                                </div>
                                <p class="career_detail"><?= !empty($history->overview) ? nl2br(h($history->overview)) : '' ?></p>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php /* 実績 */ ?>
        <?php if ($site->works_flg) : ?>
            <div class="works section">
                <div class="container">
                    <h2 class="section_title"><?= $site->works_title ?></h2>
                    <ul class="works_content_list">

                        <?php foreach ($works as $work) : ?>

                            <li class="works_content_item">

                                <h3 class="content_title"><?= h($work->title) ?></h3>

                                <?php /* 画像パス */ ?>
                                <?php $path = $username . '/' . $work->id . '/' . $work->image_path; ?>
                                <?php if (!is_null($work->image_path) && $work->image_path != '' && file_exists($root_works_image_path . $path)) : ?>
                                    <div class="works_content_image"><?= $this->Html->image($works_image_path . $path) ?></div>
                                <?php endif; ?>

                                <?php if (!empty($work->url_path)) : ?>
                                    <p class="works_content_link">関連リンク：<a href="<?= h($work->url_path) ?>" target="_blank"><?= !empty($work->url_name) ? h($work->url_name) : h($work->url_path) ?></a></p>
                                <?php endif; ?>

                                <p class="works_content_detail"><?= !empty($work->overview) ? nl2br(h($work->overview)) : '' ?></p>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php /* その他 */ ?>
        <?php if ($site->others_flg) : ?>
            <div class="work_style section">
                <div class="container">
                    <h2 class="section_title"><?= $site->others_title ?></h2>
                    <div class="work_style_content">

                        <?php foreach ($others as $other) : ?>
                            <div class="work_style_content_item">
                                <h3 class="content_title"><?= h($other->title) ?></h3>
                                <ul class="work_style_content_list">
                                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                                        <?php if (!is_null($other['content' . $i]) && $other['content' . $i] != '') : ?>
                                            <li><?= h($other['content' . $i]) ?></li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php /* お問い合わせ */ ?>
        <?php if ($site->contacts_flg) : ?>
            <div class="contacts section">
                <div class="container">
                    <h2 class="section_title"><?= h($site->contacts_title) ?></h2>
                    <ul class="contacts_content_list">

                        <?php foreach ($contacts as $contact) : ?>

                            <li class="contacts_content_item">

                                <h3 class="content_title"><?= h($contact->title) ?></h3>

                                <?php /* 画像パス */ ?>
                                <?php $path = $username . '/' . $contact->id . '/' . $contact->image_path; ?>
                                <?php if (!is_null($contact->image_path) && $contact->image_path != '' && file_exists($root_contacts_image_path . $path)) : ?>
                                    <div class="contacts_content_image"><?= $this->Html->image($contacts_image_path . $path) ?></div>
                                <?php endif; ?>

                                <?php if (!empty($contact->url_path)) : ?>
                                    <p class="contacts_content_link">関連リンク：<a href="<?= h($contact->url_path) ?>"><?= !empty($contact->url_name) ? h($contact->url_name) : h($contact->url_path) ?></a></p>
                                <?php endif; ?>

                                <p class="contacts_content_detail"><?= !empty($contact->overview) ? nl2br(h($contact->overview)) : '' ?></p>
                            </li>
                        <?php endforeach; ?>

                        <?php if ($mailForms->view_mail_form) : ?>
                            <li class="contacts_content_item">
                                <h3 class="content_title">お問い合わせフォーム</h3>
                                <?= $this->Form->create(null, ['url' => ['controller' => 'Portfolios', 'action' => 'index', $username]]) ?>
                                <?= $this->Form->control('name', ['label' => '名前']) ?>
                                <?= $this->Form->control('email', ['label' => 'メールアドレス']) ?>
                                <?= $this->Form->control('content', ['type' => 'textarea', 'label' => 'お問い合わせ内容']) ?>
                                <div class="form_submit">
                                    <?= $this->Form->submit('送信') ?>
                                </div>
                                <?= $this->Form->end() ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer_copy">©Smart Profile inc</div>
        </div>
    </footer>
</body>

</html>