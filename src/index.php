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
        Flight::redirect('/setting/connect');
    }
    $objPages->additionCommonField();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/addition/fields');
    }
    Flight::render('menu', ['created' => $objPages->assign['created'] , 'menuHandler' => $objPages->menuHandler], 'settingMenu');
    Flight::render('fields', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition - Common Fields Edit']);
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






Flight::start();