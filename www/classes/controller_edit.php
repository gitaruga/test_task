<?
class controller_edit extends controller_no_ajax{
	
	public function	execution(){
		
		$id=(int) site::$settings['URL'][2];
		$show_form=true;
		$value_from_post=false;
		if( isset($_POST['action']) && $_POST['action']=='edit' ){ // если отредактирован пункт и нужно внести его в базу
			if( $this->model->updateItem($id) !== false ){ // Успешная проверка на валидность
				site::$view->addParameter('RedirectURL', "/list/".$this->model->getName().'/');
				site::$view->setTemplateName('redirect');
				$show_form=false;
			}else{
				$value_from_post=true;
				site::$view->addParameter('error', $this->model->getError());
			}
		}

		if( $show_form &&  isset(site::$settings['URL'][2]) && site::$settings['URL'][2] ){ // если нужно вывести форму для редактирования
			site::$view->addParameter('action', 'edit');
			site::$view->addParameter('form_url', "/edit/".$this->model->getName()."/$id/");
			site::$view->addParameter('FieldList', $this->model->getEditList($id, $value_from_post));
			site::$view->addParameter('TITLE','Редактирование записи');
			site::$view->setTemplateName('add');
		}

	}
}
?>