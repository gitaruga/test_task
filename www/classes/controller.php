<?
class controller{
	const def_model_name='traffic';
	protected $model;
	
	public function __construct(){

		$model_name = isset(site::$settings['URL'][1]) ? site::$settings['URL'][1] : controller::def_model_name;
		$this->load_model($model_name);
	
	}
	
	public function	execution(){

	}

	protected function load_model($model_name){
		
		if( !file_exists(site::$settings['base_path']."/classes/model_$model_name.php") )
			$model_name=controller::def_model_name;
		$class_name="model_$model_name";
		$this->model = new $class_name;
	}
}
?>