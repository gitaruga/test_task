<?
$EDIT_LINE="
	<a href=\"#\" onclick=\"$('#addForm').submit();return false;\">Сохранить</a>
	<a href=\"/list/$model_name/\">Отмена</a>
";

include_once $BASE_TEMPLATE_PATH."htmlhead.php";
include_once $BASE_TEMPLATE_PATH."header.php";
?>
<div class="FORM">
	<?
	if( isset($error) && $error )
		echo "<p class=\"error\">$error</p>";
	?>
	<form id="addForm" name="addForm" action="<?=$form_url?>" method="post">
		<input type="hidden" name="action" value="<?=$action?>" />
		<?
		foreach ($FieldList as $field) {
			?><div class="formline"><?
				switch ($field['type']) {
					case 'description':
						?>
						<p><?=$field['text']?></p>
						<?
					break;case 'date':
					case 'text':
					case 'float':
					case 'unsignedInt':
						$_format = isset($field['format']) && $field['format'] ? 
							"<br /><span>{$field['format']}</span>" : '';
						?>
						<label><?=$field['title']?><?=$_format?></label>
						<input type="text" name="<?=$field['key']?>" value="<?=$field['value']?>" />
						<?
					break;case 'dropdown':
					case 'linkedDropdown':
						?>
						<label><?=$field['title']?></label>
						<select size="1" name="<?=$field['key']?>">
							<?
							foreach ($field['list'] as $i) {
								$_selected = $field['selected'] && $field['selected']==$i['value'] ? " selected" : '';
								?>
								<option <?=$_selected?> value="<?=$i['value']?>"><?=$i['title']?></option>
								<?
							}
							?>
						</select>
						<?
					break;case 'compositeDropdown':
						?>
						<label><?=$field['title']?></label>
						<input type="hidden" id="url_composite_<?=$field['key']?>" name="url_composite_<?=$field['key']?>" value="<?=$field['ajax_url']?>" />
						<input type="hidden" id="selected_composite_<?=$field['key']?>" name="selected_composite_<?=$field['key']?>" value="<?=$field['selected']?>" />
						<select class="composite" id="composite_<?=$field['key']?>" size="1" name="<?=$field['add_key']?>">
							<?
							foreach ($field['add_list'] as $i) {
								$_selected = $field['add_selected'] && $field['add_selected']==$i['value'] ? " selected" : '';
								?>
								<option <?=$_selected?> value="<?=$i['value']?>"><?=$i['title']?></option>
								<?
							}
							?>
						</select>
						<select id="main_composite_<?=$field['key']?>" size="1" name="<?=$field['key']?>">
						</select>
						<?
					break;
				}
				?><div class="clr"></div>
			</div><?
		}
		?>
	</form>
</div>
<?
include_once $BASE_TEMPLATE_PATH."footer.php";
?>
