(function ($) {
	tinymce.create('tinymce.plugins.open_shortcode', {
		tmp: "",
		getInfo: function () {
			return {
				longname: 'Open ShrotCode',
				version: "1.0"
			};
		},
		init: function (ed, url) {
			var t = this;

			// ビジュアルリッチエディタのときテーマディレクトリのパスに展開
			ed.onBeforeSetContent.add(function (ed, o) {
				o.content = t._do_shortcode(o.content);
			});
			
			// コードエディタのときショートコードに戻す
			ed.onGetContent.add(function (ed, o) {
				//if(o.content)
				//	o.content = t._to_shortcode(o.content);
			});
		},
		_do_shortcode: function (co) {
			var reg = new RegExp("\\[([a-z0-9_]+)\\]", 'g');
			return co.replace(reg, function(match, p1){
				var response;
				response = $.ajax({
					url: ajaxurl,
					type: 'POST',
					async: false,
					data: {
						action: 'do_shortcode',
						shortcode: p1
					}
				});
				
				return response.responseText;
			});
		},
		_to_shortcode: function (co) {
			var reg = new RegExp(tpl_url, 'g');
			return co.replace(reg, '[template_url]');
		}
	});
	tinymce.PluginManager.add('open_shortcode', tinymce.plugins.open_shortcode);
})(jQuery);