<?php

class MYSQL
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
            $dsn = 'mysql:host=' . $datas['dbhost'] . ';';
            if (!empty($datas['dbport'])) {
                $dsn .= 'port=' . $datas['dbport'] . ';';
            }
            $dsn .= 'dbname=' . $datas['dbname'] . ';charset=utf8';
            $dbuser = $datas['dbuser'];
            $dbpass = $datas['dbpass'];

            $this->database = $datas['dbname'];
            $this->conn = new PDO($dsn, $dbuser, $dbpass);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function tables()
    {
        $tables = [];

        try {

            $sql = $this->conn->prepare('show tables');
            $sql->execute();

            foreach ($sql->fetchAll() as $row) {

                $tables[$row[0]]['name'] = $row[0];

                // テーブルコメントを取得
                $tableName = $row['Tables_in_' . $this->database];
                $sql = $this->conn->prepare("SHOW TABLE STATUS LIKE '$tableName'");
                $sql->execute();

                foreach ($sql->fetchAll() as $row) {
                    $tables[$row[0]]['comment']  = $row['Comment'];
                }
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

            $sql = $this->conn->prepare("SHOW FULL COLUMNS FROM $table");
            $sql->execute();

            foreach ($sql->fetchAll() as $row) {
                $fields[] = [
                    'field' => $row['Field'],
                    'type' => $row['Type'],
                    'null' => $row['Null'],
                    'key' => $row['Key'] ?? '',
                    'default' => $row['Default'] ?? '',
                    'extra' => $row['Extra'] ?? '',
                    'comment' => $row['Comment'] ?? '',
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

            $sql = $this->conn->prepare("SHOW INDEX FROM $table");
            $sql->execute();

            foreach ($sql->fetchAll() as $row) {
                $indexs[] = [
                    'name' => $row['Key_name'] ?? '',
                    'column' => $row['Column_name'] ?? '',
                    'comment' => $row['Comment'] ?? '',
                    'primary' => ($row['Key_name'] == 'PRIMARY') ? 1 : '',
                    'unique' => ($row['Non_unique'] === 0) ? 1 : '',
                ];
            }
        } catch (Exception $e) {
            throw new Exception('GET Indexs List : ' . $e->getMessage());
        }
        return $indexs;
    }
}
