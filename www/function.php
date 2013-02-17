<?
function sel_first($sql){
	$res=mysql_query($sql);
	if( $res && mysql_num_rows($res)>0 ){
    	return mysql_fetch_assoc($res);  
	}else
		return false;
}
?>