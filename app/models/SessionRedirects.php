<?php

/**
 * This is the model class for table "oauth_session_redirects".
 *
 * The followings are the available columns in table 'oauth_session_redirects':
 * @property string $session_id
 * @property string $redirect_uri
 *
 * The followings are the available model relations:
 * @property Sessions $session
 */
class SessionRedirects extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oauth_session_redirects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('session_id, redirect_uri', 'required'),
			array('session_id', 'length', 'max'=>10),
			array('redirect_uri', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, redirect_uri', 'safe', 'on'=>'search'),
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
			'session' => array(self::BELONGS_TO, 'Sessions', 'session_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'session_id' => 'Session',
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

		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('redirect_uri',$this->redirect_uri,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionRedirects the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
