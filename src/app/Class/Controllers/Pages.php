<?php

class Pages
{
    /** @var array $assign */
    public $assign;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->assign = [
            'datas' => [],
            'successes' => [],
            'errors' => [],
            'created' => false,
            'locked' => false,
            'database' => [],
            'reload' => false,
            'files' => [
                'database' => '',
                'tables' => '',
                'common' => '',
                'fields' => '',
            ],
        ];
        $successes = Utils::getMessage();
        if (!empty($successes)) {
            $this->assign['successes'] = $successes;
        }
        try {
            $objFile = new File();
            if ($objFile->has(DATABASE_DEFINITION_JSON_FILE)) {
                $json = $objFile->read(DATABASE_DEFINITION_JSON_FILE);
                if ($json) {
                    $this->assign['database'] = json_decode($json, true);
                    $this->assign['created'] =  true;
                    if ($this->assign['database']['lock']) {
                        $this->assign['locked'] =  true;
                    }
                }
                $this->assign['files']['database'] = ROOT_EXPORT_FILE_URL . DATABASE_DEFINITION_JSON_FILE;
            }
            if ($objFile->has(TABLE_LOGICAL_CSV_FILE)) {
                $this->assign['files']['tables'] = ROOT_EXPORT_FILE_URL . TABLE_LOGICAL_CSV_FILE;
            }
            if ($objFile->has(COMMON_FIELD_LOGICAL_CSV_FILE)) {
                $this->assign['files']['common'] = ROOT_EXPORT_FILE_URL . COMMON_FIELD_LOGICAL_CSV_FILE;
            }
            if ($objFile->has(DETAIL_FIELD_LOGICAL_CSV_FILE)) {
                $this->assign['files']['fields'] = ROOT_EXPORT_FILE_URL . DETAIL_FIELD_LOGICAL_CSV_FILE;
            }
        } catch (Exception $e) {
            $this->errors[] = 'Connection failed: ' . $e->getMessage();
        }
    }
    /**
     * DB接続
     *
     * @return void
     */
    public function connect()
    {
        $request = Flight::request();
        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                $this->assign['datas'] = $request->data->getData();
                $this->assign['datas']['title'] = $this->assign['database']['title'] ?? '';
                $this->assign['datas']['comment'] = $this->assign['database']['comment'] ?? '';
                $this->assign['datas']['version'] = $this->assign['database']['version'] ?? '';
                $this->assign['datas']['tables'] = $this->assign['database']['tables'] ?? [];

                try {
                    $objBind = new Bind();
                    $tables = $objBind->initial($this->assign['datas']);
                    $objBind->setDefinition($tables);
                    try {
                        $objFile = new File();
                        $objFile->bindFile(DATABASE_DEFINITION_JSON_FILE, json_encode($tables, JSON_UNESCAPED_UNICODE));
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                    }
                } catch (Exception $e) {
                    $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                }
                if (empty($this->assign['errors'])) {
                    $this->assign['created'] =  true;
                    try {
                        $arrCsv = $objBind->logicalTables($tables);
                        $objFile->bindCsv(TABLE_LOGICAL_CSV_FILE, $arrCsv);
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                    }
                }
                if (empty($this->assign['errors'])) {
                    try {
                        $arrCsv = $objBind->logicalFields($tables);
                        $objFile->bindCsv(COMMON_FIELD_LOGICAL_CSV_FILE, $arrCsv);
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                    }
                }
                if (empty($this->assign['errors'])) {
                    try {
                        $arrCsv = $objBind->logicalDetailFields($tables);
                        $objFile->bindCsv(DETAIL_FIELD_LOGICAL_CSV_FILE, $arrCsv);
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                    }
                }
                if (empty($this->assign['errors'])) {
                    Utils::setMessage(['success: データの更新が完了しました。']);
                    $this->assign['reload'] = true;
                }
            } else {
                $this->assign['errors'][] = 'failed: 不正なデータ送信がありました。';
            }
        }
    }

    /**
     * 基本情報の設定
     *
     * @return void
     */
    public function general()
    {
        $request = Flight::request();
        if ($request->method == 'GET') {
            if (isset($this->assign['database']['title'])) {
                $this->assign['datas']['title'] = $this->assign['database']['title'];
            }
            if (isset($this->assign['database']['comment'])) {
                $this->assign['datas']['comment'] = $this->assign['database']['comment'];
            }
            if (isset($this->assign['database']['version'])) {
                $this->assign['datas']['version'] = $this->assign['database']['version'];
            }
            $this->assign['datas']['lock'] = '';
            if (isset($this->assign['database']['lock'])) {
                $this->assign['datas']['lock'] = $this->assign['database']['lock'];
            }
        }
        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                $this->assign['datas'] = $request->data->getData();
                try {
                    if (isset($this->assign['datas']['title'])) {
                        $this->assign['database']['title'] = $this->assign['datas']['title'];
                    }
                    if (isset($this->assign['datas']['comment'])) {
                        $this->assign['database']['comment'] = $this->assign['datas']['comment'];
                    }
                    if (isset($this->assign['datas']['version'])) {
                        $this->assign['database']['version'] = $this->assign['datas']['version'];
                    }
                    if (isset($this->assign['datas']['lock'])) {
                        $this->assign['database']['lock'] = $this->assign['datas']['lock'];
                    } else {
                        $this->assign['database']['lock'] = '';
                    }
                    try {
                        $objFile = new File();
                        $objFile->bindFile(DATABASE_DEFINITION_JSON_FILE, json_encode($this->assign['database'], JSON_UNESCAPED_UNICODE));
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage();
                    }
                } catch (Exception $e) {
                    $this->assign['errors'][] = 'failed: ' . $e->getMessage();
                }
                if (empty($this->assign['errors'])) {
                    Utils::setMessage(['success: データの更新が完了しました。']);
                    $this->assign['reload'] = true;
                }
            } else {
                $this->assign['errors'][] = 'failed: 不正なデータ送信がありました。';
            }
        }
    }

    /**
     * 論理名等の情報を設定
     *
     * @return void
     */
    public function addition()
    {
        $request = Flight::request();
        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                try {
                    $objFile = new File();
                    if ($_FILES['tablesFile']['tmp_name']) {
                        $filer = fopen($_FILES['tablesFile']['tmp_name'], 'r');
                        $datas = [];
                        $unique = [];
                        while ($line = fgetcsv($filer)) {
                            if (count($line) !== count(TABLE_LOGICAL_CSV_COLUMNS)) {
                                throw new Exception('Tables definition file のフォーマットが違います。');
                            }

                            if (isset($unique[$line[1]])) {
                                throw new Exception('Tables definition file の論理名の設定が重複しています。');
                            } else {
                                $unique[$line[1]] = $line[0];
                            }

                            $datas[] = $line;
                        }
                        fclose($filer);
                        $objFile->bindCsv(TABLE_LOGICAL_CSV_FILE, $datas);
                    }
                    if ($_FILES['commonFile']['tmp_name']) {
                        $filer = fopen($_FILES['commonFile']['tmp_name'], 'r');
                        $datas = [];
                        while ($line = fgetcsv($filer)) {
                            if (count($line) !== count(COMMON_FIELD_LOGICAL_CSV_COLUMNS)) {
                                throw new Exception('Fields Common definition file のフォーマットが違います。');
                            }
                            $datas[] = $line;
                        }
                        fclose($filer);
                        $objFile->bindCsv(COMMON_FIELD_LOGICAL_CSV_FILE, $datas);
                    }
                    if ($_FILES['fieldsFile']['tmp_name']) {
                        $filer = fopen($_FILES['fieldsFile']['tmp_name'], 'r');
                        $datas = [];
                        while ($line = fgetcsv($filer)) {
                            if (count($line) !== count(DETAIL_FIELD_LOGICAL_CSV_COLUMNS)) {
                                throw new Exception('Fields Common definition file のフォーマットが違います。');
                            }
                            $datas[] = $line;
                        }
                        fclose($filer);
                        $objFile->bindCsv(DETAIL_FIELD_LOGICAL_CSV_FILE, $datas);
                    }
                    $objBind = new Bind();
                    $objBind->setDefinition($this->assign['database']);
                    try {
                        $objFile->bindFile(DATABASE_DEFINITION_JSON_FILE, json_encode($this->assign['database'], JSON_UNESCAPED_UNICODE));
                    } catch (Exception $e) {
                        $this->assign['errors'][] = 'failed: ' . $e->getMessage();
                    }
                } catch (Exception $e) {
                    $this->assign['errors'][] = 'failed: ' . $e->getMessage();
                }
                if (empty($this->assign['errors'])) {
                    Utils::setMessage(['success: データの更新が完了しました。']);
                    $this->assign['reload'] = true;
                }
            } else {
                $this->assign['errors'][] = 'failed: 不正なデータ送信がありました。';
            }
        }
    }

    /**
     * 定義書の削除
     *
     * @return void
     */
    public function delete()
    {
        $request = Flight::request();
        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                try {
                    $objFile = new File();
                    $objFile->deletes([
                        DETAIL_FIELD_LOGICAL_CSV_FILE,
                        COMMON_FIELD_LOGICAL_CSV_FILE,
                        TABLE_LOGICAL_CSV_FILE,
                        DATABASE_DEFINITION_JSON_FILE,
                    ]);
                    $this->assign['created'] =  false;
                } catch (Exception $e) {
                    $this->assign['errors'][] = 'failed: ' . $e->getMessage();
                }
                if (empty($this->errors)) {
                    Utils::setMessage(['success: データの削除が完了しました。']);
                    $this->assign['reload'] = true;
                }
            } else {
                $this->assign['errors'][] = 'failed: 不正なデータ送信がありました。';
            }
        }
    }

    /**
     * エクセルで書き出し
     *
     * @return void
     */
    public function export()
    {
        try {
            $objExcel = new Excel();
            $objExcel->bindExcel($this->assign['database']);
        } catch (Exception $e) {
            $errors[] = 'Connection failed: ' . $e->getMessage();
        }
    }
}
