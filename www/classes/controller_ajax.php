<?
class controller_ajax extends controller{
	
	public function	execution(){

		if( site::$settings['URL'][2]=='del' ){

			$id= (int) site::$settings['URL'][3];
			if( $this->model->deleteItem($id) ){
				echo "OK";
			}else{
				echo $this->model->getError();
			}
			die;

		}elseif( site::$settings['URL'][2]=='composite_list' ){

			$id = isset(site::$settings['URL'][3]) && site::$settings['URL'][3] >0 ? (int) site::$settings['URL'][3] : false;
			$list = $id>0 ? $this->model->composite_list($id) : false;
			echo json_encode(array('composite_list'=>$list));
			die;
		}
	
	}
}
?>