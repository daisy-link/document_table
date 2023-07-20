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

        try {

            // MYSQL
            $objDb = new MYSQL();
            $objDb->connect($datas);
            $tables = [];
            $tables = $objDb->tables();

            foreach ($tables as $table) {

                $fields = [];
                foreach ($objDb->fields($table['name']) as $field) {
                    $fields[] = [
                        'name'    => '',
                        'field'   => $field['field'],
                        'type'    => $field['type'],
                        'null'    => $field['null'],
                        'key'     => $field['key'],
                        'default' => $field['default'] ,
                        'extra'   => $field['extra'],
                        'comment' => $field['comment'],
                        'bgcolor' => '',
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
                $databases['tables'][$table['name']] = [
                    'table' => $table['name'],
                    'name' => '',
                    'definition' => '',
                    'comment' => $table['comment'],
                    'bgcolor' => '',
                    'order' => '',
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
                $lines = explode("\n", $file);
                foreach ($lines as $order => $line) {
                    $arrLine = str_getcsv($line);
                    if (isset($arrLine[0])) {
                        $tables[$arrLine[0]] = $arrLine;
                        $tables[$arrLine[0]]['order'] = $order;
                    }
                }

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

                $file = $objFile->read(COMMON_FIELD_LOGICAL_CSV_FILE);
                $lines = explode("\n", $file);
                foreach ($lines as $line) {
                    $arrLine = str_getcsv($line);
                    if (isset($arrLine[0])) {
                        $fields[$arrLine[0]] = $arrLine;
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
                foreach ($lines as $line) {
                    $arrLine = str_getcsv($line);
                    if (isset($arrLine[0]) && isset($arrLine[0])) {
                        $detaills[$arrLine[0]][$arrLine[1]] = $arrLine;
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
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        usort($datas['tables'], function ($a, $b) {
            $orderA = isset($a['order']) ? intval($a['order']) : 0;
            $orderB = isset($b['order']) ? intval($b['order']) : 0;
            return $orderA - $orderB;
        });
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
