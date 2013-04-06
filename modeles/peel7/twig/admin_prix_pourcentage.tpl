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
// $Id: admin_prix_pourcentage.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<table class="full_width">
		<tr>
			<td class="entete" colspan="3">{{ STR_ADMIN_PRIX_POURCENTAGE_TITLE }}</td>
		</tr>
		<tr>
			<td colspan="3"><p>{{ STR_ADMIN_PRIX_POURCENTAGE_EXPLAIN }}</p></td>
		</tr>
		<tr>
			<td width="45%">
				<b>{{ STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_CATEGORY }}</b>{{ STR_BEFORE_TWO_POINTS }}:<br />
				<select id="form_categories" class="formulaire1" name="categories[]" multiple="multiple" style="width:100%" size="15" onchange="var select=document.getElementById('form_products'); for (var i in select.options) {ldelim }}select.options[i].selected=''; {rdelim }}">
				{% for o in cats_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
			<td style="font-size:20px; font-weight:bold" align="center">
				{{ STR_OR|strtoupper }}
			</td>
			<td width="45%">
				<br /><b>{{ STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_PRODUCT }}</b>{{ STR_BEFORE_TWO_POINTS }}:<br />
				<select id="form_products" class="formulaire2" name="produits[]" multiple="multiple" style="width:100%" size="15" onchange="var select=document.getElementById('form_categories'); for (var i in select.options) {ldelim }}select.options[i].selected=''; {rdelim }}">
				{% for o in products_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:center;padding-top:15px;">
				<b>{{ STR_ADMIN_PRIX_POURCENTAGE_USERS_RELATED }}</b>{{ STR_BEFORE_TWO_POINTS }}:
				<select name="for_price">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="all"{% if for_price == 'all' %} selected="selected"{% endif %}>{{ STR_ADMIN_ALL }}</option>
					<option value="1"{% if for_price == '1' %} selected="selected"{% endif %}>{{ STR_ADMIN_PRIX_POURCENTAGE_CLIENTS_ONLY }}</option>
					<option value="2"{% if for_price == '2' %} selected="selected"{% endif %}>{{ STR_ADMIN_PRIX_POURCENTAGE_RESELLERS_ONLY }}</option>
				</select><br />
			</td>
		</tr>
		<tr class="middle">
			<td colspan="3" style="text-align:center;padding-top:15px;">
				<b>{{ STR_ADMIN_PRIX_POURCENTAGE_ENTER_PERCENTAGE }}</b>{{ STR_BEFORE_TWO_POINTS }}:
				<input class="formulaire3" type="text"{% if (percent_prod) %} value="{{ percent_prod|str_form_value }}"{% endif %} name="percent_prod" size="8" />
				<select name="operation">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="plus"{% if operation == 'plus' %} selected="selected"{% endif %}>{{ STR_ADMIN_PRIX_POURCENTAGE_RAISE }}</option>
					<option value="minus"{% if operation == 'minus' %} selected="selected"{% endif %}>{{ STR_ADMIN_PRIX_POURCENTAGE_LOWER }}</option>
				</select><br />
			</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align:center;padding-top:15px;">
				<input class="formulaire1" type="hidden" name="submit" value="ok" />
				<input class="bouton" type="submit" value="{{ STR_VALIDATE|str_form_value }}" name="validate" />
			</td>
		</tr>
	</table>
</form>