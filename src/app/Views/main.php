
<?php if ($assign['created']) : ?>
<div class="c-box">
<div class="p-document">
  <div class="p-document__head">
    <div>
      <?php if ($assign['database']['version']) : ?>
      <time>ver. <span><?php Utils::_esc($assign['database']['version'] ?? ''); ?></span></time>
      <?php endif; ?>
      <?php if ($assign['database']['title']) : ?>
      <h1><?php Utils::_esc($assign['database']['title'] ?? ''); ?></h1>
      <?php endif; ?>
      <?php if ($assign['database']['comment']) : ?>
      <p><?php Utils::_esc($assign['database']['comment'] ?? ''); ?></p>
      <?php endif; ?>

    </div>
    <ul>
      <li><a href="<?php echo ROOT_PATH; ?>export" class="c-btn --sm js-btnAction" id="download-link">Excel</a></li>
      <li><a href="<?php echo ROOT_PATH; ?>setting/general" class="c-btn --sm">Setting</a></li>
    </ul>
  </div>
  <div class="p-document__item">
    <div class="p-document__itemHead">
      <h2> エンティティ一覧 </h2>
    </div>
    <div class="p-document__table --entity">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>論理名</th>
            <th>物理名</th>
            <th>定義情報</th>
            <th>備考</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; foreach ($assign['database']['tables'] as $table):
            $bgcolorStyle = '';
            if (!empty($table['bgcolor'])) {
                $bgcolorStyle = 'style="background-color: #' . $table['bgcolor'] . ';"';
            }
          ?>
          <tr <?php echo $bgcolorStyle; ?>>
            <td><?php echo $count; ?></td>
            <td><?php if ($table['name']) : ?><a href="#<?php echo $table['table']; ?>"><?php echo $table['name']; ?></a><?php endif; ?></td>
            <td><a href="#<?php Utils::_esc($table['table'] ?? ''); ?>"><?php Utils::_esc($table['table'] ?? ''); ?></a></td>
            <td><?php echo $table['definition']; ?></td>
            <td><?php echo nl2br($table['comment']); ?></td>
          </tr>
          <?php $count++; endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php foreach ($assign['database']['tables'] as $table): ?>
  <div class="p-document__item" id="<?php Utils::_esc($table['table'] ?? ''); ?>">
    <div class="p-document__itemHead">
      <h2># <?php if ($table['name']) : ?><span><?php Utils::_esc($table['name'] ?? ''); ?></span><?php endif; ?><?php Utils::_esc($table['table'] ?? ''); ?></h2>
      <?php if ($table['comment']) : ?>
      <p><?php Utils::_esc($table['comment'] ?? ''); ?></p>
      <?php endif; ?>
    </div>
    <div class="p-document__table --table">
      <h3>- フィールド情報</h3>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>論理名</th>
            <th>物理名</th>
            <th>型</th>
            <th>KEY</th>
            <th>Null</th>
            <th>Default</th>
            <th>Extra</th>
            <th>Comment</th>
          </tr>
        </thead>
        <tbody>
         <?php $count = 1; foreach ($table['columns'] as $column):
            $bgcolorStyle = '';
            if (!empty($column['bgcolor'])) {
                $bgcolorStyle = 'style="background-color: #' . $column['bgcolor'] . ';"';
            }
         ?>
          <tr <?php echo $bgcolorStyle; ?>>
            <td><?php echo $count; ?></td>
            <td><?php Utils::_esc($column['name'] ?? ''); ?></td>
            <td><?php echo $column['field']; ?></td>
            <td><?php echo $column['type']; ?></td>
            <td><?php echo $column['key']; ?></td>
            <td><?php echo ($column['null'] == 'YES') ? '' : '×'; ?></td>
            <td><?php echo $column['default']; ?></td>
            <td><?php echo $column['extra']; ?></td>
            <td><?php Utils::_esc($column['comment'] ?? ''); ?></td>
          </tr>
          <?php $count++; endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="p-document__table --index">
      <h3>- インデックス情報</h3>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>キー名</th>
            <th>フィールド</th>
            <th>主キー</th>
            <th>一意</th>
            <th>Comment</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; foreach ($table['indexs'] as $index): ?>
          <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $index['name']; ?></td>
            <td><?php echo $index['column']; ?></td>
            <td><?php echo ($index['primary']) ? '○' : ''; ?></td>
            <td><?php echo ($index['unique']) ? '○' : ''; ?></td>
            <td><?php echo $index['comment']; ?></td>
          </tr>
          <?php $count++; endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endforeach; ?>

</div>
</div>
<?php else : ?>
<div class="c-box">
    <div class="l-columns l-columns__center">
        <a href="<?php echo ROOT_PATH; ?>setting/connect" class="c-btn --next js-btnAction">Start ...<i><!-- --></i></a>
    </div>
</div>
<?php endif; ?>