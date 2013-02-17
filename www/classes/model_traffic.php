<?
class model_traffic extends model{

	public function __construct(){
		$this->name='traffic';
		$this->TableHead=array('Дата', 'Товар', 'Тип', 'Количество (шт)');
		$this->deleteText="Вы действительно хотите удалить запись?";
	}
	
	public function getCountSQL(){
		return "SELECT COUNT(*) as 'count' FROM `traffic`";
	}
	
	public function getListSQL(){
		return "SELECT t.`id` 'id', t.`date` '0',  
				CONCAT('<b>', m.`mark`, '</b> ', w.`title`) '1',
				t.`type` '2', t.`quantity` '3'
			FROM 
				`traffic` t LEFT JOIN `wares` w ON t.`ware_id`=w.`id` 
				LEFT JOIN `marks` m ON w.`mark_id`=m.`id`
			ORDER BY `0` DESC, m.`mark`, w.`title`, `2`";
	}

	public function getList($sql){

		$res=mysql_query($sql);
		$list=false;
		while ($line=mysql_fetch_assoc($res)){
			$line['0'] = substr($line['0'], 8) .'.'. substr($line['0'], 5,2) .'.'. substr($line['0'], 0,4);
			$line['2'] = $line['2']=='I' ? '<span style="color:#090;">Входящий</span>' : '<span style="color:#E00;">Исходящий</span>';
			$list[]=$line;
		}
		return $list;
	
	}
	
	public function getAddList(){
		
		$wares= new model_wares();
		$marks= new model_marks();

		$value_d = isset($_POST['date']) && $_POST['date'] ? htmlspecialchars($_POST['date']) : date("d.m.Y");
		$F_date=array('type'=>'date', 'key'=>'date', 'title'=>'Дата', 
				'value'=>$value_d, 'format'=>'Формат даты: 01.01.2001');
		
		$F_type=array('type'=>'dropdown', 'key'=>'type', 'title'=>'Тип', 
				'list'=>array(
					array('value'=>'I', 'title'=>'Входящий'),
					array('value'=>'O', 'title'=>'Исходящий') ), 
				'selected'=> htmlspecialchars($_POST['type']) );

		$F_ware=array('type'=>'compositeDropdown', 'key'=>'ware_id', 'title'=>'Товар', 'ajax_url'=>'/ajax/wares/composite_list/',
				'add_key'=>'mark_id', 'add_list'=>$marks->getDropdownList(false), 'add_selected'=> (string) (int) $_POST['mark_id'],
				'selected'=> (string) (int) $_POST['ware_id']);
		
		$value_q = isset($_POST['quantity']) ? (float) $_POST['quantity'] : '';
		$F_quantity=array('type'=>'unsignedInt', 'key'=>'quantity', 'title'=>'Количество (шт)', 
				'value'=>$value_q);
		
		return array($F_date, $F_type, $F_ware, $F_quantity);
	
	}

