<?
class paginator{
	protected $items_per_page=10;
	protected $paginator_width=3; // нечётное количество
	protected $url_suffix='/';

	public function __construct($data=false){

		if( $data ):
			if( isset($data['items_per_page']) )
				$this->items_per_page=$data['items_per_page'];
			if( isset($data['paginator_width']) )
				$this->paginator_width=$data['paginator_width'];
			if( isset($data['url_suffix']) )
				$this->url_suffix=$data['url_suffix'];
		endif;

	}

	public function execution($data){
		// 'count_sql' - Запрос для подщета кол-ва строк
		// 'base_sql' - Базовый запрос
		// 'page' - номер текущей страницы
		// 'url_prefix' - урл, к которой будет дописан номер страницы
		
		$page = (int) $data['page'];
		if( $page<=0 ) $page=1;

		$line=sel_first($data['count_sql']);
		$all_count=$line['count']; // кол-во всех записей
		$all_page=ceil($all_count/$this->items_per_page);// кол-во всех страниц
		$paginator_arr=false;
		
		if($all_page>1):
			if($page!=1){ // Если текущая страница не первая
				$prev=$page-1;
				$paginator_arr[]=array('type'=>'prev', 'href'=>$data['url_prefix'].$prev.$this->url_suffix); // Вывод "предыдущей"
				$active=false;
			}else
				$active=true;
			$paginator_arr[]=array('type'=>'num', 'href'=>$data['url_prefix'].'1'.$this->url_suffix, 'num'=>1, 'active'=>$active); // Вывод первой страницы
		
			$from=$page-floor($this->paginator_width/2);
			if( $from>2 ) // Если присутствуют 3 точки после 1
				$paginator_arr[]=array('type'=>'tri'); // Вывод первых трёх точек
			else
				$from=2;
		
			$to=$page+floor($this->paginator_width/2);
			if( $to>=$all_page-1 ) // Если 3-х точек перед последним элементом нет
				$to=$all_page;
			for($i=$from; $i<=$to; $i++){
				$active = $page==$i ? true : false;      
				$paginator_arr[]=array('type'=>'num', 'href'=>$data['url_prefix'].$i.$this->url_suffix, 'num'=>$i, 'active'=>$active);
			}
		
			if( $to<$all_page-1 ){ // Если есть 3 точки
				$paginator_arr[]=array('type'=>'tri'); // Вывод вторых трёх точек
				$paginator_arr[]=array('type'=>'num', 'href'=>$data['url_prefix'].$all_page.$this->url_suffix, 'num'=>$all_page, 'active'=>$active); // Вывод последней страницы
			}

			if( $page!=$all_page ){ // Если страница не последняя
				$next=$page+1;
				$paginator_arr[]=array('type'=>'next', 'href'=>$data['url_prefix'].$next.$this->url_suffix); // Вывод "Следующей"
			}

			$sql_from=$page*$this->items_per_page-$this->items_per_page;
			$exeData['sql']=$data['base_sql']." LIMIT $sql_from, {$this->items_per_page}";
			$exeData['paginator']=$paginator_arr;
			
		else:
			$exeData['sql']=$data['base_sql'];
			$exeData['paginator']=false;
		endif;
		
		$exeData['count']=$all_count;
		return $exeData;
	}
}
?>