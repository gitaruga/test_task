<?
class view{
	protected $parameters;
	protected $template_name;

	public function addParameter($parameter_name, $parameter){
		$this->parameters[$parameter_name]=$parameter;
	}

	public function setTemplateName($template_name){
		$this->template_name=$template_name;
	}

	public function show(){
		
		if( !$this->template_name ) die("Имя шаблона не установлено");
		if( !file_exists(site::$settings['base_path']."/templates/view_{$this->template_name}.php") )
			die("файл шаблона не найден");

		$BASE_TEMPLATE_PATH=site::$settings['base_path'].'/templates/';
		extract($this->parameters, EXTR_OVERWRITE);
		include site::$settings['base_path']."/templates/view_{$this->template_name}.php";
	}
}
?>