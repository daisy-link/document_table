<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>



    


    <div class="c-form">
        <h2 class="c-ttl">Import<?php if ($assign['created']) : ?> Export<?php endif; ?></h2>
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
            <p>定義ファイルの<?php if ($assign['created']) : ?>ダウンロード・<?php endif; ?>インポートを行います。</p>
            <p class="u-text-suppl">※ インポートファイルは、DLしたZipファイルを利用してください。また、インポートした場合、それまでの定義ファイルは削除されます。</p>
        </div>

        <?php if ($assign['created']) : ?>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Export </div>
                <p class="u-text-suppl">現在のテーブル定義書の設定ファイルをダウンロードします。</p>
            </dt>
            <dd>
                <a href="<?php echo ROOT_PATH; ?>download" class="c-btn --sm js-btnAction" id="download-link">Export</a>
            </dd>
        </dl>
        
        <hr>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
        <?php Utils::CSRF(); ?>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Import </div>
                <p class="u-text-suppl"></p>
            </dt>
            <dd>
                <div class="c-fileInpu">
                    <label> zip file.<input type="file" name="zipFile" class="js-filePreview" accept="application/zip"></label>
                    <div class="c-fileInpu__preview"></div>
                </div>
            </dd>
        </dl>
        <?php if ($assign['errors']) : foreach ($assign['errors'] as $error) : ?>
            <p class="errorText"><?php echo $error; ?></p>
        <?php endforeach; endif; ?>
        <div class="c-formItem__btn">
            <button type="submit" class="c-btn --next js-btnAction"<?php if ($assign['locked']) : ?> disabled<?php endif; ?>>Import<i><!-- --></i></button>
        </div>
        </form>

    </div>

</div>