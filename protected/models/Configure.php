<?php
class ConfigureModel extends CActiveRecord {

	public $value;
	public $name;
	
	public function tableName() {
		return '{{configure}}';
	}
}
