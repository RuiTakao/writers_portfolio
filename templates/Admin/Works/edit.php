<?php

use App\Model\Table\WorksTable;
use Cake\Core\Configure;

/**
 * 画像パス
 */
// 各々のユーザーによって決まるパス
$self_path = $auth->username . '/' . $work->id . '/' . $work->image_path;
// ルートからのパス
$root_image_path = WorksTable::ROOT_WORKS_IMAGE_PATH . $self_path;
// webrootからのパス
$image_path = WorksTable::WORKS_IMAGE_PATH . $self_path;

if (!empty($work->image_path) && file_exists($root_image_path)) {
    $image_flg = true;
} else {
    $image_flg = false;
}
?>

<?php /* ページタイトル */ ?>
<?php $this->start('page_title') ?>
<?= $this->Html->link('実績の設定', ['action' => 'index']) ?> > <?= empty($work->id) ? '追加' : '編集' ?>
<?php $this->end() ?>

<?php /* css */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css(['dropify/css/dropify.min.css']) ?>
<style>
    .input {
        margin-top: 16px;
    }
</style>
<?php $this->end() ?>

<?php if (is_null($work->id)) : ?>
    <?php /* js */ ?>
    <?php $this->start('script') ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <?= $this->Html->script([
        'dropify/dropify.min.js',
        'dropify/works_add',
        'works',
    ]) ?>
    <?php $this->end() ?>
<?php endif; ?>

<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'edit', $work->id],
    'type' => 'file',
    'onSubmit' => (is_null($work->id)) ? 'return checkAdd()' : 'return checkEdit()'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'required' => false]) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要', 'required' => false, 'style' => 'height: 114px;']) ?>

<?php if (is_null($work->id)) : ?>
    <?php /* 新規登録 */ ?>

    <p class="content_title mt32">関連URL</p>
    <?= $this->Form->control('url_path', ['label' => 'URL', 'required' => false]) ?>
    <?= $this->Form->control('url_name', ['label' => 'URL名 (※表示するURL名を変更したい場合はこちらに入力して下さい。)', 'required' => false]) ?>
    <div class="content_title mt32">
        <p>関連画像</p>
    </div>
    <?= $this->Form->control('image_path', ['class' => 'dropify', 'type' => 'file', 'label' => false]) ?>
    <div class="button-container default mt16">
        <?= $this->Form->submit(Configure::read('button.add'), ['class' => 'button default']) ?>
        <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
    </div>
    <?= $this->Form->end() ?>
<?php else : ?>
    <?php /* 編集 */ ?>

    <div class="button-container default mt16">
        <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default']) ?>
        <?= $this->Html->link('戻る', ['action' => 'index'], ['class' => 'button default back']) ?>
    </div>
    <?= $this->Form->end() ?>
    <table class="current_content_table mt64">
        <tr>
            <th>
                <?php
                echo "関連URL";
                echo $this->Html->link(!empty($work->url_path) ? 'URL変更' : 'URL追加', ['action' => 'editLink', $work->id], ['class' => 'button']);
                if ($image_flg) {
                    echo $this->Form->postLink('URL削除', ['controller' => 'Works', 'action' => 'deleteLink', $work->id], ['class' => 'button delete', 'confirm' => '関連URLを削除しますか？']);
                }
                ?>
            </th>
        </tr>
        <tr>
            <td>
                <?php
                if (!empty($work->url_name)) {
                    echo $this->Html->link($work->url_name, $work->url_path, ['target' => '_blank']) . '<span class="ml8">(' . $work->url_path . ')</span>';
                } elseif (!empty($work->url_path)) {
                    echo  $this->Html->link($work->url_path, $work->url_path, ['target' => '_blank']);
                } else {
                    echo "未設定";
                }
                ?>
            </td>
        </tr>
    </table>
    <table class="current_content_table mt32">
        <tr>
            <th>
                <?php
                echo "関連画像";
                echo $this->Html->link($image_flg ? '画像変更' : '画像追加', ['action' => 'editImage', $work->id], ['class' => 'button']);
                if ($image_flg) {
                    echo $this->Form->postLink('画像削除', ['controller' => 'Works', 'action' => 'deleteImage', $work->id], ['class' => 'button delete', 'confirm' => '関連画像を削除しますか？']);
                }
                ?>
            </th>
        </tr>
        <tr>
            <td>
                <?php
                if ($image_flg) {
                    echo $this->Html->image($image_path, ['class' => 'rectangle_image']);
                } else {
                    echo "未設定";
                }
                ?>
            </td>
        </tr>
    </table>
<?php endif; ?>