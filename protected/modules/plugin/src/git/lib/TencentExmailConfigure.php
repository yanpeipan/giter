<?php
class TencentExmailConfigure extends Configure {

	public $tencent_exmail_client_id;
	public $tencent_exmail_client_secret;

	public function tableName()
	{
		return '{{configure}}';
	}

	public function rules() {
		return array(
				array('tencent_exmail_client_secret, tencent_exmail_client_id', 'required')
			    );
	}

	public  function search()
	{
		return new CActiveDataProvider('configure', array(
			'criteria' => array(
				'condition' => 'name in ("tencent_exmail_client_secret", "tencent_exmail_client_id")',
			)
					));
	}
}
?>
