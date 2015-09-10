<?php defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
$boxed = $c->getAttribute('boxed_layout_mode');

if ($c->isEditMode()) : ?>
    <div class="ccm-edit-mode-disabled-item" style="width: <?php echo $width; ?>; height: <?php echo $height; ?>">
        <div style="padding: 40px 0px 40px 0px"><?php echo t('Theme Editor disabled in edit mode.')?></div>
    </div>
<?php else : ?>
<div id="theme-editor-wrapper" class="open">
	<div id="theme-editor">
		<p>Page Layout</p>
	    <select name="boxed" id="boxed">
	    	<option value="wide"><?php echo t('Wide') ?></option>
	    	<option value="boxed" <?php echo $boxed ? 'selected' : '' ?>><?php echo t('Boxed') ?></option>
	    </select>			
		<p>Presets</p>
		<form action="">
				<?php foreach ($themePresets as $key => $preset) :?>
					<div class="flipcard h themePreset <?php echo $preset->getPresetHandle() == $activePresetHandle ? 'pActive' : '' ?>" data-value="<?php echo $preset->getPresetHandle() ?>">
					    <div class="front">
					      <?php echo $preset->getPresetDisplayName() ?>
					    </div>
					    <div class="back">
						  <?php echo $preset->getPresetIconHTML() ?>
					    </div>
					</div>					
				<?php endforeach; ?>
		</form>
	    <p>Edit colors</p>
	    <small>This is only a small part of customizable colors present in <strong><?php echo $themeHandle ?></strong></small>
	    <form id="theme-less-form" class="clearfix">
	    </form>
	    <div class="handle"><i class="fa fa-eyedropper"></i></div>
	    <?php if ($options->displayPresetTools) $this->include('elements/preset_tools') ?>
    
	</div>
</div> 
<script type="text/template" id="template-color">
	<div class="col">
		 <span class="hint--top" data-hint="<%= variable %>">
		<input type="text" value="<%= val %>" name="<%= variable %>" class="spectrum">	
		</span>
	</div>
</script>
<style type="text/css" id="theme-editor-stylesheet"> </style>
<script>
	var GET_COLOR_URL = "<?php echo URL::to('/theme_editor/tools/getcolors') ?>";
	var GET_NEW_CSS_URL = "<?php echo URL::to('/theme_editor/tools/getnewcss') ?>";
	var ACTIVE_PRESET_HANDLE = '<?php echo $activePresetHandle ?>';
</script>
<?php endif ?>