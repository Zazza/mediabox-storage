<?php
class Files extends Base {
    public $path;
    public $name;
    private $_parent;

    private $folders = array();
    private $files = array();
    private $_width = 143;
    private $_height = 80;

    private $filetypes = array(
        'bmp' => 'image',
        'jpg' => 'image',
        'jpeg' => 'image',
        'gif' => 'image',
        'png' => 'image',
        'ogg' => 'audio',
        'mp3' => 'audio',
        'mp4' => 'video',
        'mov' => 'video',
        'wmv' => 'video',
        'flv' => 'video',
        'avi' => 'video',
        'mpg' => 'video'
    );

    public function createFolder($path)
    {
        if (!is_dir($this->_app["upload"] . $path)) {
            mkdir($this->_app["upload"] . $path);

            return true;
        } else {
            return false;
        }
    }

    public function rmFolder($dir)
    {

        if (!file_exists($dir)) {
            return false;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->rmFolder($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        rmdir($dir);

        return true;
    }

    public function rmFile($file_path) {
        if (is_file($this->_app["upload"] . $file_path)) {
            unlink($this->_app["upload"] . $file_path);

            return true;
        } else {
            return false;
        }
    }

    public function move($file, $path) {
        $name = substr($file, strrpos($file, "/")+1);

        if ($name != "") {
            if (rename($this->_app["upload"] . $file, $this->_app["upload"] . $path . $name)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function rename($path, $old_name, $new_name) {
        if (rename($this->_app["upload"] . $path . $old_name, $this->_app["upload"] . $path . $new_name)) {
            return true;
        } else {
            return false;
        }
    }

    public function streaming($path, $name, $mimetype = 'application/octet-stream') {
        $filepath = $this->_app["upload"] . $path . $name;
        $fsize = filesize($filepath);
        $ftime = date('D, d M Y H:i:s T', filemtime($filepath));

        $fd = @fopen($filepath, 'rb');

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

        $origin = AccessOrigin::model()->findByPk(1);
        header('Access-Control-Allow-Origin: ' . $origin->value);

        header('Content-Disposition: attachment; filename="'.$name.'"');
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

    /**
     * Export
     */

    public function scanFilesAndFolders() {
        chdir($this->_app["upload"]);
        $this->_parent = getcwd();

        return $this->printFilesAndFoldersTree();
    }

    public function printFilesAndFoldersTree() {
        $d = @opendir(".");
        if (!$d) return;
        while (($e=readdir($d)) !== false) {
            if ($e=='.' || $e=='..') continue;

            if (@is_dir($e)) {
                $parent = substr(getcwd(), strlen($this->_parent)+1);

                $has = Folder::model()->exists("name = :name AND parent = :parent", array(
                    "name" => $e,
                    "parent" => $parent
                ));

                if (!$has) {
                    $model = new Folder();
                    $model->name = $e;
                    $model->added = filemtime($e);
                    $model->checked = date("Y-m-d H:i:s");
                    $model->parent = $parent;
                    $model->deleted = 0;

                    if ($model->validate()) {
                        $model->save(false);
                    }
                }
            }

            if (@is_file($e)) {
                $folder = substr(getcwd(), strlen($this->_parent)+1);

                $has = File::model()->exists("name = :name AND parent = :parent", array(
                    "name" => $e,
                    "parent" => $folder
                ));

                if (!$has) {

                    $model = new File();
                    $model->name = $e;
                    $model->size = filesize($e);
                    if (isset($this->filetypes[strtolower(mb_substr($e, mb_strrpos($e, ".")+1))])) {
                        $model->extension = $this->filetypes[strtolower(mb_substr($e, mb_strrpos($e, ".")+1))];
                    } else {
                        $model->extension = "other";
                    }
                    $model->data = $this->_img_resize($e);
                    $model->added = filemtime($e);
                    $model->checked = date("Y-m-d H:i:s");
                    $model->parent = $folder;
                    $model->deleted = 0;

                    if ($model->validate()) {
                        $model->save(false);
                    }
                }
            }

            if (!is_dir($e)) {
                continue;
            } else {
                chdir($e);
            }
            $this->printFilesAndFoldersTree();

            chdir("..");

            flush();
        }
        closedir($d);
    }

    public function checkDeletedFilesAndFolders() {
        $folders = Folder::model()->findAll();
        foreach($folders as $part) {
            if (!is_dir($this->_app["upload"] . $part->parent)) {
                $part->deleted = "1";
                $part->checked = date("Y-m-d H:i:s");
                if($part->validate()) {
//                    $part->save();
                }
            }
        }

        $files = File::model()->findAll();
        foreach($files as $part) {
            if (!is_file($this->_app["upload"] . $part->parent)) {
                $part->deleted = "1";
                $part->checked = date("Y-m-d H:i:s");
                if($part->validate()) {
//                    $part->save();
                }
            }
        }
    }

    public function getFoldersStructure($checked = "0000-00-00 00:00:00") {
        $folders = Folder::model()->findAll("checked >= :checked", array("checked" => $checked));

        $result = array();
        foreach($folders as $part)
        {
            $result[$part->id] = $part->attributes;
            $result[$part->id]["obj"] = "folder";
            $result[$part->id]["parent"] = $this->getPath($part->parent_id);
            $result[$part->id]["deleted"] = $part->deleted;
        }

        return $result;
    }

    public function getFilesStructure($checked = "0000-00-00 00:00:00") {
        $folders = File::model()->findAll("checked >= :checked", array("checked" => $checked));

        $result = array();
        foreach($folders as $part)
        {
            $result[$part->id] = $part->attributes;
            $result[$part->id]["obj"] = "file";
            $result[$part->id]["parent"] = $this->getPath($part->folder_id);
            $result[$part->id]["deleted"] = $part->deleted;
            if ($part->data != "") {
                $result[$part->id]["thumb"] = $part->data;
            }
        }

        return $result;
    }

    function _img_resize($source_path) {
        $imageTypeArray = array
        (
            0=>'UNKNOWN',
            1=>'GIF',
            2=>'JPEG',
            3=>'PNG',
            4=>'SWF',
            5=>'PSD',
            6=>'BMP',
            7=>'TIFF_II',
            8=>'TIFF_MM',
            9=>'JPC',
            10=>'JP2',
            11=>'JPX',
            12=>'JB2',
            13=>'SWC',
            14=>'IFF',
            15=>'WBMP',
            16=>'XBM',
            17=>'ICO',
            18=>'COUNT'
        );

        // иначе на некотоых jpeg-файлах не работает
        ini_set("gd.jpeg_ignore_warning", 1);

        $size = @getimagesize($source_path);
        if (!is_array($size)) { return false; };
        if ($size === false) { return false; };
        list($oldwidth, $oldheight, $type) = $size;

        $x_ratio = $this->_width / $size[0];
        $y_ratio = $this->_height / $size[1];
        $ratio = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        switch ($imageTypeArray[$type]) {
            case 'JPEG': $typestr = 'jpeg'; break;
            case 'GIF': $typestr = 'gif'; break;
            case 'PNG': $typestr = 'png'; break;
            default: return;
        }
        $function = "imagecreatefrom$typestr";
        $src_resource = $function($source_path);

        $newwidth = $use_x_ratio ? $this->_width : floor($size[0] * $ratio);
        $newheight = !$use_x_ratio ? $this->_height : floor($size[1] * $ratio);

        $destination_resource = imagecreatetruecolor($newwidth,$newheight);

        imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);

        ob_start();
        imagepng($destination_resource);
        $image_data = ob_get_contents();
        ob_end_clean();

        imagedestroy($destination_resource);
        imagedestroy($src_resource);

        return base64_encode($image_data);
    }

    public function zip($data, $destination)
    {
        if (!extension_loaded('zip')) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        foreach($data as $file) {
            $source = str_replace('\\', '/', realpath($this->_app["upload"] . $file));

            if (is_dir($source) === true)
            {
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                foreach ($files as $file)
                {
                    $file = str_replace('\\', '/', $file);

                    // Ignore "." and ".." folders
                    if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                        continue;

                    $file = realpath($file);

                    if (is_dir($file) === true)
                    {
                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                    }
                    else if (is_file($file) === true)
                    {
                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                    }
                }
            }
            else if (is_file($source) === true)
            {
                $zip->addFromString(basename($source), file_get_contents($source));
            }
        }

        return $zip->close();
    }
}
