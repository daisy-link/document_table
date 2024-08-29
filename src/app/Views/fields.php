<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">Addition - Common Field Edit</h2>
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
            <p>共通のフィールド名（同じ物理名）に対して論理名の設定ができます。<br>設定後に「update」をクリックしてください。</p>
            <p class="u-text-caution">※ 各テーブル毎にフィールド名を設定する場合は、CSV を利用してください。</p>
            <p class="u-text-caution">※ 論理名が、全フィールドに反映されますが、CSVで個別に設定しているものが優先されます。</p>
        </div>

        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Common Field definition </div>
                <p class="u-text-suppl"></p>
            </dt>

            <dd>
                <div class="c-table --fieldsEdit">
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($assign['fields'][0] as $id => $column) : ?>
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
                        <?php foreach ($assign['fields'] as $id => $rows) :  ?>
                        <tr>
                            <?php foreach ($rows as $key => $column) : ?>
                            <?php if ($key == 0) : ?>
                            <td class="u-text-nowrap">
                                <?php Utils::_esc($column ?? ''); ?>
                                <input type="hidden" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" value="<?php Utils::_esc($column ?? ''); ?>">
                            </td>
                            <?php elseif ($key == 2) : ?>
                            <td class="u-text-nowrap">
                                <textarea name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="100"><?php Utils::_esc($column ?? ''); ?></textarea>
                            </td>
                            <?php else: ?>
                            <td>
                                <input type="text" name="fields[<?php Utils::_esc($id); ?>][<?php Utils::_esc($key); ?>]" maxlength="50" class="" value="<?php Utils::_esc($column ?? ''); ?>">
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