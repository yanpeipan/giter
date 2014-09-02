<?php
class CeSu extends BaseApi{
	private $url_source = array('sina','youku','youtube','tudou','ku6','umiwi','sohu','qq','qiyi','cntv','56','m1905','letv','ifeng','pps','pptv','163',
						  'joy','top100','vhxsd','chaoxing','china','tv189','yinyuetai','funshion','xunlei');
    private $url_source_china = array('新浪','优酷','youtube','土豆','酷6','优米','搜狐','腾讯','奇异','CNTV','56','电影网','乐视','凤凰网','PPS','PPTV','网易',
						  			  '激动网','巨鲸','火星','超星','艺术中国','TV189','音悦台','风行网','迅雷看看');
	private $url_default = array(
		0=>'http://video.sina.com.cn/m/hshlb_61459187.html',
		1=>'http://v.youku.com/v_show/id_XNDI4NDY0NzQw.html',
		3=>'http://www.tudou.com/albumplay/FbY9MSezbNo.html',
		4=>'http://v.ku6.com/film/show_130538/nbVHecKDS_u5ct-sZ2yQgQ...html',
		5=>'http://chuangye.umiwi.com/2012/0429/68814.shtml',
		6=>'http://tv.sohu.com/20090812/n265893812.shtml',
		7=>'http://v.qq.com/cover/n/nc1917aomu4ev6n.html',
		8=>'http://www.iqiyi.com/dianshiju/20120717/de5535367ea30de9.html',
		9=>'http://dianshiju.cntv.cn/cangtianshengtu/classpage/video/20120703/101437.shtml',
		10=>'http://www.56.com/u52/v_Njk5NzI4NjI.html',
		11=>'http://www.m1905.com/vod/play/541875.shtml',
		12=>'http://www.letv.com/ptv/pplay/54534/1.html',
		13=>'http://v.ifeng.com/gongkaike/zirankexue/201108/08c165fc-cf9f-44fd-a9e4-b74edb30448b.shtml',
		14=>'http://v.pps.tv/play_31X70Y.html#from_splay',
		15=>'http://v.pptv.com/show/QyYysBhib7iayPDXU.html',
		16=>'http://v.163.com/zongyi/V6LQSJ9UN/V85III6V1.html',
		17=>'http://v.joy.cn/teleplay/detail/70028738/1.htm',
		22=>'http://21cn.tv189.com/v/1/200942.htm',
		23=>'http://www.yinyuetai.com/video/568628',
		24=>'http://www.funshion.com/subject/play/104047/1/327680/1',
		25=>'http://vod.kankan.com/v/64/64210.shtml?id=731021',
	);		

    /**
	 * 测试速度
	 * @param $source
	 */
	public function go(){
		header("Content-Type:application/json");
		$source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
		if($source == ''){
			$sql = 'SELECT source,name,icon,url FROM {{icon}} WHERE status=1';
			$cmd = Yii::app()->db_puti->createCommand($sql);
			$rows = $cmd->queryAll();
			
			for($i=0;$i<count($rows);$i++){
				$rows[$i]['icon'] = Yii::app()->params['icon_url'].$rows[$i]['icon'];
			}
			
			$info = $rows;
		}else{
			$sql = "SELECT t.source,name,icon,tv_url AS url FROM {{v_tv}} AS t LEFT JOIN {{icon}} AS i ON t.source=i.source WHERE t.source=:source LIMIT 1";
			$cmd = Yii::app()->db_puti->createCommand($sql);
			$info = $cmd->bindValue(':source',$source)->queryAll();
			if($info){
				$info[0]['icon'] = Yii::app()->params['icon_url'].$info[0]['icon'];
			}
		}
		return $info;
	}

