<?php

class Pages
{
    /** @var array $assign */
    public $assign;

    /** @var array $menu */
    public $menuHandler;

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

        $this->menuHandler = [
            'tables_Edit' => null,
            'common_Field_Edit' => null
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

                $file = $objFile->read(TABLE_LOGICAL_CSV_FILE);
                $lines = explode("\n", $file);
                $lineCount = count($lines);
                $maxInputVars = ini_get('max_input_vars');

                // メニュー判定
                if ($lineCount * 5 > $maxInputVars) {
                    $this->menuHandler['tables_Edit'] = $lineCount * 3;
                }

            }
            if ($objFile->has(COMMON_FIELD_LOGICAL_CSV_FILE)) {
                $this->assign['files']['common'] = ROOT_EXPORT_FILE_URL . COMMON_FIELD_LOGICAL_CSV_FILE;

                $file = $objFile->read(COMMON_FIELD_LOGICAL_CSV_FILE);
                $lines = explode("\n", $file);
                $lineCount = count($lines);
                $maxInputVars = ini_get('max_input_vars');

                // メニュー判定
                if ($lineCount * 3 > $maxInputVars) {
                    $this->menuHandler['common_Field_Edit'] = $lineCount * 3;
                }

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
                                $unique[$line[1]]++;
                                $line[1] = $line[1] . '#' . $unique[$line[1]];
                                //throw new Exception('Tables definition file の論理名の設定が重複しています。');
                            } else {
                                if (!empty($line[1])) {
                                    $unique[$line[1]] = 1;
                                }
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

                        /*** Fields definition fileへも反映 */
                        $commonFields = [];
                        $commonFile = $objFile->read(COMMON_FIELD_LOGICAL_CSV_FILE);
                        $commonLines = explode("\n", $commonFile);
                        foreach ($commonLines as $line) {
                            $arrLine = str_getcsv($line);
                            if (isset($arrLine[0])) {
                                $commonFields[$arrLine[0]] = $arrLine;
                            }
                        }
                        if ($objFile->has(DETAIL_FIELD_LOGICAL_CSV_FILE)) {
                            $arrCsv = [];
                            $detailFile = $objFile->read(DETAIL_FIELD_LOGICAL_CSV_FILE);
                            $detailLines = explode("\n", $detailFile);
                            foreach ($detailLines as $line) {
                                $arrLine = str_getcsv($line);
                                if (isset($arrLine[1])) {
                                    if (isset($commonFields[$arrLine[1]])) {
                                        $updateLine = $commonFields[$arrLine[1]];
                                        $arrLine[2] = empty($arrLine[2]) ? $updateLine[1] : $arrLine[2];
                                        $arrLine[3] = empty($arrLine[3]) ? $updateLine[2] : $arrLine[3];
                                    }
                                    $arrCsv[] = $arrLine;
                                }
                            }
                            try {
                                $objFile->bindCsv(DETAIL_FIELD_LOGICAL_CSV_FILE, $arrCsv);
                            } catch (Exception $e) {
                                $this->assign['errors'][] = 'failed: ' . $e->getMessage() . '[' . $e->getLine() . ']';
                            }
                        }
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
     * テーブルの設定
     *
     * @return void
     */
    public function additionTables()
    {

        $objFile = new File();
        $this->assign['tables'] = [];

        if ($objFile->has(TABLE_LOGICAL_CSV_FILE)) {
            $datas = [];
            $file = $objFile->read(TABLE_LOGICAL_CSV_FILE);

            $fileHandle = fopen('php://memory', 'r+');
            fwrite($fileHandle, $file);
            rewind($fileHandle);

            while (($line = fgetcsv($fileHandle)) !== false) {
                $datas[] = $line;
            }

            fclose($fileHandle);

            $this->assign['tables'] = $datas;
        }

        $request = Flight::request();

        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                try {
                    $postData = $request->data->getData();
                    $tables = $postData['tables'] ?? [];

                    if (!empty($tables)) {

                        array_unshift($tables, $datas[0]);

                        $objBind = new Bind();
                        $objFile->bindCsv(TABLE_LOGICAL_CSV_FILE, $tables);
                        $objBind = new Bind();
                        $objBind->setDefinition($this->assign['database']);
                        $objFile->bindFile(DATABASE_DEFINITION_JSON_FILE, json_encode($this->assign['database'], JSON_UNESCAPED_UNICODE));
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
     * 共通フィールドの設定
     *
     * @return void
     */
    public function additionCommonField()
    {

        $objFile = new File();
        $this->assign['fields'] = [];

        if ($objFile->has(COMMON_FIELD_LOGICAL_CSV_FILE)) {
            $datas = [];
            $file = $objFile->read(COMMON_FIELD_LOGICAL_CSV_FILE);

            $fileHandle = fopen('php://memory', 'r+');
            fwrite($fileHandle, $file);
            rewind($fileHandle);

            while (($line = fgetcsv($fileHandle)) !== false) {
                $datas[] = $line;
            }

            fclose($fileHandle);

            $this->assign['fields'] = $datas;
        }

        $request = Flight::request();

        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {
                try {
                    $postData = $request->data->getData();
                    $fields = $postData['fields'] ?? [];

                    if (!empty($fields)) {

                        array_unshift($fields, $datas[0]);

                        $objBind = new Bind();
                        $objFile->bindCsv(COMMON_FIELD_LOGICAL_CSV_FILE, $fields);
                        $objBind = new Bind();
                        $objBind->setDefinition($this->assign['database']);
                        $objFile->bindFile(DATABASE_DEFINITION_JSON_FILE, json_encode($this->assign['database'], JSON_UNESCAPED_UNICODE));
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
     * 定義書のエクスポート・ダウンロード
     *
     * @return void
     */
    public function inout()
    {
        $request = Flight::request();
        if ($request->method == 'POST') {
            if (Utils::validCSRF()) {

                try {
                    $objFile = new File();

                    if ($_FILES['zipFile']['tmp_name']) {

                        $tmpDir = 'tmp/';

                        if (!$objFile->has($tmpDir)) {
                            $objFile->bindDir($tmpDir);
                        } 

                        $zip = new \ZipArchive();

                        if ($zip->open($_FILES['zipFile']['tmp_name']) === true) {
                            $zip->extractTo(EXPORT_FILE_PATH . $tmpDir);
                            $zip->close();
                        } else {
                            throw new Exception('ZIPファイルを開けませんでした。');
                        }

                        $targetFiles = [DATABASE_DEFINITION_JSON_FILE, TABLE_LOGICAL_CSV_FILE, COMMON_FIELD_LOGICAL_CSV_FILE, DETAIL_FIELD_LOGICAL_CSV_FILE];
                        $missingFiles = [];

                        foreach ($targetFiles as $file) {
                            $sourcePath = $tmpDir . $file;
                            if (!$objFile->has($sourcePath)) {
                                $missingFiles[] = $file;
                            }
                        }

                        if (!empty($missingFiles)) {
                            throw new Exception('必要なファイルが見つかりませんでした: ' . implode(', ', $missingFiles));
                        }
                        foreach ($targetFiles as $file) {
                            $sourcePath = $tmpDir . $file;
                            $objFile->move($sourcePath, $file);
                        }
                        $objFile->deleteDir($tmpDir);
                    }

                    if (empty($this->errors)) {
                        Utils::setMessage(['success: 定義ファイルのインポートが完了しました。']);
                        $this->assign['reload'] = true;
                    }
                } catch (Exception $e) {
                    $this->assign['errors'][] = 'failed: ' . $e->getMessage();
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
     * 定義ファイルのダウンロード
     *
     * @return void
     */
    public function download()
    {
        try {
            $zip = new \ZipArchive();
            $day = new \DateTime();
            $zipFileName = 'database_' . $day->format('YmdGis') . '.zip';

            $tmpFile = tempnam(sys_get_temp_dir(), 'zip');

            if ($zip->open($tmpFile, ZipArchive::OVERWRITE) === true) {
                $handle = opendir(EXPORT_FILE_PATH);

                while (false !== ($file = readdir($handle))) {
                    if (preg_match('/\.json$|\.csv$/i', $file)) {
                        $zip->addFile(EXPORT_FILE_PATH . $file, $file);
                    }
                }

                $zip->close();
                closedir($handle);

                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename=' . $zipFileName);
                header('Content-Length: ' . filesize($tmpFile));
                readfile($tmpFile);

                unlink($tmpFile);
                exit;

            } else {
                throw new Exception('Could not create ZIP file');
            }
        } catch (Exception $e) {
            $errors[] = 'Connection failed: ' . $e->getMessage();
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
