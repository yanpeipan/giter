<?php
	class SConfig extends CActiveRecord{
		public static function model($className=__CLASS__)
	    {
	        return parent::model($className);
	    }

	    /**
	     * @return string the associated database table name
	     */
	    public function tableName()
	    {
	        return '{{s_config}}';
	    }
		public function rules()
	    {
	        // NOTE: you should only define rules for those attributes that
	        // will receive user inputs.
	        return array(
	        	//array('id, cfg_value','required'),
	            // The following rule is used by search().
	            // Please remove those attributes that should not be searched.
	        );
	    }
		public function attributeLabels()
	    {
	        return array(
	            'id' => 'ID',
	            'cfg_name' => '配置名',
	            'cfg_value' => '配置设定值',
	            'cfg_commment'=>'配置说明',	            
	        );
	    }
	    public function search()
	    {
	        // Warning: Please modify the following code to remove attributes that
	        // should not be searched.
	
	        $criteria=new CDbCriteria;
	        return new CActiveDataProvider(get_class($this), array(
	            'criteria'=>$criteria,
	            'pagination'=>array(
	               'pageSize'=>30,
	            ),
	        ));
	    }	
	}


?>