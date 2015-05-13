<?php

class SiteController extends Controller
{
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
                'actions' => array('index', 'login', 'error'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('logout', 'storageAccess', 'oauth', 'origin', 'upload', 'export'),
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    protected function beforeAction($action) {
        if (Yii::app()->params["debug"]) {
            return parent::beforeAction($action);
        }
    }

	public function actionIndex()
	{
        if (isset(Yii::app()->user->id)) {
            $user = User::model()->find(Yii::app()->user->id);
            $client = Clients::model()->find(1);
            $origin = AccessOrigin::model()->find(1);
            $upload = UploadPath::model()->findByPk(1);

            $this->render('settings', array(
                "login" => $user->username,
                "password" => $user->password,
                "client_id" => $client->id,
                "client_secret" => $client->secret,
                "origin" => $origin->value,
                "upload_path" => $upload->path
            ));
        } else {
            $this->render('login');
        }
	}

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function actionLogin()
    {
        $model=new LoginForm;

        $error = false;

        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                $this->redirect("/");
            } else {
                $error = true;
            }
        }
        // display the login form
        $this->render('login', array('model'=>$model, 'error'=>$error));
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();

        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionExport()
    {
        $folders = Yii::app()->db->createCommand()
            ->select('*')
            ->from('folder')
            ->order("id")
            ->queryAll();
        $files = Yii::app()->db->createCommand()
            ->select('*')
            ->from('file')
            ->order("file.id")
            ->queryAll();

        $output = json_encode(array_merge($folders, $files));

        header('Content-Disposition: attachment; filename="export.json"');
        header('Last-Modified: ' . date("Y-m-d H:i:s"));
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . strlen($output));
        header('Content-Type: application/json');

        echo $output;

        exit;
    }

    public function actionStorageAccess()
    {
        if (count($_POST) > 0) {
            $model = User::model()->findByPk(1);
            $model->attributes = $_POST['User'];

            if($model->validate()) {
                $model->save();

                Yii::app()->user->setFlash('success', "Saved");
            } else {
                $messages = array();
                foreach($model->getErrors() as $error) {
                    $messages[] = $error[0];
                }
                Yii::app()->user->setFlash('error', implode(", ", $messages));
            }
        }

        $this->redirect("/");
    }

    public function actionOauth()
    {
        if (count($_POST) > 0) {
            $model = Clients::model()->find();
            $model->attributes = $_POST['Client'];

            if($model->validate()) {
                $model->save();

                Yii::app()->user->setFlash('success', "Saved");
            } else {
                $messages = array();
                foreach($model->getErrors() as $error) {
                    $messages[] = $error[0];
                }
                Yii::app()->user->setFlash('error', implode(", ", $messages));
            }
        }

        $this->redirect("/");
    }

    public function actionOrigin()
    {
        if (count($_POST) > 0) {
            $model = AccessOrigin::model()->findByPk(1);
            $model->value = $_POST['origin'];

            if($model->validate()) {
                $model->save();

                Yii::app()->user->setFlash('success', "Saved");
            } else {
                $messages = array();
                foreach($model->getErrors() as $error) {
                    $messages[] = $error[0];
                }
                Yii::app()->user->setFlash('error', implode(", ", $messages));
            }
        }

        $this->redirect("/");
    }

    public function actionUpload()
    {
        if (count($_POST) > 0) {
            $model = UploadPath::model()->findByPk(1);
            $model->path = $_POST['path'];

            if($model->validate()) {
                $model->save();

                Yii::app()->user->setFlash('success', "Saved");
            } else {
                $messages = array();
                foreach($model->getErrors() as $error) {
                    $messages[] = $error[0];
                }
                Yii::app()->user->setFlash('error', implode(", ", $messages));
            }
        }

        $this->redirect("/");
    }
}