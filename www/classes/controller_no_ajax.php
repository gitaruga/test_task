<?
class controller_no_ajax extends controller{
	
	public function __construct(){
		
		parent::__construct();
		site::$view->addParameter('model_name', $this->model->getName());
		$top_menu=array(
			array('url'=>"/list/traffic/", 'title'=>'Трафик'),
			array('url'=>"/list/wares/", 'title'=>'Товары'),
			array('url'=>"/list/marks/", 'title'=>'Марки')
		);
		switch($this->model->getName()){
			case 'traffic':
				$top_menu[0]['active']=true;
			break;case 'wares':
				$top_menu[1]['active']=true;
			break;case 'marks':
				$top_menu[2]['active']=true;
			break;
		}
		site::$view->addParameter('top_menu', $top_menu); 
		
	}
}
?>