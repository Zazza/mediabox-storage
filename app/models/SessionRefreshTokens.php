<?php

/**
 * This is the model class for table "oauth_session_refresh_tokens".
 *
 * The followings are the available columns in table 'oauth_session_refresh_tokens':
 * @property string $session_access_token_id
 * @property string $refresh_token
 * @property string $refresh_token_expires
 * @property string $client_id
 *
 * The followings are the available model relations:
 * @property Clients $client
 * @property SessionAccessTokens $sessionAccessToken
 */
class SessionRefreshTokens extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_session_refresh_tokens';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_access_token_id, refresh_token, refresh_token_expires, client_id', 'required'),
			array('session_access_token_id, refresh_token_expires', 'length', 'max'=>10),
			array('refresh_token, client_id', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_access_token_id, refresh_token, refresh_token_expires, client_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
			'sessionAccessToken' => array(self::BELONGS_TO, 'SessionAccessTokens', 'session_access_token_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'session_access_token_id' => 'Session Access Token',
			'refresh_token' => 'Refresh Token',
			'refresh_token_expires' => 'Refresh Token Expires',
			'client_id' => 'Client',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('session_access_token_id',$this->session_access_token_id,true);
		$criteria->compare('refresh_token',$this->refresh_token,true);
		$criteria->compare('refresh_token_expires',$this->refresh_token_expires,true);
		$criteria->compare('client_id',$this->client_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionRefreshTokens the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
