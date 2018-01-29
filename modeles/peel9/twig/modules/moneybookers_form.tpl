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
// $Id: moneybookers_form.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" id="MoneyBookersForm" action="https://www.moneybookers.com/app/payment.pl" method="post">
	<input type="hidden" name="pay_to_email" value="{{ pay_to_email|str_form_value }}" />
	<input type="hidden" name="transaction_id" value="{{ order_id|str_form_value }}TRY{{ try|str_form_value }}" />
	<input type="hidden" name="return_url" value="{{ return_url|str_form_value }}" />
	<input type="hidden" name="cancel_url" value="{{ cancel_url|str_form_value }}" />
	<input type="hidden" name="status_url" value="{{ status_url|str_form_value }}" />
	<input type="hidden" name="language" value="{{ lang|str_form_value }}" />
	<input type="hidden" name="merchant_fields" value="customer_number, platform, transaction_id" />
	<input type="hidden" name="customer_number" value="{{ user_id|str_form_value }}" />
	<input type="hidden" name="session_ID" value="" />
	<input type="hidden" name="pay_from_email" value="{{ user_email|str_form_value }}" />
	<input type="hidden" name="amount2_description" value="{{ STR_TOTAL_HT|str_form_value }}" />
	<input type="hidden" name="amount2" value="{{ amount2|str_form_value }}" />
	<input type="hidden" name="amount3_description" value="{{ STR_TAXE|str_form_value }}" />
	<input type="hidden" name="amount3" value="{{ amount3|str_form_value }}" />
	<input type="hidden" name="amount4_description" value="" />
	<input type="hidden" name="amount4" value="" />
	<input type="hidden" name="amount" value="{{ amount|str_form_value }}" />
	<input type="hidden" name="currency" value="{{ currency|str_form_value }}" />
	<input type="hidden" name="firstname" value="{{ firstname|str_form_value }}" />
	<input type="hidden" name="lastname" value="{{ lastname|str_form_value }}" />
	<input type="hidden" name="address" value="{{ address|str_form_value }}" />
	<input type="hidden" name="postal_code" value="{{ postal_code|str_form_value }}" />
	<input type="hidden" name="city" value="{{ city|str_form_value }}" />
	<input type="hidden" name="country" value="{{ country|str_form_value }}" />
	<input type="hidden" name="detail1_description" value="" />
	<input type="hidden" name="detail1_text" value="" />
	<input type="hidden" name="detail2_description" value="" />
	<input type="hidden" name="detail2_text" value="" />
	<input type="hidden" name="detail3_description" value="" />
	<input type="hidden" name="detail3_text" value="" />
	<input type="hidden" name="platform" value="21477249" />
	<input type="hidden" name="recipient_description" value="{{ recipient_description|str_form_value }}" />
	<input type="hidden" name="payment_methods" value="{{ payment_methods|str_form_value }}" />
	<input type="hidden" name="confirmation_note" value="" />
	{% if is_hide_login %}
	<input type="hidden" name="hide_login" value="1" />
	{% endif %}
	<input type="submit" value="{{ STR_MODULE_MONEYBOOKERS_SUBMIT_BUTTON|str_form_value }}" class="btn btn-primary" />
</form>