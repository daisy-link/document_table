<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">General</h2>
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
            <p>テーブル定義書のタイトル・概要説明・versionを設定し定義ファイルの更新を行います。</p>
        </div>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> title </div>
                <p class="u-text-suppl">テーブル定義書のタイトルを設置</p>
            </dt>
            <dd>
                <input type="text" name="title" class="u-width-m<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" maxlength="50" placeholder="" value="<?php Utils::_esc($assign['datas']['title'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> comment </div>
                <p class="u-text-suppl">テーブル定義書に概要説明を設定</p>
            </dt>
            <dd>
                <input type="text" name="comment" class="u-width-l<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" maxlength="100" placeholder="" value="<?php Utils::_esc($assign['datas']['comment'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> version </div>
                <p class="u-text-suppl">テーブル定義書にversionを設定</p>
            </dt>
            <dd>
                <input type="text" name="version" class="u-width-ssm<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" maxlength="8" placeholder="" value="<?php Utils::_esc($assign['datas']['version'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Lock </div>
                <p class="u-text-suppl">この画面以外でのテーブル定義の更新が禁止されます</p>
            </dt>
            <dd>
                <label class="c-toggle">
                    <input name="lock" type="checkbox" value="1"<?php if (isset($assign['datas']['lock']) && !empty($assign['datas']['lock'])): ?> checked<?php endif; ?> >
                    <span></span>
                    テーブル定義書の更新を禁止
                </label>
            </dd>
        </dl>
        <?php if ($assign['errors']) : foreach ($assign['errors'] as $error) : ?>
            <p class="errorText"><?php echo $error; ?></p>
        <?php endforeach; endif; ?>
        <div class="c-formItem__btn">
            <button type="submit" class="c-btn --next js-btnAction">update<i><!-- --></i></button>
        </div>
    </div>
    </form>
</div>