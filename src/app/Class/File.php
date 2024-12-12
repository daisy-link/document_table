<?php

use League\Flysystem\Filesystem as FlyFilesystem;
use League\Flysystem\Local\LocalFilesystemAdapter as FlyLocal;

class File
{
    private $flyfiles;

    /**
     * __construct
     */
    public function __construct()
    {
        try {
            $this->flyfiles = new FlyFilesystem(new FlyLocal(EXPORT_FILE_PATH));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function has($name = '')
    {
        try {
            return $this->flyfiles->has($name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function bindDir($name = '')
    {
        try {
            $this->flyfiles->createDirectory($name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function move($from = '', $to = '')
    {
        try {
            if ($this->flyfiles->has($from)) {
                $this->flyfiles->move($from, $to);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function bindFile($name = '', $datas = '')
    {
        try {
            $this->flyfiles->write($name, $datas);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function bindCsv($name = '', $datas = [])
    {
        try {

            $hasBom = false;

            if ($this->flyfiles->has($name)) {
                $content = $this->flyfiles->read($name);
                $hasBom = (substr($content, 0, 3) === "\xEF\xBB\xBF");
            } else {
                // throw new Exception('指定されたファイルはありません ');
            }

            $file = fopen(EXPORT_FILE_PATH . $name, 'w');

            if (!$hasBom) {
                fwrite($file, "\xEF\xBB\xBF");
            }

            foreach ($datas as $row) {
                $row = array_map(function($value) {
                    return $value;
                }, $row);
                fputcsv($file, $row);
            }
            fclose($file);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function readCsvAsArray($name = '')
    {
        $rows = [];
        try {
            if ($this->flyfiles->has($name)) {
                // CSV ファイルの内容を取得
                $fileContent = $this->flyfiles->read($name);
    
                // メモリ上に一時ストリームを作成
                $stream = fopen('php://memory', 'r+');
                fwrite($stream, $fileContent);
                rewind($stream);
    
                // ストリームから行ごとに CSV を解析して配列に追加
                while (($arrLine = fgetcsv($stream)) !== false) {
                    $rows[] = $arrLine;
                }
    
                // ストリームを閉じる
                fclose($stream);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    
        return $rows; // 行ごとの配列を返す
    }

    public function read($name = '')
    {
        $content = '';
        try {
            if ($this->flyfiles->has($name)) {
                $content = $this->flyfiles->read($name);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $content;
    }

    public function deleteDir($name = '')
    {
        try {
            if ($this->flyfiles->directoryExists($name)) {
                $this->flyfiles->deleteDirectory($name);
            } else {
                throw new Exception('指定されたパスはディレクトリではありません ');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deletes($files = [])
    {
        try {
            foreach ($files as $file) {
                $this->flyfiles->delete($file);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
