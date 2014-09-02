<?php
require_once(dirname(__FILE__)."/config.php");
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',
	// application components
	'components'=>array(
       /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
       */
        'curl'=>array(
            'class'=>'application.extensions.curl.Curl',
              //eg.
               'options'=>array(
                    'timeout'=>0,
                    'setOptions'=>array(
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
                        CURLOPT_REFERER   => 'http://www.soku.com/channel/movie______1.html',
                        CURLOPT_COOKIE    => 'SOKUSESSID=1333262037634D8K; JSESSIONID=abcPTV03Pn6d6boeZoLAt',
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ),
                ),
        ),
		// uncomment the following to use a MySQL database
         //db_vo
	     //==============================DB config===============================================
     	'db'=>array(
		    'class'=>'CDbConnectionExt',
		    'connectionString' => 'mysql:host='.LUXTONE_DBHOST_WRITE.';port=3306;dbname='.LUXTONE_DBNAME,
		    'emulatePrepare' => true,
		    'username' => LUXTONE_DBUSER,
		    'password' => LUXTONE_DBPW,
		    'charset' => LUXTONE_DBCHARSET,
		    'tablePrefix'=>LUXTONE_DBTABLEPRE,
		    'slaveConfig'=>array(
	            array('connectionString'=>'mysql:host='.LUXTONE_DBHOST_WRITE.';dbname='.LUXTONE_DBNAME.';port=3306','username'=>LUXTONE_DBUSER,'password'=>LUXTONE_DBPW),
	        ),
	    ),

	),
	
    'import'=>array(
        'application.models.*',
        'application.extensions.*',
        'application.components.*',
        'application.extensions.catch.*',
        'application.extensions.catch.sohu.sdk.*',
        'ext.soku.*',
        'ext.curl.*',
        'ext.ApiTest.*',
        'ext.checkcfg.*',
        'ext.ZSplayPY.*',
        'application.vendors.*',
        'application.vendors.Rediska_0_5_6.*',
        'ext.letter.*',
        'ext.upyun.*',
        'ext.punica.puti.*',
        'application.extensions.ApkParser.*',
        'ext.apns.*',
        'ext.Mongo.*',
    ),
    'params'=>array(
    	'apns_pwd'=>'Luxtone_2012',
        // this is used in contact page
        'adminEmail'=>'webmaster@example.com',
        //每日更新
        'NewVideoYear'=>'2012',
        'OAuth2'=>array(
            'sohu'=>array(
                'client_id' =>'1dd00ee3f3563a2338322db018737029',
                'secret_key'=>'P2kzG3TzNJd6rGZk206JtwLGLNQZyxT0',
                'callback'  =>'http://open.tv.sohu.com/SDK/sotv4php/includes/callback.php',
            ),
        ),
        'source'=>array(//播放来源配置
            ''=>'播放来源',
            'sohu' =>'搜狐高清',
            'sohublog'=>'搜狐播客',
            'youku'=>'优酷',
            'tudou'=>'土豆',
            'baofeng'=>'暴风影音',
            'qvod'=>'Qvod快播',
            'gvod'=>'Gvod迅播',
            'baidu'=>'百度影音',
            'qiyi'=>'奇异高清',
            'ku6'=>'酷6',
            '56'=>'56高清',
            'sina'=>'新浪高清',
            'sinaboke'=>'新浪播客',
            '6'=>'6间房',
            'qq'=>'qq视频',
            'pipi'=>'皮皮影音',
        ),    
        'free'=>array(//收费情况配置
            '免费',
            '收费'
        ),                
        'genuine'=>array(//正片非正片配置
            '非正片',
            '正片',
            '其他'
        ),       
        'resolution'=>array(//分辨率配置
            '普通', 
            '高清', 
            '超清',
            '其他'
        ),
       'year'=>Array//年份配置
        (
            '2012'  => '2012',
            '2011'  => '2011',
            '2010'  => '2010',
            '2009'  => '2009',
            '2008'  => '2008',
            '2007'  => '2007',
            '2006'  => '2006',
            '2005'  => '2005',
            '2004'  => '2004',
            '2003'  => '2003',
            '2002'  => '2002',
            '2001'  => '2001',
            '2000'  => '2000',
            '90年代 '=> '90年代',
            '80年代'=>'80年代',
            '70年代'=>'70年代',
            '更早 '  => '更早',
        ),
        'score'=>array(//星级评分
            '0'=>'0星',
            '1'=>'1星',
            '1.5'=>'1.5星',
            '2'=>'2星',
            '2.5'=>'2.5星',
            '3'=>'3星',
            '3.5'=>'3.5星',
            '4'=>'4星',
            '4.5'=>'4.5星',
            '5'=>'5星',
        ),  
        'fenji'=>array(
            '第1集','第2集','第3集','第4集','第5集','第6集','第7集','第8集','第9集','第10集',
            '第11集','第12集','第13集','第14集','第15集','第16集','第17集','第18集','第19集','第20集',
            '第21集','第22集','第23集','第24集','第25集','第26集','第27集','第28集','第29集','第30集',
            '第31集','第32集','第33集','第34集','第35集','第36集','第37集','第38集','第39集','第40集',
            '第41集','第42集','第43集','第44集','第45集','第46集','第47集','第48集','第49集','第50集',
            '第51集','第52集','第53集','第54集','第55集','第56集','第57集','第58集','第59集','第60集',
            '第61集','第62集','第63集','第64集','第65集','第66集','第67集','第68集','第69集','第70集',
            '第71集','第72集','第73集','第74集','第75集','第76集','第77集','第78集','第79集','第80集',
            '第81集','第82集','第83集','第84集','第85集','第86集','第87集','第88集','第89集','第90集',
            '第91集','第92集','第93集','第94集','第95集','第96集','第97集','第98集','第99集','第100集',
            '第101集','第102集','第103集','第104集','第105集','第106集','第107集','第108集','第109集','第110集',
        ),
        'geshi'=>array(//视频格式avi、wmv、mpeg、mp4、mov、mkv、flv、f4v、m4v、rmvb、rm、3gp、dat、ts、mts、vob
            ''=>'视频格式',
            'avi'=>'avi',
            'wmv'=>'wmv',
            'mpeg'=>'mpeg',
            'mp4'=>'mp4',
            'mov'=>'mov',
            'mkv'=>'mkv',
            'flv'=>'flv',
            'f4v'=>'f4v',
            'm4v'=>'m4v',
            'rmvb'=>'rmvb',
            'rm'=>'rm',
            '3gp'=>'3gp',
            'dat'=>'dat',
            'ts'=>'ts',
            'mts'=>'mts',
            'vob'=>'vob',
            'swf'=>'swf',
        ),
        'cate_arr'=>array(//添加视频分集跳转时用
            3,//电视剧
        ),
        'sohu'=>array(//视频抓取对照数组
            'movie_area'=>array(//电影地区
                '1'=>'其他', 
                '2'=>'华语', 
                '3'=>'好莱坞', 
                '4'=>'欧洲', 
                '5'=>'日本', 
                '6'=>'韩国',
            ),
            'movie_type'=>array(//电影类型
                '1'=>'其他', 
                '2'=>'爱情片',
                '3'=>'喜剧片', 
                '4'=>'动作片', 
                '5'=>'科幻片',
                '6'=>'战争片', 
                '7'=>'恐怖片',
                '8'=>'风月片', 
                '9'=>'剧情片', 
                '10'=>'音乐片',
                '11'=>'武侠片',
            ),
            'tv_area'=>array(//电视剧地区
                '1' =>'其他', 
                '7' =>'内地',
                '8' =>'港剧',
                '9' =>'台剧',
                '10'=>'韩剧',
                '11'=>'美剧',
                '12'=>'泰剧',
            ),
            'tv_type'=>array(//电视剧类型
                '1'  => '其他', 
                '12' => '偶像剧',
                '13' => '家庭伦理剧',
                '14' => '历史剧',
                '15' => '年代剧',
                '16' => '言情剧',
                '17' => '武侠剧',
                '18' => '古装剧',
                '19' => '都市剧',
                '20' => '农村剧',
                '21' => '军事战争剧',
                '22' => '悬疑剧',
                '23' => '奇幻科幻剧',
                '24' => '动作剧',
                '25' => '谍战剧',
            ),
            'animal_type'=>array(//动漫类型
                '1'  => '其他', 
                '33' => '搞笑',
                '34' => '剧情',
                '35' => '冒险',
                '36' => '魔幻',
                '37' => '励志',
                '38' => '体育',
                '39' => '益智',
                '40' => '童话',
                '41' => '动作',
                '42' => '推理',
                '43' => '怀旧',
                '44' => '历史',
                '45' => '神话',
                '46' => '青春',
                '47' => '爱情',
            ),
            'zongyi_area'=>array(
                '1' =>'其他',
                '32'=>'内地',
            ),
            'zongyi_type'=>array(
                '1' => '其他',
                '48' => '访谈',
                '49' => '选秀',
                '50' => '竞技',
                '51' => '时尚',
                '52' => '音乐',
                '53' => '曲艺',
                '54' => '交友',
            ),
            
        ),
        'soku'=>array(
            'area'=>array(
                'movie'=>array(
                    '13'=>'德国',
                    '14'=>'法国',
                    '15'=>'英国',
                    '16'=>'美国',
                    '17'=>'大陆',
                    '18'=>'香港',
                    '18'=>'澳门',
                    '5'=>'日本',
                    '6'=>'韩国',
                    '20'=>'泰国',
                    '21'=>'印度',
                    '1'=>'其他',
                ),
                'anime'=>array(
                    '24'=>'大陆',
                    '25'=>'香港',
                    '26'=>'台湾',
                    '27'=>'韩国',
                    '28'=>'日本',
                    '29'=>'美国',
                    '30'=>'英国',
                    '31'=>'泰国',
                    '1'=>'其他',
                ),
                'teleplay'=>array(
                    '7'=>'大陆',
                    '8'=>'香港',
                    '9'=>'台湾',
                    '10'=>'韩国',
                    '22'=>'日本',
                    '11'=>'美国',
                    '23'=>'英国',
                    '12'=>'泰国',
                    '1'=>'其他',
                ),
                'variety'=>array(
                    '32'=>'大陆',
                    '33'=>'香港',
                    '34'=>'台湾',
                    '35'=>'韩国',
                    '36'=>'日本',
                    '1'=>'其他',
                ),
            ),
            'vtype'=>array(//历史(57)，军事(58)，人物(59)，社会(60)，自然(61)，财经(62)，幕后(63)，其他(1)，不限(NULL)
                'jilupian'=>array(
                    '57'=>'历史',
                    '58'=>'军事',
                    '59'=>'人物',
                    '60'=>'社会',
                    '61'=>'自然',
                    '62'=>'财经',
                    '63'=>'幕后',
                    '1'=>'其他',
                ),
            ),
        ),
        
        
        
        'imgUrl' => 'http://img1.red16.com/puti/',          //封面路径配置
		'img_url1'=>'http://dev-puti.16tree.com/',
        //'avatar_url'=>'http://img1.red16.com/',
        // 'avatar_size'=>array(
                // 'large'=>100,
                // 'middle'=>80,
                // 'small'=>50,
        // ),
    ),
    
);










