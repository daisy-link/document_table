<?php
require_once __DIR__ . '/app/require.php';


Flight::route('/', function(){
    $objPages = new Pages();
    Flight::render('main', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout');
});


Flight::route('GET|POST /setting/connect', function(){
    $objPages = new Pages();
    $objPages->connect();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/connect');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('connect', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'DB connect']);
});


Flight::route('GET|POST /setting/general', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    $objPages->general();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/general');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('general', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'General']);
});


Flight::route('GET|POST /setting/addition', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    $objPages->addition();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/addition');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('addition', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition']);
});

Flight::route('GET|POST /setting/addition/tables', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    if ($objPages->menuHandler['tables_Edit']) {
        Flight::redirect('/setting/addition');
    }
    $objPages->additionTables();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/addition/tables');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('tables', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition - Tables Edit']);
});

Flight::route('GET|POST /setting/addition/fields', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    if ($objPages->menuHandler['common_Field_Edit']) {
        Flight::redirect('/setting/addition');
    }
    $objPages->additionCommonField();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/addition/fields');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('fields', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition - Common Fields Edit']);
});


Flight::route('GET|POST /setting/addition/detail(/@table)', function(?string $table = null) {

    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    if ($objPages->menuHandler['common_Field_Edit']) {
        Flight::redirect('/setting/addition');
    }
    $objPages->additionDetailField($table);
    if ($objPages->assign['reload']) {

        $reload = '/setting/addition/detail/' . $table;
        Flight::redirect($reload);
    
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('detail', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition - Detail Fields Edit']);
});

Flight::route('GET|POST /setting/inout', function(){
    $objPages = new Pages();
    $objPages->inout();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/inout');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('inout', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Import Export']);
});

Flight::route('GET|POST /setting/delete', function(){
    $objPages = new Pages();
    $objPages->delete();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/delete');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('delete', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Delete']);
});

Flight::route('/download', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    $objPages->download();
    Flight::redirect('/setting/inout');
});


Flight::route('/export', function(){
    $objPages = new Pages();
    if (!$objPages->assign['created']) {
        Flight::redirect('/setting/connect');
    }
    $objPages->export();
});

function minifyCss($css) {
    // CSSコメント削除
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    // 改行・タブ・余分なスペース削除
    $css = preg_replace('/\s*([{};,:])\s*/', '$1', $css);
    // 連続するスペースを1つに
    $css = preg_replace('/\s+/', ' ', $css);
    // 最後のセミコロン削除
    $css = preg_replace('/;}/', '}', $css);
    return trim($css);
}

Flight::route('/html', function(){
    $objPages = new Pages();
    
    // HTMLをレンダリング
    ob_start();
    Flight::render('main', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout');
    $htmlContent = ob_get_clean(); // バッファの内容を取得してクリア
    
    // app.css の内容を取得
    $cssFile = __DIR__ . '/app/assets/css/app.css';
    if (file_exists($cssFile)) {
        $cssContent = file_get_contents($cssFile);
        $cssContent .= ".p-document__head ul{display:none;}";
        $cssContent = minifyCss($cssContent); // CSSをミニファイ
        $cssInline = "<style>" . $cssContent . "</style>";
    } else {
        $cssInline = '';
    }

    // <link rel="stylesheet" href="/app/assets/css/app.css"> を削除し、<style> を埋め込む
    $htmlContent = preg_replace(
        '/<link\s+rel=["\']stylesheet["\']\s+href=["\']\/app\/assets\/css\/app\.css["\']\s*>/i',
        $cssInline,
        $htmlContent
    );

    $htmlContent = preg_replace(
        '/<script\s+src=["\']\/app\/assets\/js\/app\.js["\']\s*><\/script>/i',
        '',
        $htmlContent
    );

    // ファイルに保存
    $filePath = __DIR__ . '/database/page.html';
    file_put_contents($filePath, $htmlContent);
    
    // ダウンロード用ヘッダー
    Flight::response()
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Type', 'application/octet-stream')
        ->header('Content-Disposition', 'attachment; filename="page.html"')
        ->header('Expires', '0')
        ->header('Cache-Control', 'must-revalidate')
        ->header('Pragma', 'public')
        ->header('Content-Length', filesize($filePath))
        ->write(file_get_contents($filePath))
        ->send();
});






Flight::start();