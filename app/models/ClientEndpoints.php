<?php

/**
 * This is the model class for table "oauth_client_endpoints".
 *
 * The followings are the available columns in table 'oauth_client_endpoints':
 * @property string $id
 * @property string $client_id
 * @property string $redirect_uri
 *
 * The followings are the available model relations:
 * @property Clients $client
 */
class ClientEndpoints extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_client_endpoints';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, redirect_uri', 'required'),
			array('client_id', 'length', 'max'=>40),
			array('redirect_uri', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, redirect_uri', 'safe', 'on'=>'search'),
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
			'redirect_uri' => 'Redirect Uri',
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
		$criteria->compare('redirect_uri',$this->redirect_uri,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ClientEndpoints the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
