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
// $Id: admin_formulaire_tab.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<input type="hidden" name="lng" value="{{ lng|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_PRODUITS_UPDATE_TABS_CONTENT }} {{ product_name }}</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="display_tab" value="1"{% if display_tab == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="display_tab" value="0"{% if display_tab == '0' %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°1</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab1_title_{{ lng }}" size="70" value="{{ tab1_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab1_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°2</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab2_title_{{ lng }}" size="70" value="{{ tab2_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab2_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°3</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab3_title_{{ lng }}" size="70" value="{{ tab3_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab3_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°4</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab4_title_{{ lng }}" size="70" value="{{ tab4_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab4_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°5</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab5_title_{{ lng }}" size="70" value="{{ tab5_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab5_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TAB }} {{ lng|upper }} n°6</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tab6_title_{{ lng }}" size="70" value="{{ tab6_title|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ tab6_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><input class="bouton" type="submit" value="{{ titre_soumet|str_form_value }}" /></td>
		</tr>
	</table>
</form>