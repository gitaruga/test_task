<?
class controller_add extends controller_no_ajax{
	
	public function	execution(){

		$show_form=true;
		if( isset($_POST['action']) && $_POST['action']=='add' ){ // если добавлен пункт и нужно внести его в базу
			if( $this->model->addItem() !== false ){ // Успешная проверка на валидность
				site::$view->addParameter('RedirectURL', "/list/".$this->model->getName().'/');
				site::$view->setTemplateName('redirect');
				$show_form=false;
			}else{
				site::$view->addParameter('error', $this->model->getError());
			}
		}

		if( $show_form ){ // если нужно вывести форму для добавления пункта
			site::$view->addParameter('action', 'add');
			site::$view->addParameter('form_url', "/add/".$this->model->getName()."/");
			site::$view->addParameter('FieldList', $this->model->getAddList());
			site::$view->addParameter('TITLE','Добавление нового пункта');
			site::$view->setTemplateName('add');
		}

	}
}
?>