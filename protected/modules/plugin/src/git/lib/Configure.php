<?php
class Configure extends CActiveRecord {

	public $value;
	public $name;
	
	public function tableName() {
		return '{{configure}}';
	}
}
