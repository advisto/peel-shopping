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
// $Id: admin_utilisateur_form_isutil.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<input name="mode" type="hidden" value="event_comment" />
	<center>
		<table class="full_width" >
			<tr>
				<td class="entete">{{ STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING }} {{ pseudo }}</td>
			</tr>
		</table>
		<table>
			<tr>
				<th><br />{{ STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION }}</th>
			</tr>
			<tr>
				<td class="center">
					<textarea class="form-control" name="form_event_comment" rows="5" cols="50" id="event_comment">{{ event_comment }}</textarea>
				</td>
			</tr>
			<tr>
				<td class="center">
					<input name="form_event_submit" type="submit" value="{{ STR_ADMIN_UTILISATEURS_SAVE_EVENT|str_form_value }}" class="btn btn-primary" /><br />
					<br />
				</td>
			</tr>
		</table>
	</center>
</form>
<table class="full_width" >
	<tr>
		<td>{{ affiche_recherche_connexion_user }}</td>
	</tr>
</table>
{% if affiche_liste_abus %}
<table class="full_width" >
	<tr>
		<td>{{ affiche_liste_abus }}</td>
	</tr>
</table>
{% endif %}
{% if (affiche_liste_abus) %}
<table class="full_width" >
	<tr>
		<td class="entete">{{ STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT }}</td>
	</tr>
	<tr>
		<td>{{ actions_moderations_user }}</td>
	</tr>
</table>
{% endif %}
<table class="main_table">
	<tr>
		<td class="entete" colspan="{{ columns }}"><img src="{{ mini_liste_commande_src|escape('html') }}" width="22" height="19" alt="" align="absmiddle">{{ STR_ADMIN_UTILISATEURS_ORDERS_LIST }}</td>
	</tr>
	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu center">{{ STR_ORDER_NAME }}</td>
		<td class="menu center">{{ STR_DATE }}</td>
		<td class="menu center">{{ STR_TOTAL }} {{ STR_TTC }}</td>
		{% if is_parrainage_module_active %}
		<td class="menu center">{{ STR_AVOIR }}</td>
		{% endif %}
		<td class="menu center">{{ STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED }}</td>
		<td class="menu center" colspan="2">{{ STR_PAYMENT }}</td>
		<td class="menu center">{{ STR_DELIVERY }}</td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
		<td class="center">
			<a title="{{ STR_MODIFY|str_form_value }}" href="{{ res.modif_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="{{ STR_MODIFY|str_form_value }}" /></a>
			<a title="{{ STR_PRINT|str_form_value }}" href="{{ res.print_href|escape('html') }}"><img src="{{ printer_src|escape('html') }}" alt="{{ STR_PRINT|str_form_value }}" /></a>
		</td>
		<td class="center">{{ res.id }}</td>
		<td class="center">{{ res.date }}</td>
		<td class="center">{{ res.prix }}</td>
		{% if is_parrainage_module_active %}
		<td class="center">{{ res.recuperer_avoir_commande }}</td>
		{% endif %}
		<td class="center">{{ res.ordered_products }}</td>
		<td class="center">{{ res.payment_name }}</td>
		<td class="center">{{ res.payment_status_name }}</td>
		<td class="center">{{ res.delivery_status_name }}</td>
	</tr>
	{% endfor %}
	<tr>
		<td class="center" colspan="{{ columns }}">
			<form class="entryform form-inline" role="form" method="post" action="{{ action2|escape('html') }}">
				{{ form_token }}
				<input type="submit" name="print_all_bill" value="{{ STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS|str_form_value }}" class="btn btn-primary" />
				<input type="hidden" name="user_id" value="{{ user_id|str_form_value }}" />
			</form>
		</td>
	</tr>
	{% else %}
	<tr>
		<td colspan="9" class="center">
			<p><b>{{ STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND }}</b></p>
		</td>
	</tr>
	{% endif %}
</table>