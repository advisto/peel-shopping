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
// $Id: payment_select.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
#}<table>
	<tr>
		<td>
{% if technical_code == 'moneybookers' %}
			<input type="radio" onclick="document.getElementById('payment_method').style.display='block';" name="payment_technical_code" value="{{ technical_code|str_form_value }}"{% if issel %} checked="checked"{% endif %} /> {{ nom|html_entity_decode_if_needed }}
	{% if (fprix_tarif) %}
			{{ STR_BEFORE_TWO_POINTS }}: + {{ fprix_tarif }}
	{% endif %}
	{% if (tarif_percent) %}
			{{ STR_BEFORE_TWO_POINTS }}: + {{ tarif_percent }} %
	{% endif %}
			<br />
			<span id="payment_method" class="well" style="display: {% if isempty_moneybookers_payment_methods and not issel %} none{% else %} block{% endif %};">
				<input {% if moneybookers_payment_methods == 'VSA' %}checked="checked"{% endif %} type="radio" name="moneybookers_payment_methods" value="VSA" /> Visa<br />
				<input {% if moneybookers_payment_methods == 'MSC' %}checked="checked"{% endif %} type="radio" name="moneybookers_payment_methods" value="MSC" /> Mastercard<br />
				<input {% if moneybookers_payment_methods == 'GCB' %}checked="checked"{% endif %} type="radio" name="moneybookers_payment_methods" value="GCB" /> Carte Bleue<br />
				<input {% if moneybookers_payment_methods == 'PLI,EPY,NPY,SO2,ENT,EBT,PWY,IDL,SFT,GIR,DID,OBT' %}checked="checked"{% endif %} type="radio" name="moneybookers_payment_methods" value="PLI,EPY,NPY,SO2,ENT,EBT,PWY,IDL,SFT,GIR,DID,OBT" /> {{ STR_TRANSFER }}<br />
				<input {% if moneybookers_payment_methods == 'WLT' %}checked="checked"{% endif %} type="radio" name="moneybookers_payment_methods" value="WLT" /> Moneybookers e-wallet<br />
			</span>
{% else %}
{% if technical_code == 'order_form' %}
			<input type="radio" onclick="document.getElementById('payment_method').style.display='block';" name="payment_technical_code" value="{{ technical_code|str_form_value }}"{% if issel %} checked="checked"{% endif %} /> {{ nom|html_entity_decode_if_needed }}
			<br />
			<span id="payment_method" class="well" style="display: {% if issel is empty %} none{% else %} block{% endif %};">
				{{ STR_ORDER_FORM }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" name="{{ technical_code }}_payment_methods" value="" /> <br />{% if order_form_payment_methods_error is defined %}{{ order_form_payment_methods_error }}{% endif %}
			</span>
{% else %}
			<input onclick="if(document.getElementById('payment_method')) {ldelim}document.getElementById('payment_method').style.display='none';{rdelim}" type="radio" name="payment_technical_code" value="{{ technical_code|str_form_value }}"{% if issel %} checked="checked"{% endif %} /> {{ nom|html_entity_decode_if_needed }}
{% endif %}
	{% if (fprix_tarif) %}
			{{ STR_BEFORE_TWO_POINTS }}: + {{ fprix_tarif }}
	{% endif %}
	{% if (tarif_percent) %}
			{{ STR_BEFORE_TWO_POINTS }}: + {{ tarif_percent }} %
	{% endif %}
			<br />
{% endif %}
		</td>
{% if (payment_complement_informations) %}
		<td>{{ payment_complement_informations }}</td>
{% endif %}
	</tr>
</table>