	/**
	 * 测试速度入库
	 * @param $data 测速返回的结果
	 */
	public function addRecord(){
	    /*
		$info = array("uid"=>"0",
					    "report"=>array(
					  		  22=>array("speed"=>"330.42","url"=>"http://21cn.tv189.com/v/1/200942.htm","real_url"=>"aaa",'parse_time'=>'aa'),
					  		  //15=>array("speed"=>"2074.60","url"=>"http://v.pptv.com/show/wNXUUrogkM4xrxc.html"),
					  		  //16=>array("speed"=>"443.89","url"=>"http://21cn.tv189.com/v/1/200942.htm"),
					  		  //14=>array("speed"=>"287.42","url"=>"http://v.pps.tv/play_31X70Y.html#from_splay"),
					  		  //11=>array("speed"=>"474.66","url"=>"http://www.m1905.com/vod/play/541875.shtml"),
					  		  //12=>array("speed"=>"245.04","url"=>"http://www.letv.com/ptv/pplay/79383/7.html"),
					  		  //3=>array("speed"=>"472.02","url"=>"http://www.tudou.com/playlist/p/a87141i147364272.html"),
					  		  //1=>array("speed"=>"124.03","url"=>"http://v.youku.com/v_show/id_XNDI4NDY0NzQw.html"),
					  		  //10=>array("speed"=>"63.45","url"=>"http://www.56.com/u52/v_Njk5NzI4NjI.html"),
					  		  //0=>array("speed"=>"161.74","url"=>"http://video.sina.com.cn/m/hshlb_61459187.html"),
					  		  //7=>array("speed"=>"452.92","url"=>"http://v.qq.com/cover/w/wd7axgo2say3egi/u0010CZg3xz.html"),
					  		  //6=>array("speed"=>"477.64","url"=>"http://tv.sohu.com/20090812/n265893812.shtml"),
					  		  //5=>array("speed"=>NULL,"url"=>"http://chuangxin.umiwi.com/2011/0518/36871.shtml"),
					  		  //4=>array("speed"=>NULL,"url"=>"http://v.ku6.com/film/show_130538/nbVHecKDS_u5ct-sZ2yQgQ...html"),
					  		  //8=>array("speed"=>"356.59","url"=>"http://www.iqiyi.com/dianshiju/20120717/de5535367ea30de9.html"),
					  		  //9=>array("speed"=>"470.78","url"=>"http://dianshiju.cntv.cn/cangtianshengtu/classpage/video/20120703/101437.shtml"),
					  ),
  					  "tid"=>"0",
  					  "IP"=>"192.168.1.51",
                      "client_type"=>'test type',
                      "client_version"=>'test ver',
                      'wan_ip'=>'wan_ip',
		);
		$json = json_encode($info);
         * 
         */
        //$json = '{"report":{"13":{"parse_time":"549","real_url":"http://vslb.tv189.cn/LzIwMTEvMDQvMjIvNTEvZmFkN2JlNTBlODRjMTE4YjExN2M5MmRjNzFhYWVlMTA4MHAtMDAwMC5mbHY\u003d?sign\u003dD57DE5F84F351BAE798B108041DB9F59\u0026tm\u003d5077837f\u0026vw\u003d1\u0026ver\u003dv1.1\u0026end\u003d300","speed":"114.69","url":"http://21cn.tv189.com/v/1/200942.htm"},"23":{"parse_time":"549","real_url":"http://vslb.tv189.cn/LzIwMTEvMDQvMjIvNTEvZmFkN2JlNTBlODRjMTE4YjExN2M5MmRjNzFhYWVlMTA4MHAtMDAwMC5mbHY\u003d?sign\u003dD57DE5F84F351BAE798B108041DB9F59\u0026tm\u003d5077837f\u0026vw\u003d1\u0026ver\u003dv1.1\u0026end\u003d300","speed":"114.69","url":"http://21cn.tv189.com/v/1/200942.htm"},"25":{"parse_time":"549","real_url":"http://vslb.tv189.cn/LzIwMTEvMDQvMjIvNTEvZmFkN2JlNTBlODRjMTE4YjExN2M5MmRjNzFhYWVlMTA4MHAtMDAwMC5mbHY\u003d?sign\u003dD57DE5F84F351BAE798B108041DB9F59\u0026tm\u003d5077837f\u0026vw\u003d1\u0026ver\u003dv1.1\u0026end\u003d300","speed":"114.69","url":"http://21cn.tv189.com/v/1/200942.htm"},"22":{"parse_time":"549","real_url":"http://vslb.tv189.cn/LzIwMTEvMDQvMjIvNTEvZmFkN2JlNTBlODRjMTE4YjExN2M5MmRjNzFhYWVlMTA4MHAtMDAwMC5mbHY\u003d?sign\u003dD57DE5F84F351BAE798B108041DB9F59\u0026tm\u003d5077837f\u0026vw\u003d1\u0026ver\u003dv1.1\u0026end\u003d300","speed":"114.69","url":"http://21cn.tv189.com/v/1/200942.htm"},"17":{"parse_time":"489","real_url":"http://61.55.168.6/40808d43c6e12c272317ae1da852a985/vodflv/%E7%94%B5%E5%BD%B1/%E4%BD%B3%E5%8D%8E/110224-280k-%E6%83%8A%E5%A4%A9%E5%A4%A7%E7%81%BE%E9%9A%BE-1a.flv","speed":"83.11","url":"http://v.joy.cn/movie/detail/70023137.htm"},"24":{"parse_time":"3842","real_url":"http://182.118.38.35:80/661EE036587B9C89B95BF76AF6C4134FF9898E35/Ats-1.ts\r","speed":"106.80","url":"http://www.funshion.com/subject/play/104047/1/327680/1"},"15":{"parse_time":"2400","real_url":"http://113.57.239.29:82/885681dc47fa668e8c7718d7cfa004a1_0_20.mp4.ts","speed":"95.84","url":"http://v.pptv.com/show/QyYysBhib7iayPDXU.html"},"16":{"parse_time":"1007","real_url":"http://flv.bn.netease.com/tvmrepo/2012/7/M/V/E85IIHDMV-mobile.mp4","speed":"115.68","url":"http://v.163.com/zongyi/V6LQSJ9UN/V85III6V1.html"},"14":{"parse_time":"439","real_url":"http://vurl.pps.tv/ugc/e/87/eeaa92b9f49513699964b3ef4506f54ec50f8073/eeaa92b9f49513699964b3ef4506f54ec50f8073.pfv","speed":"112.23","url":"http://v.pps.tv/play_31X70Y.html#from_splay"},"11":{"parse_time":"234","real_url":"http://flv1.vodfile.m1905.com/movie/1206/120614BB66670E0446CA0C.flv","speed":"106.46","url":"http://www.m1905.com/vod/play/541875.shtml"},"12":{"parse_time":"1684","real_url":"http://123.125.89.42/5/11/29/2051248700.0.letv?crypt\u003d7784891aa7f2e78\u0026b\u003d385\u0026qos\u003d4\u0026level\u003d20\u0026nc\u003d1\u0026bf\u003d16\u0026p2p\u003d1\u0026video_type\u003dflv\u0026check\u003d1\u0026tm\u003d1350028800\u0026key\u003d9cf78b2e6580ab277d74c998c927831c\u0026proxy\u003d2071812427\u0026cipi\u003d1928742472\u0026s\u003d3\u0026df\u003d5/11/29/2051248700.0.flv\u0026br\u003d385","speed":"102.91","url":"http://www.letv.com/ptv/pplay/42270/52.html"},"3":{"parse_time":"810","real_url":"http://v3.tudou.com/v.ts?it\u003d150266288\u0026s\u003d0\u0026e\u003d10\u0026st\u003d2","speed":"152.81","url":"http://www.tudou.com/albumplay/FbY9MSezbNo.html"},"10":{"parse_time":"189","real_url":"http://f2.r.56.com/f2.c80.56.com/flvdownload/9/21/134197613926hd.flv","speed":"97.56","url":"http://www.56.com/u52/v_Njk5NzI4NjI.html"},"1":{"parse_time":"1643","real_url":"http://f.youku.com/player/getMpegtsPath/st/mp4/fileid/03000806005004BC77D0B603BAF2B146F133EF-ADB2-D892-CEF5-1108DA5ECD09/ipad0_0.ts?KM\u003d1a16a2afcf717e26e\u0026start\u003d0\u0026end\u003d9\u0026ts\u003d9.44\u0026html5\u003d1\u0026seg_keyframe\u003d1\u0026seg_no\u003d0\u0026seg_time\u003d0\r","speed":"102.61","url":"http://v.youku.com/v_show/id_XNDI4NDY0NzQw.html"},"0":{"parse_time":"203","real_url":"http://v.iask.com/v_play_ipad.php?vid\u003d59539965","speed":"116.49","url":"http://video.sina.com.cn/m/hshlb_61459187.html"},"7":{"parse_time":"-1","real_url":"-1","speed":"-1","url":"http://v.qq.com/cover/b/bqtvb83wvhkqe4d.html"},"6":{"parse_time":"2736","real_url":"http://61.135.183.46/ipad?file\u003d/123/92/MlosGYn32Wd8YBKtEeb7i7.mp4\u0026start\u003d0\u0026end\u003d10\u0026sig\u003dmr0yqQ4dWkPZOYdL57WjUidQyooatjoWXQwQpA..","speed":"90.08","url":"http://tv.sohu.com/20090812/n265893812.shtml"},"5":{"parse_time":"115","real_url":"http://vod.umiwi.com/vod/2010/08/19/d893053ffb1ec063976074cddd94aff6.mp4","speed":"87.31","url":"http://chuangye.umiwi.com/2010/0820/9596.shtml"}},"uid":183,"tid":12365,"wan_ip":"192.168.1.13","client_type":"testcl","client_version":"testver"}';
		//$json = file_get_contents("php://input");
		$data = isset($_POST['data']) ? json_decode($_POST['data'],true) : '';
		$json_array = array('status'=>'0');
		if(!empty($data)){
			$time = time();
            $tid            = isset($data['tid']) ? $data['tid'] : 0;
            $uid            = isset($data['uid']) ? $data['uid'] : 0;
            $ip             = isset($data['IP'])  ? $data['IP']  : "";
            $wan_ip         = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
            $client_type    = isset($data['client_type']) ? $data['client_type'] : "";
            $client_version = isset($data['client_version']) ? $data['client_version'] : "";
			$sql = "INSERT INTO {{speed}} SET 
			        uid=:uid,
			        tid=:tid,
			        IP=:IP,
			        source=:source,
			        time=:time,
			        speed=:speed,
			        url=:url,
			        parse_time=:parse_time,
			        wan_ip=:wan_ip,
			        real_url=:real_url,
			        client_type=:client_type,
			        client_version=:client_version
			        ";
			$re = Yii::app()->db_puti->createCommand($sql);
			foreach($data['report'] as $key=>$value){
				if(!empty($value) && isset($value['speed']) && isset($value['url']) && isset($value['parse_time'])){
				    $real_url = isset($value['real_url']) ? $value['real_url'] : "";
                    $params = array(
                        ':uid'            => $uid,
                        ':tid'            => $tid,
                        ':IP'             => $ip,
                        ':time'           => $time,
                        ':source'         => $this->url_source[$key],
                        ':speed'          => $value['speed'],
                        ':url'            => $value['url'],
                        ':parse_time'     => $value['parse_time'],
                        ":wan_ip"         => $wan_ip,
                        ":real_url"       => $real_url,
                        ':client_type'    => $client_type,
                        ":client_version" => $client_version
                    );
					$re->bindValues($params)->execute();
				}				
			}

			$json_array = array('status'=>'1');
		}
		return $json_array;
	} 
	


}
