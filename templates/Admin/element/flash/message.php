<script>
    <?php /* 更新確認用モーダル */ ?>
    function checkEdit() {
        if (confirm(`変更を保存しますか？`)) {
            return true;
        } else {
            return false;
        }
    }

    <?php /* 保存確認用モーダル */ ?>
    function checkSave() {
        if (confirm(`この内容で保存しますか？`)) {
            return true;
        } else {
            return false;
        }
    }

    <?php /* 追加確認用モーダル */ ?>
    function checkAdd() {
        if (confirm(`この内容で追加しますか？`)) {
            return true;
        } else {
            return false;
        }
    }

    <?php /* 削除確認用モーダル */ ?>
    function checkDelete() {
        if (confirm(`削除しますか？`)) {
            return true;
        } else {
            return false;
        }
    }

    <?php /* ログアウト確認モーダル */ ?>
    function logout() {
        if (confirm(`ログアウトしますか？`)) {
            return true;
        } else {
            return false;
        }
    }

    <?php if ($session->read('message')) : ?>
        <?php /* 処理結果通知モーダル */ ?>
        window.onload = () => alert('<?= $session->read('message') ?>');
        <?php $session->delete('message') ?>
    <?php endif; ?>
</script>