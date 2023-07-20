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
    Flight::render('menu', ['created' => $objPages->assign['created']], 'settingMenu');
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
    Flight::render('menu', ['created' => $objPages->assign['created']], 'settingMenu');
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
    Flight::render('menu', ['created' => $objPages->assign['created']], 'settingMenu');
    Flight::render('addition', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Addition']);
});


Flight::route('GET|POST /setting/delete', function(){
    $objPages = new Pages();
    $objPages->delete();
    if ($objPages->assign['reload']) {
        Flight::redirect('/setting/delete');
    }
    Flight::render('menu', ['created' => $objPages->assign['created']], 'settingMenu');
    Flight::render('delete', ['assign' => $objPages->assign], 'mainContent');
    Flight::render('layout', ['title' => 'Delete']);
});


Flight::route('/export', function(){
    $objPages = new Pages();
    $objPages->export();
});






Flight::start();