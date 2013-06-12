{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_home.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_HTML_FORM_TITLE }}</td>
		</tr>

		<tr>
			<td>{{ STR_ADMIN_LANGUAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% for l in langs %}
				<input type="radio" name="lang" id="lang_{{ l.lng|str_form_value }}" value="{{ l.lng|str_form_value }}"{% if l.issel %} checked="checked"{% endif %} /><label for="lang_{{ l.lng|str_form_value }}">{{ l.name }}</label><br />
			{% endfor %}
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1" id="etat_1"{% if etat == '1' %} checked="checked"{% endif %} /><label for="etat_1"> {{ STR_ADMIN_ONLINE }}</label><br />
				<input type="radio" name="etat" value="0" id="etat_0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /><label for="etat_0"> {{ STR_ADMIN_OFFLINE }}</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_HTML_PLACE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="emplacement" value="header"{% if emplacement == 'header' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_HEADER }}<br />
				<input type="radio" name="emplacement" value="footer"{% if emplacement == 'footer' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_FOOTER }}<br />
				<input type="radio" name="emplacement" value="home"{% if emplacement == 'home' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_HOME }}<br />
				<input type="radio" name="emplacement" value="home_bottom" id="home_bottom"{% if emplacement == 'home_bottom' %} checked="checked"{% endif %} /><label for="home_bottom">{{ STR_ADMIN_HTML_PLACE_HOME_BOTTOM }}</label><br />
				<input type="radio" name="emplacement" value="conversion_page"{% if emplacement == 'conversion_page' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_CONVERSION_PAGE }}<br />
				<input type="radio" name="emplacement" value="footer_link"{% if emplacement == 'footer_link' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_FOOTER_LINK }}<br />
				<input type="radio" name="emplacement" value="interstitiel"{% if emplacement == 'interstitiel' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_INTERSTITIEL }}<br />
				<input type="radio" name="emplacement" value="error404" id="emplacement_error404"{% if emplacement == 'error404' %} checked="checked"{% endif %} /><label for="emplacement_error404">{{ STR_ADMIN_HTML_PLACE_ERROR404 }}</label><br />
				<input type="radio" name="emplacement" value="scrolling" id="emplacement_scrolling"{% if emplacement == 'scrolling' %} checked="checked"{% endif %} /><label for="emplacement_scrolling">{{ STR_ADMIN_HTML_PLACE_SCROLLING }}</label><br />
				<input type="radio" name="emplacement" value="contact_page" id="contact_page"{% if emplacement == 'contact_page' %} checked="checked"{% endif %} /><label for="contact_page">{{ STR_ADMIN_HTML_PLACE_CONTACT_PAGE }}</label><br />
				{% if is_carrousel_allowed %}
				<input type="radio" name="emplacement" value="entre_carrousel"{% if emplacement == 'entre_carrousel' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_CARROUSEL_TOP }}<br />
				{% endif %}
				{% if is_reseller_allowed %}
				<input type="radio" name="emplacement" value="devenir_revendeur"{% if emplacement == 'devenir_revendeur' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_BECOME_RESELLER }}<br />
				{% endif %}
				{% if is_partenaires_allowed %}
				<input type="radio" name="emplacement" value="partner"{% if emplacement == 'partner' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_PARTNER }}<br />
				{% endif %}
				{% if is_reseller_map_allowed %}
				<input type="radio" name="emplacement" value="reseller_map"{% if emplacement == 'reseller_map' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_RESELLER_MAP }}<br />
				{% endif %}
				{% if is_annonce_allowed %}
				<input type="radio" name="emplacement" value="home_ad"{% if emplacement == 'home_ad' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_ADS_TOP }}<br />
				<input type="radio" name="emplacement" value="top_create_ad"{% if emplacement == 'top_create_ad' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_TOP_CREATE_AD }}<br />
				{% endif %}
				{% if is_parrain_allowed %}
				<input type="radio" name="emplacement" value="intro_parrainage"{% if emplacement == 'intro_parrainage' %} checked="checked"{% endif %} />{{ STR_ADMIN_HTML_PLACE_INTRO_PARRAINAGE }}<br />
				{% endif %}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_TITLE_NOT_DISPLAYED }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="text" style="width:760px" name="titre" value="{{ titre|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_HTML_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:<div class="global_help">{{ STR_ADMIN_HTML_PHOTOS_WARNING }}</div></td>
		</tr>
		<tr>
			<td colspan="2">{{ contenu_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" width="760" align="center"><input class="bouton" type="submit" value="{{ STR_VALIDATE|str_form_value }}" /></td>
		</tr>
	</table>
</form>