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

    public function actionCreateFolder() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->createFolder(urldecode($_GET["path"]) . urldecode($_GET["name"]))) {
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

    public function actionRemoveFolder() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->removeFolder($this->_app["upload"] . urldecode($_GET["path"]) . urldecode($_GET["name"]))) {
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

            if ($files->rmFiles(urldecode($_GET["path"]) . urldecode($_GET["name"]))) {
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

    public function actionMove() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->move($_GET["data"], urldecode($_GET["path"]))) {
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

    public function actionRename() {
        try {
            $oauth = new RemoteAuth();
            $resource = $oauth->resource();

            $resource->setTokenKey('token');
            $resource->isValid();

            $files = new Files($this->_app);

            if ($files->rename(urldecode($_GET["path"]), urldecode($_GET["old_name"]), urldecode($_GET["new_name"]))) {
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
}