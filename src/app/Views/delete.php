<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">Delete</h2>
        <?php if ($assign['successes']) : ?>
            <div class="c-form__Message js-message">
            <div class="c-form__MessageBox">
            <?php foreach ($assign['successes'] as $success) : ?>
                <p><?php echo $success; ?></p>
            <?php endforeach;?>
            </div>
            </div>
        <?php endif; ?>
        <div class="c-form__leadText">
            <p>全ての定義ファイルの削除を行います。</p>
            <p>データベース定義・テーブル・フィールド等の論理名やコメント等の設定ファイルの全てが削除されます。</p>
            <p class="u-text-suppl">※ 削除後は、DB connect から新しく定義ファイルの作成が必要になります。</p>
        </div>
        <?php if ($assign['errors']) : foreach ($assign['errors'] as $error) : ?>
            <p class="errorText"><?php echo $error; ?></p>
        <?php endforeach; endif; ?>
        <div class="c-formItem__btn">
            <button type="submit" class="c-btn --delete js-btnDelete"<?php if (!$assign['created'] || $assign['locked']) : ?> disabled<?php endif; ?>>Delete<i><!-- --></i></button>
        </div>
    </div>
    </form>
</div>