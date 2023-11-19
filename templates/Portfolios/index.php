<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $site->site_description ?>">
    <?= $this->Html->css('portfolios') ?>
    <title><?= $site->site_title ?></title>
</head>

<body>
    <div class="fv">
        <div class="fv_bg_cover"></div>
        <div class="fv_bg" style="background-image: url('<?= $this->Url->image('users/sites/headers/' . $username . '/' . $site->header_image_path) ?>');"></div>
        <div class="fv_container">
            <div class="fv_user_icon"><?= $this->Html->image('users/profiles/' . h($username) . '/' . h($profile->image_path)) ?></div>
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
                <li class="career_item">
                    <div class="career_item_title">
                        <p class="career_term">2012/4 ~ 2016/3</p>
                        <p class="career_work">慶応大学医学部在籍</p>
                    </div>
                    <p class="career_detail">学生時代は医学を学び、主に○○について勉強していました。<br />○○の論文を発表し○○賞を受賞しました</p>
                </li>
                <li class="career_item">
                    <div class="career_item_title">
                        <p class="career_term">2012/7 ~ 2016/2</p>
                        <p class="career_work">飲食業（アルバイト）</p>
                    </div>
                    <p class="career_detail">学生時代にスターバックスでアルバイトをしていました。</p>
                </li>
                <li class="career_item">
                    <div class="career_item_title">
                        <p class="career_term">2016/4 ~ 2022/7</p>
                        <p class="career_work">○○病院○○科勤務</p>
                    </div>
                    <p class="career_detail">○○科で医師をしていました。主に○○な患者と関わっていたため、○○についての知識はあります。</p>
                </li>
                <li class="career_item">
                    <div class="career_item_title">
                        <p class="career_term">2022/7</p>
                        <p class="career_work">Webライター</p>
                    </div>
                    <p class="career_detail">現在はWebライターとして、前職の知識を活かし、医療系のジャンルの記事を執筆しています。</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="works section">
        <div class="container">
            <h2 class="section_title">実績</h2>
            <ul class="works_content_list">
                <li class="works_content_item">
                    <h3 class="content_title">朝活メディア記事</h3>
                    <div class="works_content_image"><img src="img/Mask group.png" alt=""></div>
                    <p class="works_content_link">出典：『<a href="#">レッツ朝活サロン</a>』</p>
                    <p class="works_content_detail">
                        レッツ朝活サロンの記事です。早起きできるコツなどを解説しています。<br />
                        その他、在宅フリーランスが多く在籍しているので、在宅フリーランスの悩みである「孤独」を解消する方法も解説しています。
                    </p>
                </li>
                <li class="works_content_item">
                    <h3 class="content_title">朝活メディア記事</h3>
                    <div class="works_content_image"><img src="img/Mask group.png" alt=""></div>
                    <p class="works_content_link">出典：『<a href="#">レッツ朝活サロン</a>』</p>
                    <p class="works_content_detail">
                        レッツ朝活サロンの記事です。早起きできるコツなどを解説しています。<br />
                        その他、在宅フリーランスが多く在籍しているので、在宅フリーランスの悩みである「孤独」を解消する方法も解説しています。
                    </p>
                </li>
            </ul>
        </div>
    </div>
    <div class="work_style section">
        <div class="container">
            <h2 class="section_title">仕事について</h2>
            <div class="work_style_content">
                <div class="work_style_content_item">
                    <h3 class="content_title">対応可能な仕事</h3>
                    <ul class="work_style_content_list">
                        <li>記事構成案作成</li>
                        <li>記事作成</li>
                    </ul>
                </div>
                <div class="work_style_content_item">
                    <h3 class="content_title">特意なジャンル</h3>
                    <ul class="work_style_content_list">
                        <li>医療法人の記事</li>
                        <li>副業についての記事</li>
                    </ul>
                </div>
                <div class="work_style_content_item">
                    <h3 class="content_title">単価</h3>
                    <ul class="work_style_content_list">
                        <li>文字単価20円</li>
                    </ul>
                </div>
                <div class="work_style_content_item">
                    <h3 class="content_title">保有資格</h3>
                    <ul class="work_style_content_list">
                        <li>英検1級</li>
                        <li>漢検1級</li>
                    </ul>
                </div>
                <div class="work_style_content_item">
                    <h3 class="content_title">活動時間</h3>
                    <ul class="work_style_content_list">
                        <li>平日：9時～17時</li>
                        <li>土日：18時～22時</li>
                    </ul>
                </div>
                </ul>
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