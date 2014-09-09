<?php
/**
 * Admin的基础Controller
 * @author xiongchuan <xiongchuan@luxtonenet.com>
 */

//class AdminBaseController extends SBaseController
class AdminBaseController extends SBaseController
{
	
	protected $config = array();
	
	public function init()
	{
		parent::init();
		$this->GetSystemConfig();
	}
	
	//获取系统配置
	public function GetSystemConfig(){
		//获取域名
		$domain = SystemConfig::Get('SYSTEM_DOMAIN',null,'USER');
		//Yii::app()->params['domain'] = $domain[0]['cfg_value'];
		//获取boss系统域名
		$boss = SystemConfig::Get('SYSTEM_BOSS_DOMAIN',null,'USER');
		//Yii::app()->params['boss'] = $boss[0]['cfg_value'];
		//获取token
		$token = SystemConfig::Get('SYSTEM_TOKEN_KEY',null,'USER');
		//Yii::app()->params['token'] = $token[0]['cfg_value'];
		//视频状态 是否完结
		//Yii::app()->params['isend']     = SystemConfig::GetArrayValue('VIDEO_ISEND');
		//视频清晰度
		//Yii::app()->params['resolution'] = SystemConfig::GetArrayValue('VIDEO_CLARITY');
		//是否授权
		//Yii::app()->params['status']    = SystemConfig::GetArrayValue('VIDEO_ISSHOW');
		//是否有视频封面
		//Yii::app()->params['is_pic']     = SystemConfig::GetArrayValue('VIDEO_HASCOVER');
		//是否正片
		//Yii::app()->params['playtype']    = SystemConfig::GetArrayValue('VIDEO_PLAYTYPE');
		//是否免费
		//Yii::app()->params['free']    = SystemConfig::GetArrayValue('VIDEO_CHARGE');
		//适合年龄
		//Yii::app()->params['ages']    = SystemConfig::GetArrayValue('VIDEO_AGE');
		//获取接入源
		Yii::app()->params['source']  = SystemConfig::GetArrayValue('VIDEO_SOURCE',null,'USER');
		//获取主菜单
		Yii::app()->params['MAINMENU']   = SystemConfig::Get("MAINMENU",null,'USER');
		
		//获取图片地址
		$uri = "";
		$website = SystemConfig::GetArrayValue("SYSTEM_DOMAIN",null,"USER");
		if($website){
			$uri = key($website)."/";
		}
		$cdn = SystemConfig::Get("SYSTEM_CDN",null,"USER");
		if($cdn){
			$cdn_config = SystemConfig::Get("SYSTEM_CDN_CONFIG_UPYUN",null,"USER");
			if($cdn_config && is_array($cdn_config)){
				$arr = json_decode($cdn_config[0]['cfg_value'],true);
				isset($arr['uri']) && $uri = $arr['uri'];
			}
		
		}
		Yii::app()->params['imgUrl'] = $uri;
		Yii::app()->params['picUrl'] = $uri;
		Yii::app()->params['yunimg'] = $uri;
		//获取子菜单
		$submenus    = array();
		foreach(Yii::app()->params['MAINMENU'] as $menu){
			$submenu = SystemConfig::GetArrayValue("SUBMENU",$menu['id'],'USER');
			if(!empty($submenu)){
				$submenus[$menu['id']] = $submenu;
			}
		}	
		Yii::app()->params['SUBMENU'] = $submenus;	
		//获取ICONS
		Yii::app()->params['ICONS']      = SystemConfig::GetArrayValue("ICONS",null,'USER');
	}

}
