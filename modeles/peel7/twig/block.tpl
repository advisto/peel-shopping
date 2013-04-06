{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: block.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<div class="box {{ mode }} {{ block_class }} {% if is_slider_mode %}{{ mode }}_slider{% endif %} {{ location }}_basicblock {{ location }}_{{ technical_code }} {{ technical_code }}_{{ lang }}"{{ block_style }}>
	<div class="box_header {{ mode }}_header">
	{% if (title) %}
		<table class="full_width">
			<tr>
				<td style="width: 40px;">{% if is_slider_mode %}<a href="#" class="bt_prev" title="{{ STR_PREVIOUS_PAGE|str_form_value }}"> <span></span> </a>{% endif %}</td>
				<td style="width: 30px; text-align: right;"><a href="#" class="bt_icon1" ></a></td>
				<td><h2>{{ title }}</h2></td>
				<td style="width: 30px; text-align: left;"><a href="#" class="bt_icon2" ></a></td>
				<td width="40">{% if is_slider_mode %}<a href="#" class="bt_next" title="{{ STR_NEXT_PAGE }}"> <span></span> </a>{% endif %}</td>
			</tr>
		</table>
	{% endif %}
	</div>
	<div class="box_content_container {{ mode }}_content_container">
		<div class="box_content {{ mode }}_content">{{ content }}</div>
	</div>
	<div class="box_footer {{ mode }}_footer"></div>
</div>