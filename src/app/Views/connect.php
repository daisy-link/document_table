<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">DB connect</h2>

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
            <?php if ($assign['created']) : ?>
                <p>データベース情報を設定して作成された定義ファイルの更新を行います。</p>
                <p class="u-text-suppl">※ 定義ファイル作成後に、DBの変更があった場合にご利用ください。</p>
            <?php else : ?>
                <p>データベース情報を設定して定義ファイルの作成を行います。</p>
            <?php endif; ?>
        </div>


        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> host </div>
            </dt>
            <dd>
                <div class="c-selectbox">
                    <select name="type" class="<?php if (!empty($assign['errors'])): ?>is-error<?php endif; ?>">
                        <option value="mysql" <?php Utils::_selected('mysql', $assign['datas']['type'] ?? ''); ?>>MySQL</option>
                        <option value="mssql" <?php Utils::_selected('mssql', $assign['datas']['type'] ?? ''); ?>>MSSQL</option>
                    </select>
                </div>
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> dbhost </div>
            </dt>
            <dd>
                <input type="text" name="dbhost" maxlength="50" placeholder="" class="u-width-m<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" value="<?php Utils::_esc($assign['datas']['dbhost'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> dbport </div>
            </dt>
            <dd>
                <input type="text" name="dbport" maxlength="6" placeholder="" class="u-width-ssm<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" value="<?php Utils::_esc($assign['datas']['dbport'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> dbname </div>
            </dt>
            <dd>
                <input type="text" name="dbname" maxlength="50" placeholder="" class="u-width-m<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" value="<?php Utils::_esc($assign['datas']['dbname'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> dbuser </div>
            </dt>
            <dd>
                <input type="text" name="dbuser" maxlength="50" placeholder="" class="u-width-m<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" value="<?php Utils::_esc($assign['datas']['dbuser'] ?? ''); ?>">
            </dd>
        </dl>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> dbpassword </div>
            </dt>
            <dd>
                <input type="password" name="dbpass" maxlength="50" placeholder="" class="u-width-m<?php if (!empty($assign['errors'])): ?> is-error<?php endif; ?>" value="<?php Utils::_esc($assign['datas']['dbpass'] ?? ''); ?>">
            </dd>
        </dl>
        <?php if ($assign['errors']) : foreach ($assign['errors'] as $error) : ?>
            <p class="errorText"><?php echo $error; ?></p>
        <?php endforeach; endif; ?>
        <div class="c-formItem__btn">
            <button type="submit" class="c-btn --next js-btnAction"<?php if ($assign['locked']) : ?> disabled<?php endif; ?>>
                <?php if ($assign['created']) : ?>update<?php else : ?>initial<?php endif; ?><i><!-- --></i>
            </button>
        </div>
    </div>
    </form>
</div>