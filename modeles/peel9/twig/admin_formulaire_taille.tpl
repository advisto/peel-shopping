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
// $Id: admin_formulaire_taille.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_TAILLES_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} - {{ lang_names[l.lng]|upper }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
   	 	</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TAILLES_OVERWEIGHT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="poids" style="width:100px" value="{{ poids|str_form_value }}" /> {{ STR_ADMIN_GRAMS }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TAILLES_OVERCOST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="prix" style="width:100px" value="{{ prix|str_form_value }}" /> <b>{{ site_symbole }} {{ STR_TTC }}</b></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TAILLES_OVERCOST_RESELLER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="prix_revendeur" style="width:100px" value="{{ prix_revendeur|str_form_value }}" /> <b>{{ site_symbole }} {{ STR_TTC }}</b></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TAILLES_SIGN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><select class="form-control" name="signe"><option value="+"{% if signe == "+" %} selected="selected"{% endif %}>+</option><option value="-"{% if signe == "-" %} selected="selected"{% endif %}>-</option></select></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="position" value="{{ position|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>