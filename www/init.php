<?
//Отключение вошлебных кавычек
if (get_magic_quotes_gpc()){
  function stripslashes_gpc(&$value){
    $value = stripslashes($value); 
  }
  array_walk_recursive($_GET, 'stripslashes_gpc');
  array_walk_recursive($_POST, 'stripslashes_gpc');
  array_walk_recursive($_COOKIE, 'stripslashes_gpc');
  array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}//if

function __autoload($classname){
	include_once __DIR__."/classes/$classname.php";
}
?>