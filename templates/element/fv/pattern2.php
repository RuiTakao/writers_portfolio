<div class="fv">
    <div class="fv_bg"></div>
    <div class="fv_user_icon">
        <?php if (!empty($profile_image)) : ?>
            <?= $this->Html->image($profile_image) ?>
        <?php else : ?>
            <i class="fa-solid fa-user"></i>
        <?php endif; ?>
    </div>
    <div class="fv_user_content">
        <p class="fv_user_name"><?= h($profile->view_name) ?></p>
        <p class="fv_user_works"><?= h($profile->works) ?></p>
    </div>
</div>