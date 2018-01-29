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
// $Id: avisAdmin_formulaire_ajout.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if type == 'produit' %}
	{% if is_product_select_list %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table class="full_width" cellpadding="4">
				<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_AVIS_ADMIN_GIVE_OPINION }}</td>
		</tr> 
		<tr>
			<td width="200" class="title_label top">{{ STR_YOU_ARE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ prenom }} {{ nom_famille }}</td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_PSEUDO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="pseudo" value="{{ pseudo|str_form_value }}" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ product_error }}{{ product_select_list }}</td>
		</tr>
		<tr>
			<td class="top">
				<b>{{ STR_YOUR_OPINION }} <span class="etoile">*</span></b>{{ STR_BEFORE_TWO_POINTS }}:
				<br /><input type="text" class="form-control compteur" name="compteur" size="4" onfocus="blur()" value="255" /> <span style="margin-left:5px;"> {{ STR_REMINDING_CHAR }}</span>
			</td>
			<td>
				{{ error_avis }}
				<textarea class="form-control" name="avis" cols="36" rows="6" onfocus="Compter(this,255,compteur)" onkeypress="Compter(this,255,compteur)" onkeyup="Compter(this,255,compteur)" onblur="Compter(this,255,compteur)">{{ avis|html_entity_decode_if_needed }}</textarea>
			</td>
		</tr>
		<tr>
			<td class="top"><b>{{ STR_YOUR_NOTE }} <span class="etoile">*</span></b>{{ STR_BEFORE_TWO_POINTS }}: 
			</td>
			<td>
				{{ error_note }}
				{% for this_note in range(note_max, 1, -1) %}
				<input type="radio" name="note" value="{{ this_note }}" />{% for i in 1..this_note %}<img src="{{ star_src|escape('html') }}" style="vertical-align:middle" alt="*" />{% endfor %}<br />
				{% endfor %}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur }}" />
				<input type="hidden" name="prenom" value="{{ prenom|str_form_value }}" />
				<input type="hidden" name="email" value="{{ email|str_form_value }}" /> 
				<input type="hidden" name="mode" value="insere_avis" />
				<input type="hidden" name="type" value="produit" />
				<input type="hidden" name="langue" value="{{ langue|str_form_value }}" />
				<input class="btn btn-primary" type="submit" value="{{ STR_SEND|str_form_value }}" name="validate" />
			</td>
		</tr>
	</table>
</form>
	{% else %}
<div class="alert alert-success">{{ STR_MODULE_AVIS_ADMIN_NO_PRODUCT_FOUND }}</div>
	{% endif %}
{% elseif type == 'annonce' %}
	{% if is_annonce_select_list %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table class="full_width" cellpadding="4">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_AVIS_ADMIN_GIVE_OPINION }}</td>
		</tr> 
		<tr>
			<td width="200" class="title_label top">{{ STR_YOU_ARE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ prenom }} {{ nom_famille }}</td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_PSEUDO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="pseudo" value="{{ pseudo|str_form_value }}" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_MODULE_ANNONCES_AD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ annonce_error }}{{ annonce_select_list }}</td>
		</tr>
		<tr>
			<td class="top">
				<b>{{ STR_YOUR_OPINION }} <span class="etoile">*</span></b>{{ STR_BEFORE_TWO_POINTS }}:
				<br /><input type="text" class="form-control compteur" name="compteur" size="4" onfocus="blur()" value="255" />{{ STR_REMINDING_CHAR }} 
			</td>
			<td>
				{{ error_avis }}
				<textarea class="form-control" name="avis" cols="36" rows="6" onfocus="Compter(this,255,compteur)" onkeypress="Compter(this,255,compteur)" onkeyup="Compter(this,255,compteur)" onblur="Compter(this,255,compteur)">{{ avis|html_entity_decode_if_needed }}</textarea>
			</td>
		</tr>
		<tr>
			<td class="top"><b>{{ STR_YOUR_NOTE }} <span class="etoile">*</span></b>{{ STR_BEFORE_TWO_POINTS }}: 
			</td>
			<td>
				{{ error_note }}
				{% for this_note in range(note_max, 0, -1) %}
				<input type="radio" name="note" value="{{ this_note }}"{% if note == this_note %} checked="checked"{% endif %} />{% if this_note==0 %} -{% else %} {% for i in 1..this_note %}<img src="{{ star_src|escape('html') }}" style="vertical-align:middle" alt="*" />{% endfor %}{% endif %}<br />
				{% endfor %}
				<input type="radio" name="note" value="-99"{% if note == -99 %} checked="checked"{% endif %} /> {{ STR_MODULE_AVIS_POSTED_NEWS }}<br />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur }}" />
				<input type="hidden" name="prenom" value="{{ prenom|str_form_value }}" />
				<input type="hidden" name="email" value="{{ email|str_form_value }}" /> 
				<input type="hidden" name="mode" value="insere_avis" />
				<input type="hidden" name="type" value="annonce" />
				<input type="hidden" name="langue" value="{{ langue|str_form_value }}" />
				<input class="btn btn-primary" type="submit" value="{{ STR_SEND|str_form_value }}" name="validate" />
			</td>
		</tr>
	</table>
</form>
	{% else %}
<div class="alert alert-success">{{ STR_MODULE_ANNONCES_ADMIN_NO_AD_FOUND }}</div>
	{% endif %}
{% endif %}