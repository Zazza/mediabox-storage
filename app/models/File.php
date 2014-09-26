<?php

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property string $id
 * @property integer $deleted
 * @property string $parent
 * @property string $name
 * @property string $size
 * @property string $extension
 * @property string $data
 * @property string $added
 * @property string $checked
 */
class File extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, size, extension', 'required'),
			array('deleted', 'numerical', 'integerOnly'=>true),
			array('parent, size', 'length', 'max'=>512),
			array('name', 'length', 'max'=>256),
			array('extension', 'length', 'max'=>8),
			array('added, checked', 'safe'),
		);
	}

	public function relations()
	{
		return array(
            'Image'=>array(self::HAS_ONE, 'Image', 'file_id'),
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
