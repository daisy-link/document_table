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

            $file = fopen(EXPORT_FILE_PATH . $name, 'w');

            fwrite($file, "\xEF\xBB\xBF");

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
