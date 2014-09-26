<?php
namespace Sum\Oauth2\Server\Storage\Pdo\Mysql;

use League\OAuth2\Server\Storage\ClientInterface;

class Client implements ClientInterface
{

    protected $tables = [
        'oauth_clients'          => 'oauth_clients',
        'oauth_client_endpoints' => 'oauth_client_endpoints',
    ];

    /**
     * Validate a client
     *
     * Example SQL query:
     *
     * <code>
     * # Client ID + redirect URI
     * SELECT oauth_clients.id, oauth_clients.secret, oauth_client_endpoints.redirect_uri, oauth_clients.name,
     * oauth_clients.auto_approve
     *  FROM oauth_clients LEFT JOIN oauth_client_endpoints ON oauth_client_endpoints.client_id = oauth_clients.id
     *  WHERE oauth_clients.id = :clientId AND oauth_client_endpoints.redirect_uri = :redirectUri
     *
     * # Client ID + client secret
     * SELECT oauth_clients.id, oauth_clients.secret, oauth_clients.name, oauth_clients.auto_approve FROM oauth_clients
     * WHERE oauth_clients.id = :clientId AND oauth_clients.secret = :clientSecret
     *
     * # Client ID + client secret + redirect URI
     * SELECT oauth_clients.id, oauth_clients.secret, oauth_client_endpoints.redirect_uri, oauth_clients.name,
     * oauth_clients.auto_approve FROM oauth_clients LEFT JOIN oauth_client_endpoints
     * ON oauth_client_endpoints.client_id = oauth_clients.id
     * WHERE oauth_clients.id = :clientId AND oauth_clients.secret = :clientSecret AND
     * oauth_client_endpoints.redirect_uri = :redirectUri
     * </code>
     *
     * Response:
     *
     * <code>
     * Array
     * (
     *     [client_id] => (string) The client ID
     *     [client secret] => (string) The client secret
     *     [redirect_uri] => (string) The redirect URI used in this request
     *     [name] => (string) The name of the client
     *     [auto_approve] => (bool) Whether the client should auto approve
     * )
     * </code>
     *
     * @param  string $clientId The client's ID
     * @param  string $clientSecret The client's secret (default = "null")
     * @param  string $redirectUri The client's redirect URI (default = "null")
     * @param  string $grantType The grant type used in the request (default = "null")
     * @return bool|array               Returns false if the validation fails, array on success
     */
    public function getClient($clientId, $clientSecret = NULL, $redirectUri = NULL, $grantType = NULL)
    {
        if ($clientSecret AND $redirectUri) {
            $sql = 'SELECT oauth_clients.id, oauth_clients.secret, oauth_client_endpoints.redirect_uri, oauth_clients.name,' .
                'oauth_clients.auto_approve FROM oauth_clients LEFT JOIN oauth_client_endpoints ON ' .
                'oauth_client_endpoints.client_id = oauth_clients.id ' .
                'WHERE oauth_clients.id = :clientId AND oauth_clients.secret = :clientSecret AND ' .
                'oauth_client_endpoints.redirect_uri = :redirectUri';

            $command = \Yii::app()->db->createCommand($sql);
            $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
            $command->bindParam(":clientSecret", $clientSecret, \PDO::PARAM_STR);
            $command->bindParam(":redirectUri", $redirectUri, \PDO::PARAM_STR);
            $row = $command->queryRow();
        } else if ($clientSecret) {
            $sql = 'SELECT oauth_clients.id, oauth_clients.secret, oauth_clients.name, oauth_clients.auto_approve ' .
                'FROM oauth_clients WHERE oauth_clients.id = :clientId AND oauth_clients.secret = :clientSecret';

            $command = \Yii::app()->db->createCommand($sql);
            $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
            $command->bindParam(":clientSecret", $clientSecret, \PDO::PARAM_STR);
            $row = $command->queryRow();
        } elseif ($redirectUri) {
            $sql = 'SELECT ' .
                'c.id, c.secret, e.redirect_uri, c.name, c.auto_approve ' .
                'FROM oauth_clients c ' .
                'LEFT JOIN oauth_client_endpoints e ' .
                'ON e.client_id = c.id ' .
                'WHERE c.id = :clientId AND e.redirect_uri = :redirectUri';

            $command = \Yii::app()->db->createCommand($sql);
            $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
            $command->bindParam(":redirectUri", $redirectUri, \PDO::PARAM_STR);
            $row = $command->queryRow();
        } else {
            $sql = 'SELECT * FROM oauth_clients WHERE id = :clientId';

            $command = \Yii::app()->db->createCommand($sql);
            $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
            $row = $command->queryRow();
        }
        if (empty($row))
            return FALSE;
        return $row;
    }
}
