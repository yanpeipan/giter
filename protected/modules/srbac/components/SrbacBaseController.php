<?php
/**
 * Admin的基础Controller
 * @author xiongchuan <xiongchuan@luxtonenet.com>
 */

class SrbacBaseController extends Controller
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
		Yii::app()->params['domain'] = $domain[0]['cfg_value'];
		//获取boss系统域名
		$boss = SystemConfig::Get('SYSTEM_BOSS_DOMAIN',null,'USER');
		Yii::app()->params['boss'] = $boss[0]['cfg_value'];
		//获取token
		$token = SystemConfig::Get('SYSTEM_TOKEN_KEY',null,'USER');
		Yii::app()->params['token'] = $token[0]['cfg_value'];
		//视频状态 是否完结
		Yii::app()->params['isend']     = SystemConfig::GetArrayValue('VIDEO_ISEND');
		//获取主菜单
		Yii::app()->params['MAINMENU']   = SystemConfig::Get("MAINMENU",null,'USER');
		
		
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
