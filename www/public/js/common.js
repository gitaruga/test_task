
function deleteItem(id){
	if (confirm(window.deleteText)) {
		var url='/ajax/'+$('#model_name').val()+'/del/'+id;
		$.post(url,function(data){
			if(data=='OK'){
				window.location.reload();
			}else{
				alert(data);
			}
		});
	}
}

function composite_start(){
	$('.composite').each(function(eo){
		add_id=$(this).attr('id');
		add_value=$(this).val();
		selected=$('#selected_'+add_id).val();
		url=$('#url_'+add_id).val()+add_value;
		
		$.getJSON(url, function(json){
			var list='';
			var val=false;
			for(var key in json.composite_list){
				val = json.composite_list[key];
				if (selected==val.value) {
					sel=' selected';
				}else{
					sel='';
				}
				list=list+'<option'+sel+' value="'+val.value+'">'+val.title+'</option>'
			}
			$('#main_'+add_id).html(list);
		});
	});
}

$(function(){

	composite_start();

	$('.composite').change(function(eo){
		add_id=$(this).attr('id');
		add_value=$(this).val();
		url=$('#url_'+add_id).val()+add_value;
		
		$.getJSON(url, function(json){
			var list='';
			var val=false;
			for(var key in json.composite_list){
				val = json.composite_list[key];
				list=list+'<option value="'+val.value+'">'+val.title+'</option>'
			}
			$('#main_'+add_id).html(list);
		});
	});
});