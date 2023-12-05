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
                            <p class="career_term"><?= h($start->i18nFormat('yyyy/MM')) ?> ~ <?= h($end->i18nFormat('yyyy/MM')) ?></p>
                            <p class="career_work"><?= h($history->title) ?></p>
                        </div>
                        <p class="career_detail"><?= nl2br(h($history->overview)) ?></p>
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

                        <h3 class="content_title"><?= h($work->title) ?></h3>

                        <?php /** 画像パス */ ?>
                        <?php $path = $username . '/' . $work->id . '/' . $work->image_path; ?>
                        <?php if (!is_null($work->image_path) && $work->image_path != '' && file_exists($root_works_image_path . $path)) : ?>
                            <div class="works_content_image"><?= $this->Html->image($works_image_path . $path) ?></div>
                        <?php endif; ?>

                        <?php if (!empty($work->url_path)) : ?>
                            <p class="works_content_link">関連リンク：<a href="<?= h($work->url_path) ?>"><?= !empty($work->url_name) ? h($work->url_name) : h($work->url_path) ?></a></p>
                        <?php endif; ?>

                        <p class="works_content_detail"><?= h($work->overview) ?></p>
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