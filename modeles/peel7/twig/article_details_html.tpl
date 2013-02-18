{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: article_details_html.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}{% if not is_article %}
	<h1>{{ STR_NO_FIND_ART }}</h1>
{% else %}
	<h1 class="page_title">{{ titre|html_entity_decode_if_needed }}</h1>
	<div class="rub_content">
		{% if is_offline %}
		<p style="color: red;">{{ STR_OFFLINE_ART }}</p>
		{% endif %}
		<div style="padding-top:5px;">
		{% if (image_src) %}
			<p class="center"><img src="{{ image_src|escape('html') }}" alt="{{ titre }}" /></p>
		{% endif %}
			<div style="text-align:justify;">{{ chapo|html_entity_decode_if_needed|nl2br_if_needed }}</div>
			<div style="text-align:justify;">{{ texte|html_entity_decode_if_needed|nl2br_if_needed }}</div>
			{% if (share_feature) %}
				{{ share_feature }}
			{% elseif (tell_friends) %}
				<p class="right"><img src="{{ tell_friends.src|escape('html') }}" alt="{{ tell_friends.txt }}" />&nbsp;<a href="{{ tell_friends.href|escape('html') }}">{{ tell_friends.txt }}</a></p>
			{% endif %}
		</div>
		{% if (admin) %}
		<p><a href="{{ admin.href|escape('html') }}" class="label">{{ admin.modify_article_txt }}</a></p>
		{% endif %}
{% endif %}