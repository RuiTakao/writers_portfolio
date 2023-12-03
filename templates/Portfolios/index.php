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
    <?= $this->Html->css('portfolios') ?>
    <title><?= h($site->site_title) ?></title>
</head>

<body>
    <div class="fv">
        <div class="fv_bg_cover"></div>
        <div class="fv_bg" style="background-image: url('<?= $this->Url->image($header_image) ?>');"></div>
        <div class="fv_container">
            <div class="fv_user_icon"><?= $this->Html->image($profile_image) ?></div>
            <div class="fv_user_content">
                <p class="fv_user_name"><?= h($profile->view_name) ?></p>
                <p class="fv_user_works"><?= h($profile->works) ?></p>
            </div>
        </div>
    </div>
    <div class="pr">
        <div class="container">
            <div class="pr_content"><?= nl2br(h($profile->profile_text)) ?></div>
        </div>
    </div>
    <div class="career section">
        <div class="container">
            <h2 class="section_title">経歴</h2>
            <ul class="career_list">

                <?php foreach ($histories as $history) : ?>

                    <?php
                    $start = new FrozenTime($history->start);
                    $end = new FrozenTime($history->end);
                    ?>

                    <li class="career_item">
                        <div class="career_item_title">
                            <p class="career_term"><?= $start->i18nFormat('yyyy/MM') ?> ~ <?= $end->i18nFormat('yyyy/MM') ?></p>
                            <p class="career_work"><?= $history->title ?></p>
                        </div>
                        <p class="career_detail"><?= $history->overview ?></p>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
    <div class="works section">
        <div class="container">
            <h2 class="section_title">実績</h2>
            <ul class="works_content_list">

                <?php foreach ($works as $work) : ?>
                    <li class="works_content_item">
                        <h3 class="content_title"><?= $work->title ?></h3>
                        <div class="works_content_image"></div>
                        <p class="works_content_link">出典：『<a href="#">レッツ朝活サロン</a>』</p>
                        <p class="works_content_detail"><?= $work->overview ?></p>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
    <div class="work_style section">
        <div class="container">
            <h2 class="section_title">仕事について</h2>
            <div class="work_style_content">

                <?php foreach ($others as $other) : ?>
                    <div class="work_style_content_item">
                        <h3 class="content_title"><?= $other->title ?></h3>
                        <ul class="work_style_content_list">
                            <li>医療法人の記事</li>
                            <li>副業についての記事</li>
                        </ul>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <div class="contact section">
        <div class="container">
            <h2 class="section_title">お問い合わせ</h2>
            <div class="_blank">
                未定
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer_copy">©Takao Folio inc</div>
        </div>
    </footer>
</body>

</html>
