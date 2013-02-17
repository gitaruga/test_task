<?
$EDIT_LINE="
	<a href=\"/add/$model_name/\">Добавить запись</a>
";

include_once $BASE_TEMPLATE_PATH."htmlhead.php";
?><script type="text/javascript">
	window.deleteText='<?=$deleteText?>';
</script><?
include_once $BASE_TEMPLATE_PATH."header.php";
?>
<form >
	<input id="model_name" type="hidden" name="model_name" value="<?=$model_name?>" />
</form>
<div class="LIST">
	<?
	if( is_array($list) && count($list)>0 ){
		?>
		<table id="table_list">
			<tr>
				<?
				foreach ($TableHead as $i) {
					echo "<th>$i</th>\n";
				}
				?>
				<th>Операции</th>
			</tr>
			<?
			foreach ($list as $row){
				$row_id=array_shift($row);
				?>
				<tr>
					<?
					foreach ($row as $i) {
						echo "<td>$i</td>\n";
					}
					?>
					<td>
						<a href="/edit/<?=$model_name?>/<?=$row_id?>" class="edit"></a>
						<?
						if( $model_name!='traffic' ){
							?>
							<a href="#" class="del" onclick="deleteItem(<?=$row_id?>);return false;"></a>
							<?
						}
						?>
					</td>
				</tr>
				<?
			}
			?>
		</table>
		<?
	}else{
		echo "<p class=\"error\">В БД записи не найдены</p>";
	}
	?>	
</div>
<div class="paginator">
	<?
	if( isset($paginator) && $paginator ):
		foreach ($paginator as $i) {
			if( $i['type']=='prev' ){
				?>
				<a href="<?=$i['href']?>">&lt;&lt;</a>
				<?
			}elseif( $i['type']=='next' ){
				?>
				<a href="<?=$i['href']?>">&gt;&gt;</a>
				<?
			}elseif( $i['type']=='tri' ){
				?>
				<span class="tri">...</span>
				<?
			}elseif( $i['type']=='num' ){
				$class = $i['active'] ? ' class="active"' : '';
				?>
				<a href="<?=$i['href']?>"<?=$class?>><?=$i['num']?></a>   
				<?
			}
		}
	endif;
	?>
	<div class="clr"></div>
</div>
<?
include_once $BASE_TEMPLATE_PATH."footer.php";
?>
