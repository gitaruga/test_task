<?
class controller_list extends controller_no_ajax{
	
	public function	execution(){

		site::$view->addParameter('TableHead', $this->model->getTableHead());
		site::$view->addParameter('deleteText', $this->model->getDeleteText());
		
		$paginator= new paginator();
		$res=$paginator->execution(array(
			'count_sql'=>$sql=$this->model->getCountSQL(),
			'base_sql'=>$sql=$this->model->getListSQL(),
			'page'=>site::$settings['URL'][2],
			'url_prefix'=>"/list/".$this->model->getName().'/'
		));
		site::$view->addParameter('paginator', $res['paginator']);
		site::$view->addParameter('list', $this->model->getList($res['sql']));
		site::$view->setTemplateName('list');
	}
}
?>