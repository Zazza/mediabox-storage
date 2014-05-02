<?php
namespace Fm\Components;

class Files extends Base {
    public $path;
    public $name;

    public function set($id) {
        $dir = $this->_app["upload"];
        $handle = opendir($dir);
        if($handle) {
            while(true == ($dir = readdir($handle))) {
                if($dir!='.' && $dir!='..')
                    if(mb_strpos($dir, "[" . $id . "]") === 0) {
                        $this->path = $this->_app["upload"] . $dir;
                        $this->name = mb_substr($dir, mb_strlen("[" . $id . "]")+1, -1);
                    }
            }
        }
    }

    public function rmFiles() {
        if (unlink($this->path)) {
            return true;
        } else {
            return false;
        }
    }

    public function streaming($mimetype = 'application/octet-stream') {
        $fsize = filesize($this->path);
        $ftime = date('D, d M Y H:i:s T', filemtime($this->path));

        $fd = @fopen($this->path, 'rb');

        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            $range = str_replace('bytes=', '', $range);
            list($range, $end) = explode('-', $range);

            if (!empty($range)) {
                fseek($fd, $range);
            }
        } else {
            $range = 0;
        }

        if ($range) {
            header($_SERVER['SERVER_PROTOCOL'].' 206 Partial Content');
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        }

        header('Content-Disposition: attachment; filename='.urlencode($this->name));
        header('Last-Modified: '.$ftime);
        header('Accept-Ranges: bytes');
        header('Content-Length: '.($fsize - $range));
        if ($range) {
            header("Content-Range: bytes $range-".($fsize - 1).'/'.$fsize);
        }
        header('Content-Type: '.$mimetype);

        $downloaded = 0;

        while (!feof($fd) && !connection_status() && ($downloaded < $fsize)) {
            echo fread($fd, 512000);
            $downloaded += 512000;
            flush();
        }

        fclose($fd);

        exit;
    }
}
