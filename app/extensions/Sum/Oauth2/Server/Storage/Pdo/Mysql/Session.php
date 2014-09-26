<?php
namespace Sum\Oauth2\Server\Storage\Pdo\Mysql;

use League\OAuth2\Server\Storage\SessionInterface;

class Session implements SessionInterface
{

    protected $db;
    protected $tables = array(
        'oauth_sessions'                => 'oauth_sessions',
        'oauth_session_authcodes'       => 'oauth_session_authcodes',
        'oauth_session_redirects'       => 'oauth_session_redirects',
        'oauth_session_access_tokens'   => 'oauth_session_access_tokens',
        'oauth_session_refresh_tokens'  => 'oauth_session_refresh_tokens',
        'oauth_session_token_scopes'    => 'oauth_session_token_scopes',
        'oauth_session_authcode_scopes' => 'oauth_session_authcode_scopes'
    );

    /**
     * Create a new session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_sessions (client_id, owner_type,  owner_id)
     *  VALUE (:clientId, :ownerType, :ownerId)
     * </code>
     *
     * @param  string $clientId The client ID
     * @param  string $ownerType The type of the session owner (e.g. "user")
     * @param  string $ownerId The ID of the session owner (e.g. "123")
     * @return int               The session ID
     */
    public function createSession($clientId, $ownerType, $ownerId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_sessions'], array(
            'client_id'=>$clientId,
            'owner_type'=>$ownerType,
            'owner_id'=>$ownerId
        ));
        return \Yii::app()->db->getLastInsertID();
    }

    /**
     * Delete a session
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM oauth_sessions WHERE client_id = :clientId AND owner_type = :type AND owner_id = :typeId
     * </code>
     *
     * @param  string $clientId The client ID
     * @param  string $ownerType The type of the session owner (e.g. "user")
     * @param  string $ownerId The ID of the session owner (e.g. "123")
     * @return void
     */
    public function deleteSession($clientId, $ownerType, $ownerId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->delete($this->tables['oauth_sessions'], 'client_id = :clientId AND owner_type = :type AND owner_id = :typeId', array(
            'type' => $ownerType, 'typeId' => $ownerId, 'clientId' => $clientId
        ));
    }

    /**
     * Associate a redirect URI with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_redirects (session_id, redirect_uri) VALUE (:sessionId, :redirectUri)
     * </code>
     *
     * @param  int $sessionId The session ID
     * @param  string $redirectUri The redirect URI
     * @return void
     */
    public function associateRedirectUri($sessionId, $redirectUri)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_redirects'], array(
            'session_id'=>$sessionId,
            'redirect_uri'=>$redirectUri
        ));
    }

    /**
     * Associate an access token with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_access_tokens (session_id, access_token, access_token_expires)
     *  VALUE (:sessionId, :accessToken, :accessTokenExpire)
     * </code>
     *
     * @param  int $sessionId The session ID
     * @param  string $accessToken The access token
     * @param  int $expireTime Unix timestamp of the access token expiry time
     * @return int                 The access token ID
     */
    public function associateAccessToken($sessionId, $accessToken, $expireTime)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_access_tokens'], array(
            'session_id'=>$sessionId,
            'access_token'=>$accessToken,
            'access_token_expires'=>$expireTime
        ));
        return \Yii::app()->db->getLastInsertID();
    }

    /**
     * Associate a refresh token with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_refresh_tokens (session_access_token_id, refresh_token, refresh_token_expires,
     *  client_id) VALUE (:accessTokenId, :refreshToken, :expireTime, :clientId)
     * </code>
     *
     * @param  int $accessTokenId The access token ID
     * @param  string $refreshToken The refresh token
     * @param  int $expireTime Unix timestamp of the refresh token expiry time
     * @param  string $clientId The client ID
     * @return void
     */
    public function associateRefreshToken($accessTokenId, $refreshToken, $expireTime, $clientId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_refresh_tokens'], array(
            'session_access_token_id'=>$accessTokenId,
            'refresh_token'=>$refreshToken,
            'refresh_token_expires'=>$expireTime,
            'client_id'=>$clientId
        ));
    }

    /**
     * Assocate an authorization code with a session
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO oauth_session_authcodes (session_id, auth_code, auth_code_expires)
     *  VALUE (:sessionId, :authCode, :authCodeExpires)
     * </code>
     *
     * @param  int $sessionId The session ID
     * @param  string $authCode The authorization code
     * @param  int $expireTime Unix timestamp of the access token expiry time
     * @return int                The auth code ID
     */
    public function associateAuthCode($sessionId, $authCode, $expireTime)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_authcodes'], array(
            'session_id'=>$sessionId,
            'auth_code'=>$authCode,
            'auth_code_expires'=>$expireTime
        ));
        return \Yii::app()->db->getLastInsertID();
    }

    /**
     * Remove an associated authorization token from a session
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM oauth_session_authcodes WHERE session_id = :sessionId
     * </code>
     *
     * @param  int $sessionId The session ID
     * @return void
     */
    public function removeAuthCode($sessionId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->delete($this->tables['oauth_session_authcodes'], 'session_id = :sessionId', array(
            'sessionId' => $sessionId
        ));
    }

    /**
     * Validate an authorization code
     *
     * Example SQL query:
     *
     * <code>
     * SELECT oauth_sessions.id AS session_id, oauth_session_authcodes.id AS authcode_id FROM oauth_sessions
     *  JOIN oauth_session_authcodes ON oauth_session_authcodes.`session_id` = oauth_sessions.id
     *  JOIN oauth_session_redirects ON oauth_session_redirects.`session_id` = oauth_sessions.id WHERE
     * oauth_sessions.client_id = :clientId AND oauth_session_authcodes.`auth_code` = :authCode
     *  AND `oauth_session_authcodes`.`auth_code_expires` >= :time AND
     *  `oauth_session_redirects`.`redirect_uri` = :redirectUri
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'session_id' =>  (int)
     *     'authcode_id'  =>  (int)
     * )
     * </code>
     *
     * @param  string $clientId The client ID
     * @param  string $redirectUri The redirect URI
     * @param  string $authCode The authorization code
     * @return array|bool              False if invalid or array as above
     */
    public function validateAuthCode($clientId, $redirectUri, $authCode)
    {
        $oS = mysql_real_escape_string($this->tables['oauth_sessions']);
        $oSA = mysql_real_escape_string($this->tables['oauth_session_authcodes']);
        $oSR = mysql_real_escape_string($this->tables['oauth_session_redirects']);

        $sql="SELECT $oS.id AS session_id, $oSA.id AS authcode_id
            FROM $oS
            JOIN $oSA
            ON $oSA.session_id = $oS.id
            JOIN $oSR
            ON $oSR.session_id = $oS.id
            WHERE $oS.client_id = :clientId
            AND $oSA.auth_code = :authCode
            AND $oSA.auth_code_expires >= :time
            AND $oSR.redirect_uri = :redirectUri";

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
        $command->bindParam(":redirect_uri", $redirectUri, \PDO::PARAM_STR);
        $command->bindParam(":authCode", $authCode, \PDO::PARAM_STR);
        $command->bindParam(":time", time(), \PDO::PARAM_STR);
        $row = $command->queryRow();

        if (!empty($row)) {
            return $row;
        }
        return FALSE;
    }

    /**
     * Validate an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT session_id, oauth_sessions.`client_id`, oauth_sessions.`owner_id`, oauth_sessions.`owner_type`
     *  FROM `oauth_session_access_tokens` JOIN oauth_sessions ON oauth_sessions.`id` = session_id WHERE
     *  access_token = :accessToken AND access_token_expires >= UNIX_TIMESTAMP(NOW())
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'session_id' =>  (int),
     *     'client_id'  =>  (string),
     *     'owner_id'   =>  (string),
     *     'owner_type' =>  (string)
     * )
     * </code>
     *
     * @param  string $accessToken The access token
     * @return array|bool              False if invalid or an array as above
     */
    public function validateAccessToken($accessToken)
    {
        $sql='SELECT session_id, oauth_sessions.client_id, oauth_sessions.owner_id, oauth_sessions.owner_type
              FROM oauth_session_access_tokens JOIN oauth_sessions ON oauth_sessions.id = session_id WHERE
              access_token = :accessToken AND access_token_expires >= UNIX_TIMESTAMP(NOW())';

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":accessToken", $accessToken, \PDO::PARAM_STR);
        $row = $command->queryRow();

        if (empty($row))
            return FALSE;
        return $row;
    }

    /**
     * Removes a refresh token
     *
     * Example SQL query:
     *
     * <code>
     * DELETE FROM `oauth_session_refresh_tokens` WHERE refresh_token = :refreshToken
     * </code>
     *
     * @param  string $refreshToken The refresh token to be removed
     * @return void
     */
    public function removeRefreshToken($refreshToken)
    {
        $command = \Yii::app()->db->createCommand();
        $command->delete($this->tables['oauth_session_refresh_tokens'], 'refresh_token = :refreshToken', array(
            'refreshToken' => $refreshToken
        ));
    }

    /**
     * Validate a refresh token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT session_access_token_id FROM `oauth_session_refresh_tokens` WHERE refresh_token = :refreshToken
     *  AND refresh_token_expires >= UNIX_TIMESTAMP(NOW()) AND client_id = :clientId
     * </code>
     *
     * @param  string $refreshToken The refresh token
     * @param  string $clientId The client ID
     * @return int|bool               The ID of the access token the refresh token is linked to (or false if invalid)
     */
    public function validateRefreshToken($refreshToken, $clientId)
    {
        $sql="SELECT session_access_token_id FROM oauth_session_refresh_tokens " .
            "WHERE refresh_token = :refreshToken AND refresh_token_expires >= UNIX_TIMESTAMP(NOW()) AND client_id = :clientId";

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":refreshToken", $refreshToken, \PDO::PARAM_STR);
        $command->bindParam(":clientId", $clientId, \PDO::PARAM_STR);
        $row = $command->queryRow();

        if (!empty($row))
            return $row['session_access_token_id'];
        return FALSE;
    }

    /**
     * Get an access token by ID
     *
     * Example SQL query:
     *
     * <code>
     * SELECT * FROM `oauth_session_access_tokens` WHERE `id` = :accessTokenId
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     'id' =>  (int),
     *     'session_id' =>  (int),
     *     'access_token'   =>  (string),
     *     'access_token_expires'   =>  (int)
     * )
     * </code>
     *
     * @param  int $accessTokenId The access token ID
     * @return array
     */
    public function getAccessToken($accessTokenId)
    {
        $sql = 'SELECT * FROM oauth_session_access_tokens WHERE id = :accessTokenId';

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":accessTokenId", $accessTokenId, \PDO::PARAM_STR);

        return $command->queryRow();
    }

    /**
     * Associate scopes with an auth code (bound to the session)
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO `oauth_session_authcode_scopes` (`oauth_session_authcode_id`, `scope_id`) VALUES
     *  (:authCodeId, :scopeId)
     * </code>
     *
     * @param  int $authCodeId The auth code ID
     * @param  int $scopeId The scope ID
     * @return void
     */
    public function associateAuthCodeScope($authCodeId, $scopeId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_authcode_scopes'], array(
            'oauth_session_authcode_id'=>$authCodeId,
            'scope_id'=>$scopeId
        ));
    }

    /**
     * Get the scopes associated with an auth code
     *
     * Example SQL query:
     *
     * <code>
     * SELECT scope_id FROM `oauth_session_authcode_scopes` WHERE oauth_session_authcode_id = :authCodeId
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array(
     *     array(
     *         'scope_id' => (int)
     *     ),
     *     array(
     *         'scope_id' => (int)
     *     ),
     *     ...
     * )
     * </code>
     *
     * @param  int $oauthSessionAuthCodeId The session ID
     * @return array
     */
    public function getAuthCodeScopes($oauthSessionAuthCodeId)
    {
        $sql = 'SELECT scope_id FROM oauth_session_authcode_scopes WHERE oauth_session_authcode_id = :authCodeId';

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":authCodeId", $oauthSessionAuthCodeId, \PDO::PARAM_STR);

        return $command->queryAll();
    }

    /**
     * Associate a scope with an access token
     *
     * Example SQL query:
     *
     * <code>
     * INSERT INTO `oauth_session_token_scopes` (`session_access_token_id`, `scope_id`) VALUE (:accessTokenId, :scopeId)
     * </code>
     *
     * @param  int $accessTokenId The ID of the access token
     * @param  int $scopeId The ID of the scope
     * @return void
     */
    public function associateScope($accessTokenId, $scopeId)
    {
        $command = \Yii::app()->db->createCommand();
        $command->insert($this->tables['oauth_session_token_scopes'], array(
            'session_access_token_id'=>$accessTokenId,
            'scope_id'=>$scopeId
        ));
    }

    /**
     * Get all associated access tokens for an access token
     *
     * Example SQL query:
     *
     * <code>
     * SELECT oauth_scopes.* FROM oauth_session_token_scopes JOIN oauth_session_access_tokens
     *  ON oauth_session_access_tokens.`id` = `oauth_session_token_scopes`.`session_access_token_id`
     *  JOIN oauth_scopes ON oauth_scopes.id = `oauth_session_token_scopes`.`scope_id`
     *  WHERE access_token = :accessToken
     * </code>
     *
     * Expected response:
     *
     * <code>
     * array (
     *     array(
     *         'id'     =>  (int),
     *         'scope'  =>  (string),
     *         'name'   =>  (string),
     *         'description'    =>  (string)
     *     ),
     *     ...
     *     ...
     * )
     * </code>
     *
     * @param  string $accessToken The access token
     * @return array
     */
    public function getScopes($accessToken)
    {
        $sql = 'SELECT oauth_scopes.* FROM oauth_session_token_scopes JOIN oauth_session_access_tokens
            ON oauth_session_access_tokens.id = oauth_session_token_scopes.session_access_token_id
            JOIN oauth_scopes ON oauth_scopes.id = oauth_session_token_scopes.scope_id
            WHERE access_token = :accessToken';

        $command = \Yii::app()->db->createCommand($sql);
        $command->bindParam(":accessToken", $accessToken, \PDO::PARAM_STR);

        return $command->queryAll();
    }
}
