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

$data_default_file = null;
if (!is_null($work->image_path) && $work->image_path != '' && file_exists($root_image_path)) {
    $data_default_file = $this->Url->image($image_path);
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
        'dropify/works'
    ]) ?>
    <?php $this->end() ?>
<?php endif; ?>

<?= $this->Form->create($work, [
    'url' => ['controller' => 'Works', 'action' => 'edit', $work->id],
    'type' => 'file',
    'onSubmit' => 'return checkAdd()'
]) ?>
<?= $this->Form->control('title', ['label' => 'タイトル', 'required' => false]) ?>
<?= $this->Form->control('overview', ['type' => 'textarea', 'label' => '概要', 'required' => false, 'style' => 'height: 114px;']) ?>

<?php if (is_null($work->id)) : ?>
    <?php /* 新規登録 */ ?>

    <p class="content_title mt32">関連リンク</p>
    <?= $this->Form->control('url_path', ['label' => 'URL', 'required' => false]) ?>
    <?= $this->Form->control('url_name', ['label' => 'URL名 (※表示するURLリンクを変更したい場合はこちらに入力して下さい。)', 'required' => false]) ?>
    <div class="content_title mt32">
        <p>関連画像</p>
    </div>
    <?= $this->Form->control('image_path', ['class' => 'dropify', 'type' => 'file', 'label' => false]) ?>
    <?= $this->Form->submit(Configure::read('button.add'), ['class' => 'button default mt16']) ?>
    <?= $this->Form->end() ?>
<?php else : ?>
    <?php /* 編集 */ ?>

    <?= $this->Form->submit(Configure::read('button.save'), ['class' => 'button default mt16']) ?>
    <?= $this->Form->end() ?>
    <table class="current_content_table mt64">
        <tr>
            <th>関連リンク<?=
                        $this->Html->link('URLの変更', ['action' => 'editLink', $work->id], ['class' => 'button'])
                        ?><?=
                            $this->Form->postLink('URL削除', ['controller' => 'Works', 'action' => 'deleteLink', $work->id], ['class' => 'button delete', 'confirm' => 'URLを削除しますか？'])
                            ?></th>
        </tr>
        <tr>
            <td>
                <?= $this->Html->link($work->url_name, ['action' => 'editImage']) ?>
            </td>
        </tr>
    </table>
    <table class="current_content_table mt32">
        <tr>
            <th>関連画像<?=
                    $this->Html->link('画像の変更', ['action' => 'editImage', $work->id], ['class' => 'button'])
                    ?><?=
                        $this->Form->postLink('画像削除', ['controller' => 'Works', 'action' => 'deleteImage', $work->id], ['class' => 'button delete', 'confirm' => '画像を削除しますか？'])
                        ?></th>
        </tr>
        <tr>
            <td><?= $this->Html->image($image_path, ['class' => 'rectangle_image']) ?></td>
        </tr>
    </table>
<?php endif; ?>