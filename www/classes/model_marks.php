<?
class model_marks extends model{

	public function __construct(){
		$this->name='marks';
		$this->TableHead=array('Наименование');
		$this->deleteText="Вы действительно хотите удалить марку и все связанные с ней записи в других таблицах?";
	}
	
	public function getCountSQL(){
		return "SELECT COUNT(*) as 'count' FROM `marks`";
	}
	
	public function getListSQL(){
		return "SELECT `id`, `mark` as '0' FROM `marks` ORDER BY `mark`";
	}
	
	public function getAddList(){
		
		return array(
			array('type'=>'text', 'key'=>'mark', 'title'=>'Название марки', 
				'value'=>htmlspecialchars($_POST['mark']))
		);
	
	}
	
	public function getEditList($id, $value_from_post){

		if( $value_from_post ){
			$value=htmlspecialchars($_POST['mark']);
		}else{
			$line=sel_first("SELECT * FROM `marks` WHERE `id`=$id");
			$value=$line['mark'];
		}
		
		return array(
			array('type'=>'text', 'key'=>'mark', 'title'=>'Название марки', 
				'value'=>$value)
		);
	
	}
	
	public function addItem(){

		$mark=$this->ValidField('text', $_POST['mark'], "Название марки должно содержать только буквы, цифры, и символы _+-.%&amp;");
		if( $mark===false) return false;

		$mark=mysql_real_escape_string($mark);
		$line=sel_first("SELECT `id` FROM `marks` WHERE `mark`='$mark'");
		if( $line ){
			$this->error="Такое название марки уже зарегистрировано в системе";
			return false;
		}else{
			if( mysql_query("INSERT `marks` SET `mark`='$mark'") ){
				return true;
			}else{
				$this->error="Не удалось добавить данные в БД";
				return false;
			}
		}
		
	}
	
	public function updateItem($id){

		$mark=$this->ValidField('text', $_POST['mark'], "Название марки должно содержать только буквы, цифры, и символы _+-.%&amp;");
		if( $mark===false) return false;
		
		$mark=mysql_real_escape_string($mark);
		$line=sel_first("SELECT `id` FROM `marks` WHERE `mark`='$mark' AND `id`<>$id");
		if( $line ){
			$this->error="Такое название марки уже зарегистрировано в системе";
			return false;
		}else{
			if( mysql_query("UPDATE `marks` SET `mark`='$mark' WHERE `id`=$id") ){
				return true;
			}else{
				$this->error="Не удалось обновить данные в БД";
				return false;
			}
		}
		
	}
	
	public function deleteItem($id){

		if( mysql_query("DELETE `marks`, `wares`, `traffic` FROM `marks`, `wares`, `traffic`
			WHERE `marks`.`id`=`wares`.`mark_id` AND `wares`.`id`=`traffic`.`ware_id` AND `marks`.`id`=$id") ){
			return true;
		}else{
			$this->error="Не удалось удалить данные в БД";
			return false;
		}

	}

	public function getDropdownList(){
		
		$res=mysql_query("SELECT m.`id` 'value', m.`mark` 'title' FROM `wares` w LEFT JOIN `marks` m ON w.`mark_id`=m.`id` GROUP BY m.`mark` ORDER BY `title`");
		while ($line=mysql_fetch_assoc($res)) {
			$line['title']=htmlspecialchars($line['title']);
			$DropdownList[]=$line;
		}
		return $DropdownList;

	}

	public function is($mark_id){

		$res= sel_first("SELECT `id` FROM `marks` WHERE `id`=$mark_id") ? true : false;
		return $res;

	}	
}
?>