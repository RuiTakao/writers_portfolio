<div class="fv">
    <div class="fv_bg"></div>
    <div class="fv_container">
        <div class="fv_user_icon">
            <?php if (!empty($profile_image)) : ?>
                <?= $this->Html->image($profile_image) ?>
            <?php else : ?>
                <i class="fa-solid fa-user"></i>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <p class="fv_user_name"><?= h($user->profile->view_name) ?></p>
    <p class="fv_user_works"><?= h($user->profile->works) ?></p>
</div>

<div class="pr">
    <div class="container">
        <div class="pr_content"><?= !empty($user->profile->profile_text) ? nl2br(h($user->profile->profile_text)) : '' ?></div>
    </div>
</div>