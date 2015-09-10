<h4>Options Presets</h4>
<form action="">
		<?php $o->output_presets_list(true, $pID)?>
</form>
<script>
	$(document).ready(function(){
		
		$("#preset_id").change(function(){
			changePreset($(this).val());
		});

	});
	function changePreset(pID) {
		var url = $.get('<?php echo URL::to("/ThemeSuperMint/tools/font_url_ajax") ?>' + '?pID=' + pID,function(data){
			$("#css-fonts").attr('href', data);
		})

		var o = $("#css-override");
		o.url = o.attr('href') + "&pID=" + pID;
		o.attr('href', o.url);
	}	
</script>