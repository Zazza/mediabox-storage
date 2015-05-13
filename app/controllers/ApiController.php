<?php
class ApiController extends Controller
{
    private $_app;
    private $_response;

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

    public function afterAction($action)
    {
        $origin = AccessOrigin::model()->findByPk(1);

        header('Access-Control-Allow-Origin: ' . $origin->value);
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
        header('Access-Control-Max-Age: 600');

        if (isset($_GET['callback'])) {
            echo $_GET['callback'] . "(" . json_encode($this->_response) . ")";
        } else {
            echo json_encode($this->_response);
        }

        return parent::afterAction($action);
    }

    public function actionAccess() {
        try {
            $oauth = new RemoteAuth();
            $auth = $oauth->oauth($_GET["session"]);

            $params = $auth->getParam(array('client_id', 'client_secret'));
            $this->_response = array(
                "result" => true
            );
            $this->_response += $auth
                    ->getGrantType('client_credentials')
                    ->completeFlow($params);

        } catch (\League\OAuth2\Server\Exception\ClientException $e) {
            $this->_response = array(
                "result" => false,
                "error" => $e->getTraceAsString()
            );
        } catch (\Exception $e) {
            $this->_response = array(
                "result" => false,
                "error" => $e->getTraceAsString()
            );
        }
    }

    public function actionTestConnection() {
        $oauth = new RemoteAuth();
        $resource = $oauth->resource();

        $resource->setTokenKey('token');
        try {
            $resource->isValid();

            $this->_response = array(
                "result" => true
            );
        } catch (Exception $e) {
            $this->_response = array(
                "result" => false,
                "error" => $e->getMessage()
            );
        };
    }

    public function actionCreateFolder() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->createFolder(urldecode($_POST["path"]) . urldecode($_POST["name"]))) {
                $this->_response = array(
                    "result" => true
                );
            } else {
                $this->_response = array(
                    "result" => false
                );
            }

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    public function actionSave() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $save = new FSave($this->_app);

            if ($save->handleUpload(urldecode($_POST["path"]), urldecode($_POST["name"]))) {
                $this->_response = array(
                    "result" => true
                );
            } else {
                $this->_response = array(
                    "result" => false,
                    "error" => $save->getError()
                );
            }
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }


    public function actionGet() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            $filepath = $this->_app["upload"] . urldecode($_GET["path"]) . urldecode($_GET["name"]);

            if (file_exists($filepath)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $content_type = finfo_file($finfo, $filepath);
                finfo_close($finfo);

                return $files->streaming(urldecode($_GET["path"]), urldecode($_GET["name"]), $content_type);
            }

            return false;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    public function actionRemove() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            $result = [];
            foreach(json_decode($_POST["files"], true) as $part) {
                $array = [];

                if ($part["type"] == "file") {
                    if ($files->rmFile(urldecode($part["path"]))) {
                        $array = [
                            "type" => "file",
                            "id" => $part["id"],
                            "result" => true
                        ];
                    } else {
                        $array = [
                            "type" => "file",
                            "id" => $part["id"],
                            "result" => false
                        ];
                    }
                }
                if ($part["type"] == "folder") {
                    if ($files->rmFolder($this->_app["upload"] . urldecode($part["path"]))) {
                        $array = [
                            "type" => "folder",
                            "id" => $part["id"],
                            "result" => true
                        ];
                    } else {
                        $array = [
                            "type" => "folder",
                            "id" => $part["id"],
                            "result" => false
                        ];
                    }
                }

                $result[] = $array;
            }

            $this->_response = [
                "result" => true,
                "messages" => $result
            ];

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false,
                "message" => $e->getMessage()
            );
        }
    }

    public function actionMove() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            $result = [];
            foreach(json_decode($_POST["files"], true) as $part) {
                if ($files->move($part["file"], urldecode($_POST["path"]))) {
                    $result[] = [
                        "type" => $part["type"],
                        "id" => $part["id"],
                        "result" => true
                    ];
                } else {
                    $result[] = [
                        "type" => $part["type"],
                        "id" => $part["id"],
                        "result" => false
                    ];
                }
            }

            $this->_response = array(
                "result" => true,
                "messages" => $result
            );

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    public function actionRename() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->rename(urldecode($_POST["path"]), urldecode($_POST["old_name"]), urldecode($_POST["new_name"]))) {
                $this->_response = array(
                    "result" => true
                );
            } else {
                $this->_response = array(
                    "result" => false
                );
            }

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    public function actionDownload() {
        $files = new Files($this->_app);

        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $zip_name = time() . ".zip";
            $zip_filepath = $this->_app["upload"] . $zip_name;

            $get = explode(",", urldecode($_GET["data"]));

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

        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    private function _genShareName($len){
        $gen = "";
        $ch = array('digits' => array(0,1,2,3,4,5,6,7,8,9),
            'lower' => array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'),
            'upper' => array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'));

        $chTypes = array_keys($ch);
        $numTypes = count($chTypes) - 1;

        for($i=0; $i<$len; $i++){
            $chType = $chTypes[mt_rand(0, $numTypes)];
            $gen .= $ch[$chType][mt_rand(0, count($ch[$chType]) - 1 )];
        }

        if (!Share::model()->exists("name = :gen", array(":gen" => $gen))) {
            return $gen;
        } else {
            return $this->_genShareName($len);
        }
    }


    public function actionShare() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $share = new Share();
            $share->name = $this->_genShareName(8);
            $share->save(false);

            foreach(explode(",", $_POST['files']) as $file) {
                $share_file = new ShareFile();
                $share_file->share_id = $share->id;
                $share_file->file = $file;
                $share_file->save(false);
            }

            $this->_response = array(
                "result" => true,
                'share' => $share->name
            );

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }

    public function actionShareDelete() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $share_id = $_POST["share_id"];
            $share = Share::model()->findByPk($share_id);

            foreach($share->ShareFile as $file) {
                $file->delete();
            }
            $share->delete();

            $this->_response = array(
                "result" => true
            );

            return true;
        } catch (League\OAuth2\Server\Exception\InvalidAccessTokenException $e) {
            $this->_response = array(
                "result" => false
            );
            $this->_response += array("message" => $e->getMessage());
        }
    }
}