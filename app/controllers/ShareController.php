<?php

class ShareController extends Controller
{
    private $_app;

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('get'),
                'users' => array('*'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    protected function beforeAction($action) {
        if (UploadPath::model()->exists("id = :id", array("id" => 1))) {
            $upload = UploadPath::model()->findByPk(1);

            if ($upload->path != "") {
                $this->_app["upload"] = $upload->path;
            } else {
                $this->_app["upload"] = __DIR__ . "/../../upload/";
            }
        } else {
            $this->_app["upload"] = __DIR__ . "/../../upload/";
        }

        return parent::beforeAction($action);
    }

    public function actionGet($share)
    {
        if (Share::model()->exists("name = :name", array(":name" => $share))) {
            $share = Share::model()->find("name = :name", array(":name" => $share));

            $num = count($share->ShareFile);

            if ($num == 1) {

                $files = new Files($this->_app);

                $filepath = $this->_app["upload"] . urldecode($share->ShareFile[0]->file);
                $name = mb_substr(urldecode($share->ShareFile[0]->file), mb_strrpos($filepath, "/"));
                $path = mb_substr(urldecode($share->ShareFile[0]->file), 0, mb_strrpos($filepath, "/")-1);

                if (file_exists($filepath)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $content_type = finfo_file($finfo, $filepath);
                    finfo_close($finfo);

                    return $files->streaming($path, $name, $content_type);
                }

            } elseif ($num > 1) {

                $zip_name = time() . ".zip";
                $zip_filepath = $this->_app["upload"] . $zip_name;

                $get = array();
                foreach($share->ShareFile as $file) {
                    $get[] = urldecode($file->file);
                }

                $files = new Files($this->_app);
                $files->zip($get, $zip_filepath);
                if (file_exists($zip_filepath)) {
                    $files->streaming("", $zip_name, "application/zip");

                    /*
                     * не выполняется?
                     *
                    unlink($zip_filepath);

                    $this->_response = array(
                        "result" => true
                    );

                    return true;
                    */
                }

            }
        }
    }

}