<?php

/**
 * This is the model class for table "oauth_session_authcodes".
 *
 * The followings are the available columns in table 'oauth_session_authcodes':
 * @property string $id
 * @property string $session_id
 * @property string $auth_code
 * @property string $auth_code_expires
 *
 * The followings are the available model relations:
 * @property SessionAuthcodeScopes[] $sessionAuthcodeScopes
 * @property Sessions $session
 */
class SessionAuthcodes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_session_authcodes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_id, auth_code, auth_code_expires', 'required'),
			array('session_id, auth_code_expires', 'length', 'max'=>10),
			array('auth_code', 'length', 'max'=>40),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, session_id, auth_code, auth_code_expires', 'safe', 'on'=>'search'),
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
			'sessionAuthcodeScopes' => array(self::HAS_MANY, 'SessionAuthcodeScopes', 'oauth_session_authcode_id'),
			'session' => array(self::BELONGS_TO, 'Sessions', 'session_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'session_id' => 'Session',
			'auth_code' => 'Auth Code',
			'auth_code_expires' => 'Auth Code Expires',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('auth_code',$this->auth_code,true);
		$criteria->compare('auth_code_expires',$this->auth_code_expires,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionAuthcodes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
