<?php

/**
 * This is the model class for table "oauth_clients".
 *
 * The followings are the available columns in table 'oauth_clients':
 * @property string $id
 * @property string $secret
 * @property string $name
 * @property integer $auto_approve
 *
 * The followings are the available model relations:
 * @property ClientEndpoints[] $clientEndpoints
 * @property SessionRefreshTokens[] $sessionRefreshTokens
 * @property Sessions[] $sessions
 */
class Clients extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_clients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, secret, name', 'required'),
			array('auto_approve', 'numerical', 'integerOnly'=>true),
			array('id, secret', 'length', 'max'=>40),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, secret, name, auto_approve', 'safe', 'on'=>'search'),
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
			'clientEndpoints' => array(self::HAS_MANY, 'ClientEndpoints', 'client_id'),
			'sessionRefreshTokens' => array(self::HAS_MANY, 'SessionRefreshTokens', 'client_id'),
			'sessions' => array(self::HAS_MANY, 'Sessions', 'client_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'secret' => 'Secret',
			'name' => 'Name',
			'auto_approve' => 'Auto Approve',
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
		$criteria->compare('secret',$this->secret,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('auto_approve',$this->auto_approve);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Clients the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
