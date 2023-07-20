<div class="c-box l-columns">
    <nav class="c-sideSubMenu">
        <?php echo $settingMenu; ?>
    </nav>
    <form method="POST" enctype="multipart/form-data">
    <?php Utils::CSRF(); ?>
    <div class="c-form">
        <h2 class="c-ttl">Addition</h2>
        <?php if ($assign['successes']) : ?>
            <div class="c-form__Message js-message">
            <div class="c-form__MessageBox">
            <?php foreach ($assign['successes'] as $success) : ?>
                <p><?php echo $success; ?></p>
            <?php endforeach;?>
            </div>
            </div>
        <?php endif; ?>


        <?php if ($assign['files']): ?>
        <div class="p-setting__files">
            <?php if ($assign['files']['tables']): ?>
            <div class="p-setting__fileBox">
              <h3>Tables definition file</h3>
              <p>テーブル名に対して論理名・コメント・色の設定ができます。</p>
              <p class="u-text-caution">※ 並びを変えることで並びの変更も可能です。</p>
              <a href="<?php echo $assign['files']['tables'];?>" class="c-btn --sm">csv DL.</a>
            </div>
            <?php endif; ?>
            
            <?php if ($assign['files']['fields']): ?>
            <div class="p-setting__fileBox">
              <h3>Fields definition file</h3>
              <p>各テーブルのフィールドごとに論理名・コメント・色の設定</p>
              <p class="u-text-caution">　</p>
              <a href="<?php echo $assign['files']['fields'];?>" class="c-btn --sm">csv DL.</a>
            </div>
            <?php endif; ?>
            <?php if ($assign['files']['common']): ?>
            <div class="p-setting__fileBox">
              <h3>Common definition file</h3>
              <p>共通のフィールド名に対して論理名の設定ができます。</p>
              <p class="u-text-caution">※ 論理名は、全フィールドに反映されます。（Fields definition の設定が優先）</p>
              <a href="<?php echo $assign['files']['common'];?>" class="c-btn --sm">csv DL.</a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="c-form__leadText">
            <p>物理名に対して、論理名やコメント等の設定を行い定義ファイルの更新を行います。</p>
            <p>上記より現在の各定義ファイルをDLして内容を修正してください。</p>
        </div>
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Tables definition file </div>
                <p class="u-text-suppl"></p>
            </dt>
            <dd>
                <div class="c-fileInpu">
                    <label> csv file.<input type="file" name="tablesFile" class="js-filePreview" accept="text/csv">
                    </label>
                    <div class="c-fileInpu__preview"></div>
                </div>
            </dd>
        </dl>
        
        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Fields definition file </div>
                <p class="u-text-suppl"></p>
            </dt>
            <dd>
                <div class="c-fileInpu">
                    <label> csv file.<input type="file" name="fieldsFile" class="js-filePreview" accept="text/csv">
                    </label>
                    <div class="c-fileInpu__preview"></div>
                </div>
            </dd>
        </dl>

        <dl class="c-formItem">
            <dt class="c-formItem__head">
                <div class="c-formItem__ttl"> Common definition file </div>
                <p class="u-text-suppl"></p>
            </dt>
            <dd>
                <div class="c-fileInpu">
                    <label> csv file.<input type="file" name="commonFile" class="js-filePreview" accept="text/csv">
                    </label>
                    <div class="c-fileInpu__preview"></div>
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