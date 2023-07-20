<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <link rel="shortcut icon" href="">
  <link rel="icon" href="">
  <title><?php if (isset($title) && !empty($title)): echo $title . ' ｜ '; endif;?>table def.</title>
  <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>app/assets/css/app.css">
</head>

<body>
  <main class="l-main">
    <div class="l-contents">
        <?php echo $mainContent; ?>
    </div>
  </main>
  <footer class="l-footer">
    <p class="l-footer__copyright"><i>&copy;</i>DAISY Inc.</p>
    <a class="c-pageTop" href="#"><span></span></a>
  </footer>
  <script src="<?php echo ROOT_PATH; ?>app/assets/js/app.js"></script>
</body>

</html>