	public function getEditList($id, $value_from_post){
		
		$wares= new model_wares();
		$marks= new model_marks();

		$line=sel_first("SELECT t.*, CONCAT('<b>', m.`mark`, '</b> ', w.`title`) 'title'
			FROM
				`traffic` t LEFT JOIN `wares` w ON t.`ware_id`=w.`id` 
				LEFT JOIN `marks` m ON w.`mark_id`=m.`id`
			WHERE t.`id`=$id");
		$date=substr($line['date'], 8) .'.'. substr($line['date'], 5,2) .'.'. substr($line['date'], 0,4);

		if( $value_from_post ){
			$value_t=htmlspecialchars($_POST['type']);
			$value_q=(float) $_POST['quantity'];
		}else{
			$value_t=$line['type'];
			$value_q=$line['quantity'];
		}

		$F_description=array('type'=>'description', 'text'=>"<b>$date</b> - {$line['title']}");
		$F_type=array('type'=>'dropdown', 'key'=>'type', 'title'=>'Тип', 
				'list'=>array(
					array('value'=>'I', 'title'=>'Входящий'),
					array('value'=>'O', 'title'=>'Исходящий') ), 
				'selected'=>$value_t );
		$F_quantity=array('type'=>'unsignedInt', 'key'=>'quantity', 'title'=>'Количество (шт)', 
				'value'=>$value_q);
		
		return array($F_description, $F_type, $F_quantity);

	}
	
	public function addItem(){

		if( $res=$this->validData() ){ // Данные прошли проверку
			extract($res);
			if( mysql_query("INSERT `traffic` SET `date`='$date', `type`='$type', `ware_id`=$ware_id, `quantity`=$quantity") ){
				$this->recount($ware_id);
				return true;
			}else{
				$this->error="Не удалось добавить данные в БД";
				return false;
			}
		}else{ // Данные не прошли проверку
			return false;
		}


	}
	
	public function updateItem($id){

		if( $res=$this->validData($id) ){ // Данные прошли проверку
			extract($res);
			if( mysql_query("UPDATE `traffic` SET `date`='$date', `type`='$type', `ware_id`=$ware_id, `quantity`=$quantity WHERE `id`=$id") ){
				$this->recount($ware_id);
				return true;
			}else{
				$this->error="Не удалось добавить данные в БД";
				return false;
			}
		}else{ // Данные не прошли проверку
			return false;
		}

	}
	
	public function deleteItem($id){

		if( mysql_query("DELETE FROM `traffic` WHERE `id`=$id") ){
			return true;
		}else{
			$this->error="Не удалось удалить данные в БД";
			return false;
		}

	}

	protected function validData($id=false){
		
		$wares= new model_wares();

		if($id){
			$line=sel_first("SELECT `ware_id`, `date`,`type` FROM `traffic` WHERE `id`=$id");
			$ware_id=$line['ware_id'];
			$date=$line['date'];
			$type_old=$line['type'];
		}else{ // добавляется новая запись
			if( $wares->is((int) $_POST['ware_id']) ){
				$ware_id=(int) $_POST['ware_id'];
			}else{
				$this->error="Товар с таким id не зарегистрирован";
				return false;
			}

			$date=$this->ValidField('date', $_POST['date'], "Дата должна быть введена в правильном формате и должна существоавть");
			if( $date===false) return false;
		}
		
		$type=trim($_POST['type']);
		if( $type!='I' && $type!='O'){
			$this->error="Недопустимый тип операции";
			return false;
		}

		$quantity=$this->ValidField('int', $_POST['quantity'], "Количество должно быть положительным числом");
		if( $quantity===false) return false;


		$id_check = $id ? " AND `id`<>$id" : '';
		
		// проверка существования такой записи в БД
		$line=sel_first("SELECT `id` FROM `traffic` WHERE `date`='$date' AND `ware_id`=$ware_id AND `type`='$type' $id_check");
		if( $line ){ 
			$this->error="Такая запись уже зарегистрирована в системе";
			return false;
		}
		
		// проверка наличия нужного количества товара для отгрузки на заданную дату
		if( $type_old=='O' ){
			$count=sel_first("SELECT SUM( IF(`type`='I', quantity, -quantity) ) 'count'
				FROM `traffic`
				WHERE `ware_id`=$ware_id AND `date`<='$date' $id_check");
			if( $count===false OR $count['count']===null ){
				$count=0;
			}else{
				$count=$count['count'];
			}
			if( $quantity>$count ){
				$this->error="На заданную дату такого количества товара нет в наличии: присутствует только <b>$count</b> единиц товара.";
				return false;
			}
		}else{ // проверка допустимого количества товара для завоза на заданную дату
			if( $id ){ // редактируем существ. запись
				$this->error="Записи с типом \"входящие\" временно редактировать нельзя";
				return false;
			}else{ // добавляем новую запись

				$count_all=sel_first("SELECT SUM( IF(`type`='I', -quantity, quantity) ) 'count'
					FROM `traffic`
					WHERE `ware_id`=$ware_id $id_check");
				if( $count_all===false OR $count_all['count']===null ){
					$count_all=0;
				}else{
					$count_all=$count_all['count'];
				}

				$count_date=sel_first("SELECT SUM( IF(`type`='I', -quantity, quantity) ) 'count'
					FROM `traffic`
					WHERE `ware_id`=$ware_id AND `date`<='$date' $id_check");
				if( $count_date===false OR $count_date['count']===null ){
					$count_date=0;
				}else{
					$count_date=$count_date['count'];
				}

				if( $quantity<$count_all OR $quantity<$count_date  ){
					$count_real= $count_all<$count_date ? $count_date : $count_all;
					$this->error="На заданную дату нельзя ввести количество товара, меньше чем <b>$count_real</b>.";
					return false;
				}
			}
		}

		return array('date'=>$date, 'ware_id'=>$ware_id, 'type'=>$type, 'quantity'=>$quantity);

	}

	protected function recount($ware_id){
		$res = mysql_query("UPDATE `wares` SET `quantity`=(
				SELECT SUM( IF(`type`='I', quantity, -quantity) ) FROM `traffic` WHERE `ware_id`=$ware_id
			) WHERE `id`=$ware_id");
		return $res;

	}
}
?>