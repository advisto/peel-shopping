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
// $Id: attributsAdmin_liste_by_id.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
#}
<form class="entryform form-inline" role="form" method="post" name="associe_produit_attribut" action="{{ action|escape('html') }}">
	<table cellpadding="4" class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE }} "{{ product_name }}"</td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="{{ product_revenir_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCT }}</a><br />
				<a href="{{ product_liste_revenir_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCTS_LIST }}</a><br />
				<div class="alert alert-info">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_EXPLAIN_SELECT }}</div>
			</td>
		</tr>
		<tr>
			<td class="menu">{{ STR_ADMIN_ATTRIBUTE }}</td>
			<td class="menu">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTIONS_ASSOCIATED }}</td>
		</tr>
	{% for res in results %}
		{{ res.tr_rollover }}
			<td class="title_label">{% if (res.nom) %}{{ res.nom|html_entity_decode_if_needed }}{% else %}[{{ res.id }}]{% endif %}</td>
			<td>
		{% if (res.sub_res) or res.texte_libre or res.upload %}
			{% if (res.sub_res) %}
					<select class="form-control" name="attribut_id_{{ res.id }}[]" multiple="multiple" style="width:100%" size="{% if (res.sub_res|length)<5 %}{{ res.sub_res|length }}{% else %}5{% endif %}">
				{% for sr in res.sub_res %}
						<option value="{{ sr.value|str_form_value }}" {% if sr.issel %} selected="selected"{% endif %}>{{ sr.desc|html_entity_decode_if_needed }}{% if sr.prix>0 %} - {{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST }} : {{ sr.prix }} {{ ttc_ht }}{% endif %}</option>
				{% endfor %}
					</select>
			{% elseif res.texte_libre or res.upload %}
					<select class="form-control" name="attribut_id_{{ res.id }}[]" multiple="multiple" style="width:400px" size="1">
						<option value="0" {% if res.issel %} selected="selected"{% endif %}>
						{% if (res.texte_libre) %}
							[{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_FREE_TEXT }}]
						{% elseif (res.upload) %}
							[{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ADD_UPLOAD }}]
						{% endif %}
						</option>
			{% endif %}
					</select> 
			{% else %}
				{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_NO_OPTION }} <a href="{{ wwwroot_in_admin }}/modules/attributs/administrer/attributs.php?mode=liste&attid={{ res.id }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_MANAGE_LINK }}</a>.
			</td>
		{% endif %}
		</tr>
	{% endfor %}
	</table>
	<div class="center"><input type="submit" name="submit" class="btn btn-primary" value="{{ STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ASSOCIATE_ATTRIBUTE|str_form_value }}" /></div>
</form>