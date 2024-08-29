
<?php if ($created) : ?>
<p><a href="<?php echo ROOT_PATH; ?>" class="c-btn --next">DB定義書 表示</a></p>

<hr>
<h3 class="c-sideSubMenu__ttl">Setting</h3>
<ul>
    <li><a href="<?php echo ROOT_PATH; ?>setting/general"><i>-</i><span>General</span></a></li>
    <li>
        <a href="<?php echo ROOT_PATH; ?>setting/addition"><i>-</i><span>Addition</span></a>
        <ul>
            <?php if ($menuHandler['tables_Edit']) : ?>
                <li class="--invalid">
                    <span>Tables Edit</span>
                    <span class="c-tooltip">
                        <em>?</em>
                        <span class="text">max_input_vars を<?php Utils::_esc($menuHandler['tables_Edit']); ?>以上にしてください。 </span>
                    </span>
                </li>
            <?php else : ?>
                <li><a href="<?php echo ROOT_PATH; ?>setting/addition/tables"><span>Tables Edit</span></a></li>
            <?php endif; ?>

            <?php if ($menuHandler['common_Field_Edit']) : ?>
                <li class="--invalid">
                    <span>Common Field Edit</span>
                    <span class="c-tooltip">
                        <em>?</em>
                        <span class="text">max_input_vars を<?php Utils::_esc($menuHandler['common_Field_Edit']); ?>以上にしてください。 </span>
                    </span>
                </li>
            <?php else : ?>
                <li><a href="<?php echo ROOT_PATH; ?>setting/addition/fields"><span>Common Field Edit</span></a></li>
            <?php endif; ?>
            <li><a href="<?php echo ROOT_PATH; ?>setting/addition"><span>Csv Edit</span></a></li>
        </ul>
    </li>
    <li><a href="<?php echo ROOT_PATH; ?>setting/connect"><i>-</i><span>DB connect</span></a></li>
    <li><a href="<?php echo ROOT_PATH; ?>setting/inout"><i>-</i><span>Import Export</span></a></li>
</ul>
<hr>
<ul>
    <li><a href="<?php echo ROOT_PATH; ?>setting/delete"><i>-</i><span>Delete</span></a></li>
</ul>
<?php else: ?>
<h3 class="c-sideSubMenu__ttl">Setting</h3>
<ul>
    <li><a href="<?php echo ROOT_PATH; ?>setting/connect"><i>-</i><span>DB connect</span></a></li>
    <li><a href="<?php echo ROOT_PATH; ?>setting/inout"><i>-</i><span>Import</span></a></li>
</ul>
<?php endif; ?>