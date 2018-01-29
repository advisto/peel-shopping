{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.tpl 55304 2017-11-28 15:49:01Z sdelaporte $
#}<h1 property="name" class="page_title">{{ compte }}</h1>
<div class="page_content account_icons">
{% if est_identifie %}
	{% if user_infos_resume_array %}
		{* Si cette variable est active, on affiche qu'elle sur la page de compte. *}
		{{ user_infos_resume_array }}
	{% else %}
	<div class="row">
		<div class="col-md-5 pull-right">
			{% if admin %}
			<a class="btn btn-warning pull-right" style="margin-right:10px; margin-left:10px" href="{{ admin.href|escape('html') }}">{{ admin.txt }}</a>
			{% endif  %}	
			<a class="btn btn-primary pull-right" style="margin-right:10px; margin-left:10px" href="{{ logout.href|escape('html') }}"><span class="glyphicon glyphicon-log-out"></span> {{ logout.txt }}</a>
		</div>
		<div class="col-md-7 pull-left">
			<p>{{ msg_support }}</p>
		</div>
	</div>
		<p>{{ compte }} {{ number }} <b>{{ code_client }}</b></p>
		{% if user_account_completion_text %}<p>{{ user_account_completion_text }}</p>{% endif %}
		{% if data is defined %}{{ data }}{% endif %}
		{% if code_promo_utilise %}
		<h2 class="well">{{ code_promo_utilise.header }}</h2>
		<div class="row">
			<div class="col-xs-12">
			{% for item in code_promo_utilise.data %}
				- {{ item.code_promo }} {{ item.discount_text }}<br />
			{% endfor %}
			</div>
		</div>
			{% endif %}
			{% if code_promo_valide %}
		<h2 class="well">{{ code_promo_valide.header }}</h2>
		<div class="row">
			<div class="col-xs-12">
			{% for item in code_promo_valide.data %}
				- {{ item.nom_code }} {{ item.discount_text }} {{ item.code_promo_valid_from }} {{ item.date_from }} {{ item.flash_to }} {{ item.date_to }}<br />
			{% endfor %}
			</div>
		</div>
			{% endif %}
			{% if remise_percent %}
		<div class="row">
			<div class="col-xs-12">
				- {{ remise_percent.label }}: {{ remise_percent.value }} %<br />
			</div>
			{% if avoir %}
			<div class="col-xs-12">
				- {{ avoir.label }}: {{ avoir.value }}<br />
			</div>
			{% endif %}
		</div>
		{% endif %}
			{% if modules_data %}
				{% for group,modules_data_array in modules_data %}
					{% if modules_data_group[group] and modules_data_group[group].header %}
		<h2 class="well {{ group }}">{{ modules_data_group[group].header }}</h2>
					{% endif %}
		<div class="row {{ group }}">
				{% for module in modules_data_array %}
			<div class="col-sm-4 col-md-3 col-lg-3"><a class="btn btn-default" href="{{ module.href|escape('html') }}">{{ module.txt }}</a></div>
					{% endfor %}
					{% if modules_data_group[group] and modules_data_group[group].comments %}
			<div class="clearfix"></div>
			<div class="col-xs-12"><p>{{ modules_data_group[group].comments }}</p></div>
					{% endif %}
		</div>
				{% endfor %}
			{% endif %}
			{% if disable_account %}
		<a class="btn btn-danger" style="margin-bottom:10px" data-confirm="{{ confirm_disable_account }}" href="{{ disable_account_href|escape('html') }}">{{ disable_account_text }}</a></div></div>
			{% endif %}
			{% if ABONNEMENT_MODULE %}{{ ABONNEMENT_MODULE }}{% endif %}
	{% endif %}
{% else %}
	<div><a class="btn btn-primary" style="margin-bottom:10px" href="{{ login_href|escape('html') }}">{{ login }}</a></div>
	<div><a class="btn btn-primary" style="margin-bottom:10px" href="{{ register_href|escape('html') }}">{{ register }}</a></div>
{% endif %}
{% if downloadable_file_link_array  %}
		<table class="full_width">
		{% for item in downloadable_file_link_array %}
			<tr>
				<td align="center">
					<a href="{{ item.link }}">{{ item.date }} - {{ item.name }} - {{ STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD }}</a>
				</td>
			</tr>
		{% endfor %}
		</table>
{% endif %}
</div>