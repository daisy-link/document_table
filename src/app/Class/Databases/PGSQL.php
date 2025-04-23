<?php

class PGSQL
{

    private $conn;

    private $database;

    private $schema;

    public function __construct()
    {
        $this->conn = null;
        $this->database = null;
        $this->schema = null;
    }

    public function connect($datas = [])
    {
        try {
            $dsn = 'pgsql:host=' . $datas['dbhost'] . ';';
            if (!empty($datas['dbport'])) {
                $dsn .= 'port=' . $datas['dbport'] . ';';
            }
            $dsn .= 'dbname=' . $datas['dbname'] . ';';
    
            $dbuser = $datas['dbuser'];
            $dbpass = $datas['dbpass'];
    
            $this->database = $datas['dbname'];

            $this->schema = $datas['schema'] ?? 'sales';

            $options = [
                PDO::ATTR_TIMEOUT => 10,
            ];
            $this->conn = new PDO($dsn, $dbuser, $dbpass, $options);
    
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function tables()
    {
        $tables = [];
    
        try {
            // テーブル一覧を取得
            $sql = $this->conn->prepare("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_schema = :schemaName
            ");
            $sql->bindValue(':schemaName', $this->schema, PDO::PARAM_STR);
            $sql->execute();
    
            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $tableName = $row['table_name'];
                $tables[$tableName]['name'] = $tableName;
    
                // テーブルコメントを取得
                $sqlComment = $this->conn->prepare("
                    SELECT obj_description(pg_class.oid) AS comment
                    FROM pg_class
                    WHERE relname = :tableName
                ");
                $sqlComment->bindValue(':tableName', $tableName, PDO::PARAM_STR);
                $sqlComment->execute();
    
                $commentRow = $sqlComment->fetch(PDO::FETCH_ASSOC);
                $tables[$tableName]['comment'] = $commentRow['comment'] ?? null; // コメントがない場合はnull
            }
        } catch (Exception $e) {
            throw new Exception('GET Tables List : ' . $e->getMessage());
        }
    
        return $tables;
    }

    public function fields($table = '')
    {
        $fields = [];
    
        try {
            // カラムの基本情報を取得
            $sql = $this->conn->prepare("
                SELECT 
                    c.column_name AS field,
                    c.data_type AS base_type,
                    c.character_maximum_length AS char_length,
                    c.numeric_precision AS num_precision,
                    c.numeric_scale AS num_scale,
                    c.is_nullable AS null,
                    c.column_default AS default,
                    CASE 
                        WHEN p.contype = 'p' THEN 'PRI'
                        WHEN p.contype = 'u' THEN 'UNI'
                        ELSE ''
                    END AS key,
                    CASE 
                        WHEN c.column_default LIKE 'nextval%' THEN 'auto_increment'
                        ELSE ''
                    END AS extra,
                    pg_catalog.col_description(format('%s.%s', c.table_schema, c.table_name)::regclass::oid, c.ordinal_position) AS comment
                FROM information_schema.columns c
                LEFT JOIN pg_catalog.pg_constraint p 
                    ON p.conrelid = format('%s.%s', c.table_schema, c.table_name)::regclass::oid 
                    AND c.ordinal_position = ANY (p.conkey)
                WHERE c.table_name = :table
                  AND c.table_schema = :schemaName
            ");
            $sql->bindValue(':schemaName', $this->schema, PDO::PARAM_STR);
            $sql->bindValue(':table', $table, PDO::PARAM_STR);
            $sql->execute();
    
            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                // データ型に桁数を含める
                $type = $row['base_type'];
                if ($row['char_length'] !== null) {
                    $type .= "({$row['char_length']})";
                } elseif ($row['num_precision'] !== null) {
                    $type .= "({$row['num_precision']}";
                    if ($row['num_scale'] !== null) {
                        $type .= ", {$row['num_scale']}";
                    }
                    $type .= ")";
                }

                $fields[] = [
                    'field' => $row['field'],
                    'type' => $type,
                    'null' => $row['null'] === 'YES' ? true : false,
                    'default' => $row['default'] ?? '',
                    'key' => $row['key'], // PRI または UNI
                    'extra' => $row['extra'],
                    'comment' => $row['comment'] ?? '',
                ];
            }
        } catch (Exception $e) {
            throw new Exception('GET Fields List : ' . $e->getMessage());
        }
    
        return $fields;
    }

    
    public function indexs($table = '')
    {
        $indexs = [];

        try {
            // テーブルのインデックス情報を取得
            $sql = $this->conn->prepare("
                SELECT 
                    i.relname AS name,
                    a.attname AS column,
                    ix.indisprimary AS primary,
                    ix.indisunique AS unique,
                    pg_catalog.obj_description(i.oid) AS comment
                FROM pg_catalog.pg_class t
                INNER JOIN pg_catalog.pg_index ix ON t.oid = ix.indrelid
                INNER JOIN pg_catalog.pg_class i ON ix.indexrelid = i.oid
                INNER JOIN pg_catalog.pg_attribute a ON t.oid = a.attrelid AND a.attnum = ANY(ix.indkey)
                WHERE t.relname = :table
                AND t.relkind = 'r'
            ");
            $sql->bindValue(':table', $table, PDO::PARAM_STR);
            $sql->execute();

            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $indexs[] = [
                    'name' => $row['name'],
                    'column' => $row['column'],
                    'comment' => $row['comment'] ?? '',
                    'primary' => $row['primary'] ? 1 : '',
                    'unique' => $row['unique'] ? 1 : '',
                ];
            }
        } catch (Exception $e) {
            throw new Exception('GET indexs List : ' . $e->getMessage());
        }

        return $indexs;
    }
}
