<?php
require_once realpath(dirname(__FILE__)) . '/vendor/autoload.php';

/* settings */
define('ROOT_PATH', '/');
define('ROOT_EXPORT_FILE_PATH', '/database/');
define('EXPORT_FILE_PATH', realpath(dirname(__FILE__)) . '/..' . ROOT_EXPORT_FILE_PATH);

/* 定義ファイル名 */
define('DATABASE_DEFINITION_JSON_FILE', 'database.json');
define('TABLE_LOGICAL_CSV_FILE', 'def_tables.csv');
define('TABLE_LOGICAL_CSV_COLUMNS', ['物理名', '論理名', '定義情報', 'comment', 'bgcolor']);
define('COMMON_FIELD_LOGICAL_CSV_FILE', 'def_common.csv');
define('COMMON_FIELD_LOGICAL_CSV_COLUMNS', ['物理名', '論理名', 'comment']);
define('DETAIL_FIELD_LOGICAL_CSV_FILE', 'def_fields.csv');
define('DETAIL_FIELD_LOGICAL_CSV_COLUMNS', ['テーブル名', '物理名', '論理名', 'comment', 'bgcolor']);

define('CSRF_TOKEN_NAME', 'csrf_token');

/* Flight settings */
Flight::set('flight.views.path', realpath(dirname(__FILE__)) .'/Views');
Flight::set('flight.log_errors', true);

/* Class-related */
require_once realpath(dirname(__FILE__)) . '/Class/Utils.php';
require_once realpath(dirname(__FILE__)) . '/Class/Bind.php';
require_once realpath(dirname(__FILE__)) . '/Class/File.php';
require_once realpath(dirname(__FILE__)) . '/Class/Excel.php';
require_once realpath(dirname(__FILE__)) . '/Class/Controllers/Pages.php';
require_once realpath(dirname(__FILE__)) . '/Class/Databases/MYSQL.php';

/* function */
session_start();