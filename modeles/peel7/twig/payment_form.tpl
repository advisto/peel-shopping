{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: payment_form.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}{% if type == 'check' or type == 'transfer' %}
	<p>- <a href="{{ commande_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ print_proforma_txt }}</a></p>
	{% if type == 'check' %}
	<p>- {{ send_check_txt }}{{ STR_BEFORE_TWO_POINTS }}: {{ societe }}</p>
	{% else %}
	<p>- {{ send_STR_TRANSFER }}{{ STR_BEFORE_TWO_POINTS }}:<br />{{ rib }}</p>
	{% endif %}
{% elseif type == 'paypal' and (form) %}
	<div class="center">
	{{ reglement_carte_bancaire_txt }}<br />
	{{ form }}
	<br />
	{{ paypal_img_txt }}
	</div>
{% elseif (form) %}
	<div class="center">{{ form }}</div>
{% endif %}
{% if (js_action) and (autosend_delay) %}
<script><!--//--><![CDATA[//><!--
	setTimeout ('{{ js_action }}', {{ autosend_delay }});
//--><!]]></script>
{% endif %}