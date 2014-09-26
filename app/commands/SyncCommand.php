<?php
class SyncCommand extends CConsoleCommand
{
    private $_app;

    public function run($args)
    {
        if (UploadPath::model()->exists("id = :id", array("id" => 1))) {
            $upload = UploadPath::model()->findByPk(1);
            $this->_app["upload"] = $upload->path;
        } else {
            $this->_app["upload"] = "../../upload/";
        }

        $files = new Files($this->_app);

        //new files
        $files->scanFilesAndFolders();

        //delete files
        $files->checkDeletedFilesAndFolders();
    }
}