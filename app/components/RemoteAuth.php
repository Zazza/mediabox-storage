<?php
class RemoteAuth {
    public function oauth($session = 60) {
        Yii::setPathOfAlias('Sum',Yii::getPathOfAlias('ext.Sum'));

        $server = new \League\OAuth2\Server\Authorization(
            new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Client(),
            new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Session(),
            new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Scope()
        );

        # Not required as it called directly from original code
        # $request = new \League\OAuth2\Server\Util\Request();

        # add these 2 lines code if you want to use my own Request otherwise comment it
        $request = new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Request();
        $server->setRequest($request);

        $server->setAccessTokenTTL($session); //86400
        $server->addGrantType(new League\OAuth2\Server\Grant\ClientCredentials());

        return $server;
    }

    public function resource() {
	    Yii::setPathOfAlias('Sum',Yii::getPathOfAlias('ext.Sum'));

        $resource = new League\OAuth2\Server\Resource(
            new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Session()
        );
        ##only exist on my develop fork
        #$resource->setMsg([
        #    'invalidToken' => 'Token tidak benar',
        #    'missingToken' => 'Token tidak ditemukan'
        #]);
        $resource->setRequest(new \Sum\Oauth2\Server\Storage\Pdo\Mysql\Request());

        return $resource;
    }

}