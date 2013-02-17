<?
class model_wares extends model{

	public function __construct(){
		$this->name='wares';
		$this->TableHead=array('Наименование', 'Цена (грн)', 'Вес (кг)', 'Сейчас на складе (шт)');
		$this->deleteText="Вы действительно хотите удалить товар и все связанные с ним записи в таблице трафик?";
	}
	
	public function getCountSQL(){
		return "SELECT COUNT(*) as 'count' FROM `wares`";
	}
	
	public function getListSQL(){
		return "SELECT w.`id` as 'id', CONCAT('<b>', m.`mark`, '</b> ', w.`title`) as '0', w.`price` as '1',  w.`weight` as '2', w.`quantity` as '3'
			FROM `wares` as w LEFT JOIN `marks` as m ON w.`mark_id`=m.`id` 
			ORDER BY `0`,`1`";
	}

	public function getAddList(){
		
		$marks= new model_marks();
		
		$F_mark_id=array('type'=>'linkedDropdown', 'key'=>'mark_id', 'title'=>'Марка', 
				'list'=>$marks->getDropdownList(), 'selected'=> (string) (int) $_POST['mark_id']);
		
		$F_title=array('type'=>'text', 'key'=>'title', 'title'=>'Наименование', 
				'value'=>htmlspecialchars($_POST['title']));
		
		$value = isset($_POST['price']) ? (float) $_POST['price'] : '';
		$F_price=array('type'=>'float', 'key'=>'price', 'title'=>'Цена (грн)', 
				'value'=>$value);
		
		$value = isset($_POST['weight']) ? (float) $_POST['weight'] : '';
		$F_weight=array('type'=>'float', 'key'=>'weight', 'title'=>'Вес(кг)', 
				'value'=>$value);
		
		return array($F_mark_id, $F_title, $F_price, $F_weight);
	
	}
	
	public function getEditList($id, $value_from_post){

		$marks= new model_marks();
		if( $value_from_post ){
			$value_mi=(string) (int) $_POST['mark_id'];
			$value_t=htmlspecialchars($_POST['title']);
			$value_p = isset($_POST['price']) ? (float) $_POST['price'] : '';
			$value_w = isset($_POST['weight']) ? (float) $_POST['weight'] : '';
		}else{
			$line=sel_first("SELECT * FROM `wares` WHERE `id`=$id");
			
			$value_mi=$line['mark_id'];
			$value_t=$line['title'];
			$value_p=$line['price'];
			$value_w=$line['weight'];

		}
		
		$F_mark_id=array('type'=>'linkedDropdown', 'key'=>'mark_id', 'title'=>'Марка', 
				'list'=>$marks->getDropdownList(), 'selected'=>$value_mi);
		
		$F_title=array('type'=>'text', 'key'=>'title', 'title'=>'Наименование', 
				'value'=>$value_t );
		
		$F_price=array('type'=>'float', 'key'=>'price', 'title'=>'Цена (грн)', 
				'value'=>$value_p);
		
		$F_weight=array('type'=>'float', 'key'=>'weight', 'title'=>'Вес(кг)', 
				'value'=>$value_w);
		
		return array($F_mark_id, $F_title, $F_price, $F_weight);
	
	}
	
	public function addItem(){

		$marks= new model_marks();
		if( $marks->is((int) $_POST['mark_id']) ){
			$mark_id=(int) $_POST['mark_id'];
		}else{
			$this->error="Марка с таким id не зарегистрирована";
			return false;
		}

		$title=$this->ValidField('text', $_POST['title'], "Наименование должно содержать только буквы, цифры, и символы _+-.%&amp;");
		if( $title===false) return false;

		$price=$this->ValidField('float', $_POST['price'], "Цена должна быть положительным числом");
		if( $price===false) return false;

		$weight=$this->ValidField('float', $_POST['weight'], "Вес должен быть положительным числом");
		if( $weight===false) return false;

		$title=mysql_real_escape_string($title);
		$line=sel_first("SELECT `id` FROM `marks` WHERE `title`='$title' AND `mark_id`=$mark_id");
		if( $line ){
			$this->error="Такой товар уже зарегистрирован в системе";
			return false;
		}elseif( mysql_query("INSERT `wares` SET `title`='$title', `mark_id`=$mark_id, `price`=$price, `weight`=$weight, `quantity`=0") ){
				return true;
		}else{
				$this->error="Не удалось добавить данные в БД";
				return false;
		}
		
	}
	
	public function updateItem($id){

		$marks= new model_marks();
		if( $marks->is((int) $_POST['mark_id']) ){
			$mark_id=(int) $_POST['mark_id'];
		}else{
			$this->error="Марка с таким id не зарегистрирована";
			return false;
		}

		$title=$this->ValidField('text', $_POST['title'], "Наименование должно содержать только буквы, цифры, и символы _+-.%&amp;");
		if( $title===false) return false;

		$price=$this->ValidField('float', $_POST['price'], "Цена должна быть положительным числом");
		if( $price===false) return false;

		$weight=$this->ValidField('float', $_POST['weight'], "Вес должен быть положительным числом");
		if( $weight===false) return false;

		
		$title=mysql_real_escape_string($title);
		$line=sel_first("SELECT `id` FROM `marks` WHERE `title`='$title' AND `mark_id`=$mark_id AND `id`<>$id");
		if( $line ){
			$this->error="Такой товар уже зарегистрирован в системе";
			return false;
		}elseif( mysql_query("UPDATE `wares` SET `title`='$title', `mark_id`=$mark_id, `price`=$price, `weight`=$weight WHERE `id`=$id") ){
			return true;
		}else{
			$this->error="Не удалось обновить данные в БД";
			return false;
		}
		
	}
	
	public function deleteItem($id){
		if( mysql_query("DELETE `wares`, `traffic` FROM `wares`, `traffic` 
			WHERE `wares`.`id`=`traffic`.`ware_id` AND `wares`.`id`=$id AND `traffic`.`ware_id`=$id") ){
			return true;
		}else{
			$this->error="Не удалось удалить данные в БД";
			return false;
		}

	}

	public function composite_list($mark_id){
		
		$res=mysql_query("SELECT `id` as 'value', `title` FROM `wares` WHERE `mark_id`='$mark_id' ORDER BY `title`");
		$List=false;
		while ($line=mysql_fetch_assoc($res)) {
			$line['title']=htmlspecialchars($line['title']);
			$List[]=$line;
		}
		return $List;

	}

	public function is($ware_id){
		return sel_first("SELECT `id` FROM `wares` WHERE `id`=$ware_id") ? true : false;
	}	

}
?>