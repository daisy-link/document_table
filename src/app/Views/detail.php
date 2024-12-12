<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    
    <div class="c-form">
        <h2 class="c-ttl">Addition - Detail Field Edit</h2>
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
            <p>テーブル毎のフィールド名に対して論理名の設定ができます。<br>tables選択後に、各項目を設定後に「update」をクリックしてください。</p>
            <p class="u-text-caution">※ ドラック＆ドロップで並び変えも可能となります。</p>
        </div>

        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> tables </div>
            </dt>
            <dd>
                <div class="c-selectbox">
                    <select name="link" onChange="location.href=value;">
                        <option value="<?php echo ROOT_PATH . 'setting/addition/detail/'; ?>">select...</option>
                        <?php unset($assign['tables'][0]); foreach ($assign['tables'] as $table) : ?>
                        <option value="<?php echo ROOT_PATH . 'setting/addition/detail/' . $table[0]; ?>"<?php Utils::_selected($table[0], $assign['target'] ?? ''); ?>><?php Utils::_esc($table[0] ?? ''); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </dd>
        </dl>

        <?php if (isset($assign['fields']) && !empty($assign['fields'])) : ?>
            <form method="POST">
            <?php Utils::CSRF(); ?>
            <input type="hidden" name="target" value="<?php Utils::_esc($assign['target'] ?? ''); ?>">
            <dl class="c-formItem">
                <dt class="c-formItem__head">
                    <div class="c-formItem__ttl"> Field definition </div>
                    <p class="u-text-suppl"></p>
                </dt>
                <dd>
                    <div class="c-table --detailEdit">
                    <table class="js-sortable">
                        <thead>
                            <tr>
                                <?php foreach ($assign['fields'][0] as $id => $column) : if ($id == 0) : continue; endif; ?>
                                <th><?php Utils::_esc($column ?? ''); ?>
                                <?php if ($id == 4) : ?>
                                <span class="c-tooltip --right">
                                    <em>?</em>
                                    <span class="text">#無しのカラーコード</span>
                                </span>
                                <?php endif; ?>
                                </th>
                                <?php endforeach; unset($assign['fields'][0]); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php unset($assign['fields'][0]); foreach ($assign['fields'] as $id => $rows) : ?>
                                <tr draggable="true">
                                    <?php foreach ($rows as $key => $column) : ?>

                                    <?php if ($key == 0) : ?>
                                        <input type="hidden" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" value="<?php Utils::_esc($column ?? ''); ?>">
                                    <?php elseif ($key == 1) : ?>
                                    <td class="u-text-nowrap">
                                        <?php Utils::_esc($column ?? ''); ?>
                                        <input type="hidden" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" value="<?php Utils::_esc($column ?? ''); ?>">
                                    </td>
                                    <?php elseif ($key == 3) : ?>
                                    <td class="u-text-nowrap">
                                        <textarea name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="100"><?php Utils::_esc($column ?? ''); ?></textarea>
                                    </td>
                                    <?php elseif ($key == 4) : ?>
                                    <td>
                                        <input type="text" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="10" class="" value="<?php Utils::_esc($column ?? ''); ?>">
                                    </td>

                                    <?php else: ?>
                                    <td>
                                        <input type="text" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="100" class="" value="<?php Utils::_esc($column ?? ''); ?>">
                                    </td>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
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
            </form>

        <?php endif; ?>
    </div>
    
</div>