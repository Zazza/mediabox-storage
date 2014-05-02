<?php
namespace Fm\Components;

class Save extends Base {
    private $_filename = null;
    private $_ext = null;
    private $_source = null;
    private $_target = null;

    public function save() {
        move_uploaded_file($this->_source, $this->_target);

        return true;
    }

    public function handleUpload($name) {
        if (!is_writable($this->_app['upload'])){
            $this->_error = 'Server error. Write in a directory: ' . $this->_app['upload'] . ' is impossible!';

            return false;
        }

        if (isset($_FILES['files'])) {
            $this->_source = $_FILES['files']['tmp_name'];
            $this->_filename = $name;
            $this->_ext = end(explode('.', strtolower($_FILES['files']['name'])));

            $this->_target = $this->_app['upload'] . $this->_filename;

            if ($this->save()) {
                return true;
            } else {
                $this->_error = 'It is impossible to save the file.' . 'Cancelled, server error';

                return false;
            }
        } else {
            $this->_error = 'array empty';

            return false;
        }
    }
}
