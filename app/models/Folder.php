<?php

/**
 * This is the model class for table "folder".
 *
 * The followings are the available columns in table 'folder':
 * @property string $id
 * @property integer $deleted
 * @property string $parent
 * @property string $name
 * @property string $added
 * @property string $checked
 */
class Folder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'folder';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('deleted', 'numerical', 'integerOnly'=>true),
			array('parent', 'length', 'max'=>512),
			array('name', 'length', 'max'=>256),
			array('added, checked', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, deleted, parent, name, added, checked', 'safe', 'on'=>'search'),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
