<?php

class Bind
{
    /**
     * __construct
     */
    public function __construct()
    {
    }

    public function initial($datas = [])
    {

        $databases = [
            'title' => $datas['title'],
            'comment' => $datas['comment'],
            'database' => $datas['dbname'] ?? '',
            'version' => $datas['version'] ?? '',
            'lock' => $datas['lock'] ?? '',
            'tables' => [],
        ];

        /** Jsonの設定情報 */
        $already = [];

        if (!empty($datas['tables'])) {
            foreach ($datas['tables'] as $key => $table) {
                $already[$table['table']] = $table;
            }
        }

        try {
            if ($datas['type'] == 'mysql') {
                $objDb = new MYSQL();
            }
            else if ($datas['type'] == 'mssql') {
                $objDb = new MSSQL();
            }


            $objDb->connect($datas);
            $tables = [];
            $tables = $objDb->tables();

            foreach ($tables as $table) {

                $fields = [];
                foreach ($objDb->fields($table['name']) as $field) {

                    /** Jsonの設定がある場合は情報を引き継ぐ */
                    $name = '';
                    $comment = '';
                    $bgcolor = '';
                    if (isset($already[$table['name']]['columns'])) {
                        foreach ($already[$table['name']]['columns'] as $item) {
                            if ($field['field'] == $item['field']) {
                                $name = $item['name'] ?? '';
                                $comment = $item['comment'] ?? '';
                                $bgcolor = $item['bgcolor'] ?? '';
                            }
                        }
                    }

                    $fields[] = [
                        'name'    => $name,
                        'field'   => $field['field'],
                        'type'    => $field['type'],
                        'null'    => $field['null'],
                        'key'     => $field['key'],
                        'default' => $field['default'] ,
                        'extra'   => $field['extra'],
                        'comment' => $comment ? $comment : $field['comment'],
                        'bgcolor' => $bgcolor,
                    ];
                }
                
                $indexs = [];
                foreach ($objDb->indexs($table['name']) as $index) {
                    $indexs[] = [
                        'name' => $index['name'],
                        'column' => $index['column'],
                        'comment' => $index['comment'],
                        'primary' => $index['primary'],
                        'unique' => $index['unique'],
                    ];
                }

                /** Jsonの設定がある場合は情報を引き継ぐ */
                $name = '';
                $definition = '';
                $comment = '';
                $bgcolor = '';
                $order = '';
                if (isset($already[$table['name']])) {
                    $item = $already[$table['name']];
                    $name = $item['name'] ?? '';
                    $definition = $item['definition'] ?? '';
                    $comment = $item['comment'] ?? '';
                    $bgcolor = $item['bgcolor'] ?? '';
                    $order = $item['order'] ?? '';
                }

                $databases['tables'][$table['name']] = [
                    'table' => $table['name'],
                    'name' => $name,
                    'definition' => $definition,
                    'comment' => $comment ?? $table['comment'],
                    'bgcolor' => $bgcolor,
                    'order' => $order,
                    'columns' => $fields,
                    'indexs' => $indexs,
                ];
            }

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $databases;
    }


    public function setDefinition(&$datas = [])
    {

        $tables = [];

        try {

            $objFile = new File();
            
            if ($objFile->has(TABLE_LOGICAL_CSV_FILE)) {

                $file = $objFile->read(TABLE_LOGICAL_CSV_FILE);

                $fileHandle = fopen('php://memory', 'r+');
                fwrite($fileHandle, $file);
                rewind($fileHandle);

                $order = 0;
                while (($arrLine = fgetcsv($fileHandle)) !== false) {
                    if (isset($arrLine[0])) {
                        $tables[$arrLine[0]] = $arrLine;
                        $tables[$arrLine[0]]['order'] = $order++;
                    }
                }
                fclose($fileHandle);

                foreach ($datas['tables'] as &$table) {
                    if (isset($tables[$table['table']])) {
                        $table['name'] = $tables[$table['table']][1] ?? '';
                        $table['definition'] = $tables[$table['table']][2] ?? '';
                        $table['comment'] = $tables[$table['table']][3] ?? '' . $table['comment'];
                        $table['bgcolor'] = $tables[$table['table']][4] ?? '';
                        $table['order'] = $tables[$table['table']]['order'] ?? '';
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        try {
            $fields = [];
            if ($objFile->has(COMMON_FIELD_LOGICAL_CSV_FILE)) {

                $lines = [];
                $file = $objFile->read(COMMON_FIELD_LOGICAL_CSV_FILE);

                $fileHandle = fopen('php://memory', 'r+');
                fwrite($fileHandle, $file);
                rewind($fileHandle);

                while (($line = fgetcsv($fileHandle)) !== false) {
                    $lines[] = $line;
                }

                fclose($fileHandle);

                foreach ($lines as $line) {
                    if (isset($line[0])) {
                        $fields[$line[0]] = $line;
                    }
                }
                foreach ($datas['tables'] as &$table) {
                    foreach ($table['columns'] as &$column) {
                        if (isset($fields[$column['field']])) {
                            $column['name'] = $fields[$column['field']][1] ?? '';
                            $column['comment'] = $fields[$column['field']][2] ?? '';
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }


        try {
            $detaills = [];
            if ($objFile->has(DETAIL_FIELD_LOGICAL_CSV_FILE)) {

                $file = $objFile->read(DETAIL_FIELD_LOGICAL_CSV_FILE);
                $lines = explode("\n", $file);
                $order = 0;
                foreach ($lines as $line) {
                    $arrLine = str_getcsv($line);
                    if (isset($arrLine[0]) && isset($arrLine[0])) {
                        $detaills[$arrLine[0]][$arrLine[1]] = $arrLine;
                        $detaills[$arrLine[0]][$arrLine[1]]['order'] = $order++;
                    }
                }

                foreach ($datas['tables'] as &$table) {
                    foreach ($table['columns'] as &$column) {
                        $detaill = [];

                        if (isset($detaills[$table['table']][$column['field']])) {

                            $detaill = $detaills[$table['table']][$column['field']];

                            // 論理名セット
                            if (isset($detaill[2]) && !empty($detaill[2])) {
                                $column['name'] = $detaill[2] ?? '';
                            }
                            // commentセット
                            if (isset($detaill[3]) && !empty($detaill[3])) {
                                $column['comment'] = $detaill[3] ?? '';
                            }
                            // styleセット
                            if (isset($detaill[4]) && !empty($detaill[4])) {
                                $column['bgcolor'] = $detaill[4] ?? '';
                            }

                            $column['order'] = $detaill['order'] ?? '';
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        /** テーブルの並び順を調整 */
        usort($datas['tables'], function ($a, $b) {
            $orderA = isset($a['order']) ? intval($a['order']) : 0;
            $orderB = isset($b['order']) ? intval($b['order']) : 0;
            return $orderA - $orderB;
        });
        /** フィールドの並び順を調整 */
        foreach ($datas['tables'] as &$tables) {
            usort($tables['columns'], function ($a, $b) {
                $orderA = isset($a['order']) ? intval($a['order']) : 0;
                $orderB = isset($b['order']) ? intval($b['order']) : 0;
                return $orderA - $orderB;
            });
        }

    }

    public function logicalTables($datas = [])
    {
        $result[] = TABLE_LOGICAL_CSV_COLUMNS;

        foreach ($datas['tables'] as $table) {
            $result[] = [
                $table['table'], $table['name'], $table['definition'], $table['comment'], $table['bgcolor']
            ];
        }
        return $result;
    }

    public function logicalFields($datas = [])
    {
        $result[] = COMMON_FIELD_LOGICAL_CSV_COLUMNS;

        foreach ($datas['tables'] as $table) {
            foreach ($table['columns'] as $column) {
                $result[$column['field']] = [$column['field'], $column['name'], ''];
            }
        }
        return $result;
    }

    public function logicalDetailFields($datas = [])
    {
        $result[] = DETAIL_FIELD_LOGICAL_CSV_COLUMNS;

        foreach ($datas['tables'] as $table) {
            foreach ($table['columns'] as $column) {
                $result[] = [$table['table'], $column['field'], $column['name'], $column['comment'], $column['bgcolor']];
            }
        }
        return $result;
    }
}
