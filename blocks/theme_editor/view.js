debug = false;

function theme_editor (activePresetHandle) {
   		// Move container
		var container = $("#theme-editor-wrapper");
		var templateColor = _.template($('#template-color').html());
		var stylesheet = $('#theme-editor-stylesheet');
		

		


		$('.themePreset').click(function(){
			var t = $(this);
			$('.pActive').removeClass('pActive');
			t.toggleClass('pActive');
			loadColorsFromPresetHandle(t.data('value'));
		})

		loadColorsFromPresetHandle = function (handle, disableCss) {
			$.post(GET_COLOR_URL,{presetHandle: handle, cID:CCM_CID}, function(data){
				fillTemplateColor(data.values);
				if (!disableCss) changeCss(data.less);
			}, "json");
					
		}

		fillTemplateColor = function (data) {
			var defaults = {};
			$.extend(defaults,data);
			var container = $('#theme-less-form');
			$('.col', container).remove();
			_.each(data, function(value){
				container.append(templateColor(value));		
			})
				$('.spectrum',container).spectrum({
					appendTo:container,
					 showAlpha: true
				});					
			$('.spectrum').on('change.spectrum', function(e, color) { 
				var data = $('#theme-less-form').serializeArray().reduce(function(obj, item) {
				    obj[item.name] = item.value;
				    return obj;
				}, {});
				delete data.query;
				changeCss(data);
				
			});			

		}
		// La fonction qui peut modifer le less.js
		changeCss = function  (data) {
			NProgress.configure({parent: "#theme-editor-wrapper" });
			NProgress.start();
			data.cID = CCM_CID;
			$.post(GET_NEW_CSS_URL,data, function(data){
				// stylesheet.attr('href',data);
				stylesheet.html(data);
				NProgress.done();
			});

			// less.modifyVars (data);
		}

		init = function () {
			container.detach().appendTo('body').addClass('loaded');
			stylesheet.detach().appendTo('head');
			// Init color template

			if (activePresetHandle) loadColorsFromPresetHandle (activePresetHandle, true);
		}

		init();

	}


	$(document).ready(function(){
		if (typeof ACTIVE_PRESET_HANDLE == 'undefined') return;
		theme_editor(ACTIVE_PRESET_HANDLE);

		$("#theme-editor-wrapper .handle").click(function(e){
			$("#theme-editor-wrapper").toggleClass('open');
		});
		$("#boxed").change(function(){
			t = $(this);
			c = $('.ccm-page');
			if (t.val() == 'boxed') {
				c.addClass('boxed-wrapper');
			} else {
				c.removeClass('boxed-wrapper');
			}
		})

	});
	$(window).load(function(){
		$("#theme-editor-wrapper").delay(2000).removeClass('open');
	});




function l() {
    if(debug==true) {
        for (var i=0; i < arguments.length; i++) {
            console.log(arguments[i]);
        }
    } 
}

