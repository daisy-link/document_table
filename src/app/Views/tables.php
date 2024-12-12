<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">Addition - Tables Edit</h2>
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
            <p>テーブル名に対して論理名・コメント・色の設定ができます。<br>設定後に「update」をクリックしてください。</p>
            <p class="u-text-caution">※ ドラック＆ドロップで並び変えも可能となります。</p>
        </div>

        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Tables definition </div>
            </dt>

            <dd>
                <div class="c-table --tablesEdit">
                <table class="js-sortable">
                    <thead>
                        <tr>
                            <?php foreach ($assign['tables'][0] as $id => $column) : ?>
                                <th><?php Utils::_esc($column ?? ''); ?>
                                <?php if ($id == 4) : ?>
                                <span class="c-tooltip --right">
                                    <em>?</em>
                                    <span class="text">#無しのカラーコード</span>
                                </span>
                                <?php endif; ?>
                                </th>
                            <?php endforeach; unset($assign['tables'][0]); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assign['tables'] as $id => $rows) : ?>
                        <tr draggable="true">
                            <?php foreach ($rows as $key => $column) : ?>
                            <?php if ($key == 0) : ?>
                            <td class="u-text-nowrap">
                                <?php Utils::_esc($column ?? ''); ?>
                                <input type="hidden" name="tables[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" value="<?php Utils::_esc($column ?? ''); ?>">
                            </td>
                            <?php elseif ($key == 3) : ?>
                            <td class="u-text-nowrap">
                                <textarea name="tables[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="100"><?php Utils::_esc($column ?? ''); ?></textarea>
                            </td>
                            <?php elseif ($key == 2|| $key == 4) : ?>
                            <td>
                                <input type="text" name="tables[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="10" class="" value="<?php Utils::_esc($column ?? ''); ?>">
                            </td>
                            <?php else: ?>
                            <td>
                                <input type="text" name="tables[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="500" class="" value="<?php Utils::_esc($column ?? ''); ?>">
                            </td>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </dd>
        </dl>

        <?php if ($assign['errors']) : foreach ($assign['errors'] as $error) : ?>
            <p class="errorText"><?php echo $error; ?></p>
        <?php endforeach; endif; ?>
        <div class="c-formItem__btn">
            <button type="submit" class="c-btn --next js-btnAction"<?php if ($assign['locked']) : ?> disabled<?php endif; ?>>update<i><!-- --></i></button>
        </div>
    </div>
    </form>
</div>