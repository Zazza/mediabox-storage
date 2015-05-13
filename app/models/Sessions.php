<?php

/**
 * This is the model class for table "oauth_sessions".
 *
 * The followings are the available columns in table 'oauth_sessions':
 * @property string $id
 * @property string $client_id
 * @property string $owner_type
 * @property string $owner_id
 *
 * The followings are the available model relations:
 * @property SessionAccessTokens[] $sessionAccessTokens
 * @property SessionAuthcodes[] $sessionAuthcodes
 * @property SessionRedirects $sessionRedirects
 * @property Clients $client
 */
class Sessions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_sessions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, owner_id', 'required'),
			array('client_id', 'length', 'max'=>40),
			array('owner_type', 'length', 'max'=>6),
			array('owner_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, owner_type, owner_id', 'safe', 'on'=>'search'),
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
			'sessionAccessTokens' => array(self::HAS_MANY, 'SessionAccessTokens', 'session_id'),
			'sessionAuthcodes' => array(self::HAS_MANY, 'SessionAuthcodes', 'session_id'),
			'sessionRedirects' => array(self::HAS_ONE, 'SessionRedirects', 'session_id'),
			'client' => array(self::BELONGS_TO, 'Clients', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client',
			'owner_type' => 'Owner Type',
			'owner_id' => 'Owner',
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
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('owner_type',$this->owner_type,true);
		$criteria->compare('owner_id',$this->owner_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sessions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
