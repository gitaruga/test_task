<?
class site{
	const def_controller_name='list';
	static public $settings = array();
	static public $view;
	static public $DB_connect_link;

	protected $controller;
	
	function __construct(){
		$this->FURL();
		site::$settings['base_path']=dirname(dirname(__FILE__));
		site::$view = new view();
		$this->startDB();
	}

	public function execution(){

		// определение имени контроллера
		$controller_name = site::$settings['URL'] ? site::$settings['URL'][0] : site::def_controller_name;
		$this->load_controller($controller_name);
		$this->controller->execution();
	}

	public function show(){
		site::$view->show();
	}

	protected function FURL(){ // Получаем массив ЧПУ
		
		$url = urldecode($_SERVER['REQUEST_URI']);
		
		$Q_pos=mb_strpos($url,'?');
		if( $Q_pos!==false )
			$url=mb_substr($url,0,$Q_pos); // Вырезаем гет запрос
		
		$url_arr = explode('/',$url);
		array_shift($url_arr);
		
		if( !end($url_arr) ){ // если нужно извлечь последний элемент
			if( count($url_arr)>1 )
				array_pop($url_arr);
			else
				$url_arr=false;
		}
		
		site::$settings['URL']=$url_arr;
	
	}

	protected function load_controller($controller_name){
		if( !file_exists(site::$settings['base_path']."/classes/controller_$controller_name.php") ){
			$controller_name=site::def_controller_name;
		}
		$class_name="controller_$controller_name";
		$this->controller = new $class_name;
	}

	protected function startDB(){

		include site::$settings['base_path']."/db_init.php";
		
		site::$DB_connect_link=mysql_connect($DBhost, $DBuser, $DBpass) or die("DB Connection FALL");
		mysql_select_db($DBname) or die ("NO DB select");
			
		mysql_query("SET character_set_client='utf8'");
		mysql_query("SET character_set_results='utf8'");
		mysql_query("SET collation_connection='utf8_general_ci'");

	}
}
?>