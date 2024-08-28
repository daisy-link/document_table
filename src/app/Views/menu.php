
<?php if ($created) : ?>
<p><a href="<?php echo ROOT_PATH; ?>" class="c-btn --next">DB定義書 表示</a></p>

<hr>
<h3 class="c-sideSubMenu__ttl">Setting</h3>
<ul>
    <li><a href="<?php echo ROOT_PATH; ?>setting/general"><i>-</i><span>General</span></a></li>
    <li><a href="<?php echo ROOT_PATH; ?>setting/addition"><i>-</i><span>Addition</span></a></li>
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