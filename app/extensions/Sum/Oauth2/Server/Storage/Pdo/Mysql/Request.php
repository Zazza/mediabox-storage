<?php
namespace Sum\Oauth2\Server\Storage\Pdo\Mysql;

use League\OAuth2\Server\Util\RequestInterface;

class Request implements RequestInterface
{
    public function get($index = NULL)
    {
        if (isset($_GET[$index]))
            return $_GET[$index];
    }

    public function post($index = NULL)
    {
        if (isset($_POST[$index]))
            return $_POST[$index];
    }

    public function cookie($index = NULL)
    {
        if (isset($_COOKIE[$index]))
            return $_COOKIE[$index];
        //return Yii::app()->request->cookies[$index]->value;
    }

    public function file($index = NULL)
    {
        if (is_null($index))
            return $_FILES;

        return isset($_FILES[$index]) ? $_FILES[$index] : NULL;
    }

    public function server($index = NULL)
    {
        if (isset($_SERVER[$index]))
            return $_SERVER[$index];
    }

    private function _getallheaders()
    {
        foreach($_SERVER as $name => $value)
        {
            if(substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function header($index = NULL)
    {
        $array = $this->_getallheaders();
        if (isset($array[$index]))
            return $array[$index];
    }
}
