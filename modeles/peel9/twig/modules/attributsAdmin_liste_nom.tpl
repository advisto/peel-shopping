{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributsAdmin_liste_nom.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form action="{{ action }}" method="POST">
<table class="main_table">
	<tr>
		<td class="entete" colspan="5">{{ STR_MODULE_ATTRIBUTS_ADMIN_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="5">
			<div style="margin-top:5px;">
				<p><a href="{{ add_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_MODULE_ATTRIBUTS_ADMIN_CREATE }}</a></p>
			</div>
		</td>
	</tr>
{% if num_results == 0 %}
	<tr><td colspan="5"><div class="alert alert-warning">{{ STR_MODULE_ATTRIBUTS_ADMIN_NOTHING_FOUND }}</div></td></tr>
{% else %}
	<tr>
		<td class="menu" style="width:200px">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_NAME }}</td>
		<td class="menu">{{ STR_TYPE }}</td>
		<td class="menu">{{ STR_STATUS }}</td>
		<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
	</tr>
	{% for res in results %}
		{{ res.tr_rollover }}
			<td class="center">
				<input type="checkbox" value="{{ res.id }}" name="attribut_id[]" />
				&nbsp;
				<a data-confirm="{{ STR_ADMIN_CONFIRM_JAVASCRIPT|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
				&nbsp;
				<a href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="{{ STR_ADMIN_UPDATE }}" /></a>
			</td>
			<td class="center"><a title="{{ STR_MODULE_ATTRIBUTS_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom }}</a></td>
			<td class="center">
				{% if not res.texte_libre and not res.upload %}
					<a href="{{ res.texte_libre_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_HANDLE_OPTIONS }}</a>
				{% elseif res.upload %}
					{{ STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_FIELD }}
				{% elseif res.texte_libre %}
					{{ STR_MODULE_ATTRIBUTS_ADMIN_CUSTOM_TEXT }}
				{% endif %}
			</td>
			<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
			<td class="center">{{ res.site_name|html_entity_decode_if_needed }}</td>
		</tr>
	{% endfor %}
{% endif %}
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">
			<table>
				<tr>
					<td>
						<select class="form-control" name="assignation_mode">
							<option value="assign">{{ STR_ADMIN_ASSOCIATED }}</option>
							<option value="unassign">{{ STR_ADMIN_DISASSOCIATED }}</option>
						</select>
					</td><td>
						&nbsp;{{ STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTES_CHECKED_IN_CATEGORY_PRODUCTS }}&nbsp;
					</td><td>
						<select class="form-control" name="categories">
							{{ categorie_options }}
						</select>
					</td>
					<td>&nbsp;<input type="submit" class="btn btn-primary" name="submit_product_attribut_form" value="{{ STR_SEND }}" /></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>