<?php

class MSSQL
{

    private $conn;

    private $database;

    public function __construct()
    {
        $this->conn = null;
        $this->database = null;
    }

    public function connect($datas = [])
    {
        try {

            $server = $datas['dbhost'];
            if (!empty($datas['dbport'])) {
                $server .= ',' . $datas['dbport'];
            }
            $dsn = 'sqlsrv:server=' . $server . ';Database=' . $datas['dbname'] . ';TrustServerCertificate=true;';
            $dbuser = $datas['dbuser'];
            $dbpass = $datas['dbpass'];

            // PDO のオプションを追加してタイムアウトを設定
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーモードを例外に設定
                // PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 60,       // クエリのタイムアウトを 60 秒に設定
                // PDO::ATTR_TIMEOUT => 60,                    // 接続タイムアウトを 60 秒に設定
            ];

            $this->conn = new PDO($dsn, $dbuser, $dbpass, $options);

        } catch (FlyFileNotFoundException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function tables()
    {
        $tables = [];

        try {
            $sql = $this->conn->prepare("
                SELECT
                    t.TABLE_SCHEMA + '.' + t.TABLE_NAME AS name,
                    ep.value AS comment
                FROM
                    information_schema.tables t
                LEFT JOIN
                    sys.extended_properties ep
                    ON ep.major_id = OBJECT_ID(t.TABLE_SCHEMA + '.' + t.TABLE_NAME)
                    AND ep.minor_id = 0
                    AND ep.name = 'MS_Description'
                WHERE
                    t.table_type = 'BASE TABLE'
            ");
            $sql->execute();

            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $tables[$row['name']] = [
                    'name' => $row['name'],
                    'comment' => $row['comment'] ?? '',  // テーブルコメントを設定
                ];
            }
        } catch (PDOException $e) {
            throw new Exception('GET Tables List : ' . $e->getMessage());
        }

        return $tables;
    }

    public function fields($table = '')
    {
        $fields = [];
    
        try {
            // テーブル名からスキーマ名とテーブル名を分ける
            $parts = explode('.', $table);
            if (count($parts) === 2) {
                $schema = $parts[0];
                $tableName = $parts[1];
            } else {
                throw new Exception('Invalid table format. Use schema.table format.');
            }
    
            $sql = $this->conn->prepare("
                SELECT 
                    c.COLUMN_NAME AS Field, 
                    c.DATA_TYPE AS Type, 
                    CASE 
                        WHEN c.IS_NULLABLE = 'YES' THEN 'YES'
                        ELSE 'NO'
                    END AS isNull,
                    c.CHARACTER_MAXIMUM_LENGTH AS max_length,
                    c.COLUMN_DEFAULT AS isDefault,
                    ep.value AS Comment,
                    i.name AS IndexName,
                    CASE
                        WHEN i.is_primary_key = 1 THEN 'PRI'
                        WHEN i.is_unique = 1 THEN 'UNI'
                        ELSE ''
                    END AS KeyType
                FROM information_schema.columns c
                LEFT JOIN sys.columns sc
                    ON c.TABLE_NAME = OBJECT_NAME(sc.object_id)
                    AND c.COLUMN_NAME = sc.name
                LEFT JOIN sys.extended_properties ep
                    ON sc.object_id = ep.major_id
                    AND sc.column_id = ep.minor_id
                    AND ep.class = 1
                    AND ep.name = 'MS_Description'
                LEFT JOIN sys.index_columns ic
                    ON sc.object_id = ic.object_id
                    AND sc.column_id = ic.column_id
                LEFT JOIN sys.indexes i
                    ON ic.object_id = i.object_id
                    AND ic.index_id = i.index_id
                WHERE c.table_schema = :schema_name 
                  AND c.table_name = :table_name
            ");
            $sql->bindParam(':schema_name', $schema, PDO::PARAM_STR);
            $sql->bindParam(':table_name', $tableName, PDO::PARAM_STR);
            $sql->execute();
    
            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                // max_length が -1 の場合には '(MAX)' を設定
                $maxLength = ($row['max_length'] == -1) ? 'MAX' : $row['max_length'];
    
                // max_length がある場合に Type に追加
                $typeWithLength = $row['Type'];
                if (!empty($maxLength)) {
                    $typeWithLength .= "({$maxLength})";
                }
    
                // デフォルト値の修正
                $default = $row['isDefault'];
                if ($default === null) {
                    $default = '';
                } else {
                    $default = trim($default, '()'); // 括弧を削除
                }
    
                $fields[] = [
                    'field' => $row['Field'],
                    'type' => $typeWithLength,
                    'null' => $row['isNull'],
                    'default' => $default,
                    'max_length' => ($row['max_length'] == -1) ? 'MAX' : $row['max_length'],
                    'comment' => $row['Comment'] ?? '',
                    'key' => $row['KeyType'] ?? '', // KeyType を設定
                    'extra' => $row['Extra'] ?? '',
                ];
            }
        } catch (PDOException $e) {
            throw new Exception('GET Fields List : ' . $e->getMessage());
        }
    
        return $fields;
    }

    public function indexs($table = '')
    {
        $indexs = [];
    
        try {
            // テーブル名からスキーマ名とテーブル名を分ける
            $parts = explode('.', $table);
            if (count($parts) === 2) {
                $schema = $parts[0];
                $tableName = $parts[1];
            } else {
                throw new Exception('Invalid table format. Use schema.table format.');
            }
    
            // MSSQLのインデックス情報を取得するクエリ
            $sql = $this->conn->prepare("
                SELECT
                    i.name AS [name],
                    c.name AS [column],
                    i.is_primary_key AS [primary],
                    i.is_unique AS [unique]
                FROM sys.indexes i
                INNER JOIN sys.index_columns ic
                    ON i.object_id = ic.object_id
                    AND i.index_id = ic.index_id
                INNER JOIN sys.columns c
                    ON ic.object_id = c.object_id
                    AND ic.column_id = c.column_id
                WHERE i.object_id = OBJECT_ID(:schema_name + '.' + :table_name)
                ORDER BY i.name, ic.key_ordinal
            ");
            $sql->bindParam(':schema_name', $schema, PDO::PARAM_STR);
            $sql->bindParam(':table_name', $tableName, PDO::PARAM_STR);
            $sql->execute();
    
            foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $indexs[] = [
                    'name' => $row['name'] ?? '',
                    'column' => $row['column'] ?? '',
                    'comment' => '', // MSSQLではインデックスコメントを直接取得するのが難しいため、空にしています
                    'primary' => ($row['primary'] == 1) ? 1 : '',
                    'unique' => ($row['unique'] == 1) ? 1 : '',
                ];
            }
        } catch (PDOException $e) {
            throw new Exception('GET Indexs List : ' . $e->getMessage());
        }
    
        return $indexs;
    }
}
