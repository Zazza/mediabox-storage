<?php

/**
 * This is the model class for table "oauth_session_authcode_scopes".
 *
 * The followings are the available columns in table 'oauth_session_authcode_scopes':
 * @property string $oauth_session_authcode_id
 * @property integer $scope_id
 *
 * The followings are the available model relations:
 * @property Scopes $scope
 * @property SessionAuthcodes $oauthSessionAuthcode
 */
class SessionAuthcodeScopes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_session_authcode_scopes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oauth_session_authcode_id, scope_id', 'required'),
			array('scope_id', 'numerical', 'integerOnly'=>true),
			array('oauth_session_authcode_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('oauth_session_authcode_id, scope_id', 'safe', 'on'=>'search'),
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
			'scope' => array(self::BELONGS_TO, 'Scopes', 'scope_id'),
			'oauthSessionAuthcode' => array(self::BELONGS_TO, 'SessionAuthcodes', 'oauth_session_authcode_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'oauth_session_authcode_id' => 'Oauth Session Authcode',
			'scope_id' => 'Scope',
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

		$criteria->compare('oauth_session_authcode_id',$this->oauth_session_authcode_id,true);
		$criteria->compare('scope_id',$this->scope_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionAuthcodeScopes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
