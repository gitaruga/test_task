<?
abstract class model{
	protected $TableHead=array();
	protected $name;
	protected $error=false;
	protected $deleteText;
	
	abstract public function getCountSQL();
	abstract public function getListSQL();
	abstract public function getAddList();
	abstract public function getEditList($id, $value_from_post);
	abstract public function addItem();
	abstract public function updateItem($id);
	abstract public function deleteItem($id);

	public function getTableHead(){
		return $this->TableHead;
	}
	
	public function getName(){
		return $this->name;
	}

	public function getError(){
		return $this->error;
	}

	public function getDeleteText(){
		return $this->deleteText;
	}

	function ValidField($type, $value, $errMsg){
		switch ($type) {
			case 'text':
				if( preg_match('/^[0-9A-za-zА-Яа-яЁёЇїІіЄє _+&.%\-]+$/u', trim($value)) >0){
					return trim($value);
				}
			break;case 'float':
				if( ((float) trim($value)) >0){
					return (float) trim($value);
				}
			break;case 'int':
				if( ((int) trim($value)) >0){
					return (int) trim($value);
				}
			break;case 'date':
				if( preg_match_all('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/u', trim($value), $res) >0){
					$D=sprintf("%'02d", $res[1][0]);
					$M=sprintf("%'02d", $res[2][0]);
					$Y=$res[3][0];
					if( checkdate($M, $D, $Y) ){
						return "$Y-$M-$D";
					}
				}
			break;
		}
		$this->error=$errMsg;
		return false;

	}	

	public function getList($sql){

		$res=mysql_query($sql);
		$list=false;
		while ($line=mysql_fetch_assoc($res)){
			$list[]=$line;
		}
		return $list;
	
	}
	
}
?>