{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: paypal_form.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<form class="entryform form-inline" role="form" id="paypalForm" action="{{ url }}" method="post">
	<input type="hidden" name="charset" value="{{ charset|str_form_value }}">
	<input type="hidden" name="cmd" value="_ext-enter" />
	<input type="hidden" name="redirect_cmd" value="_xclick" />
	<input type="hidden" name="business" value="{{ business|str_form_value }}" />
	<input type="hidden" name="item_name" value="{{ item_name|str_form_value }}" />
	<input type="hidden" name="item_number" value="{{ item_number|str_form_value }}" />
	<input type="hidden" name="amount" value="{{ amount|str_form_value }}" />
	<input type="hidden" name="page_style" value="Primary" />
	<input type="hidden" name="first_name" value="{{ first_name|str_form_value }}">
	<input type="hidden" name="last_name" value="{{ last_name|str_form_value }}">
	<input type="hidden" name="address1" value="{{ address1|str_form_value }}">
	<input type="hidden" name="address2" value="{{ address2|str_form_value }}">
	<input type="hidden" name="zip" value="{{ zip|str_form_value }}">
	<input type="hidden" name="city" value="{{ city|str_form_value }}">
	<input type="hidden" name="country" value="{{ country|str_form_value }}">
	{% if prenom_bill %}<input type="hidden" name="billing_first_name" value="{{ prenom_bill|str_form_value }}" />{% endif %}
	{% if nom_bill %}<input type="hidden" name="billing_last_name" value="{{ nom_bill|str_form_value }}" />{% endif %}
	{% if adresse1_bill %}<input type="hidden" name="billing_address1" value="{{ adresse1_bill|str_form_value }}" />{% endif %}
	{% if adresse2_bill %}<input type="hidden" name="billing_address2" value="{{ adresse2_bill|str_form_value }}" />{% endif %}
	{% if zip_bill %}<input type="hidden" name="billing_zip" value="{{ zip_bill|str_form_value }}" />{% endif %}
	{% if ville_bill %}<input type="hidden" name="billing_city" value="{{ ville_bill|str_form_value }}" />{% endif %}
	{% if pays_bill %}<input type="hidden" name="billing_country" value="{{ pays_bill|str_form_value }}" />{% endif %}
	<input type="hidden" name="return" value="{{ return|str_form_value }}" />
	<input type="hidden" name="cancel_return" value="{{ cancel_return|str_form_value }}" />
	<input type="hidden" name="notify_url" value="{{ notify_url|str_form_value }}" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="currency_code" value="{{ currency_code|str_form_value }}" />
	<input type="hidden" name="lc" value="{{ lc|upper|str_form_value }}" />
	<input type="hidden" name="email" value="{{ email|str_form_value }}" />
	{{ additional_fields }}
	<input type="image" src="{{ paypal_bouton_src|str_form_value }}"  name="submit" alt="{{ paypal_button_alt|str_form_value }}" />
</form>