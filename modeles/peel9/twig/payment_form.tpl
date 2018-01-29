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
// $Id: payment_form.tpl 55304 2017-11-28 15:49:01Z sdelaporte $
#}{% if type == 'check' or type == 'transfer' %}
<p><b>{{ STR_FOR_A_CHECK_PAYMENT }}</b></p>
<p>- <a href="{{ commande_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_PRINT_PROFORMA }}</a></p>
{% if not (disable_address_payment_by_check) %}<p>- {{ STR_SEND_CHECK }} <b>{{ amount_to_pay_formatted }}</b> {{ STR_FOLLOWING_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:<br />{{ societe }}</p>{% endif %}
{% elseif type == 'transfer' %}
<p><b>{{ STR_FOR_A_TRANSFERT }}</b></p>
{% if STR_MODULE_DREAMTAKEOFF_INSERT_CODE %}
	{{ STR_MODULE_DREAMTAKEOFF_INSERT_CODE }}
{% endif %}
<p>- <a href="{{ commande_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_PRINT_PROFORMA }}</a></p>
<p>- {{ STR_SEND_TRANSFER }} <b>{{ amount_to_pay_formatted }}</b> {{ STR_FOLLOWING_ACCOUNT }}{{ STR_BEFORE_TWO_POINTS }}:<br />{{ rib }}</p>
{% elseif type == 'paypal' and (form) %}
	<div class="center">
	{{ STR_FOR_A_PAYPAL_PAYMENT }}<br />
	{{ form }}
	<br />
	{{ paypal_img_html }}
	</div>
{% elseif (form) %}
	<div class="center">{{ form }}</div>
{% endif %}