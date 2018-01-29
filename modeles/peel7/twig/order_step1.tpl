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
// $Id: order_step1.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if (error_cvg) %}
	<p>{{ error_cvg  }}</p>
{% endif %}
<h1 property="name" class="order_step1">{{ STR_STEP1 }}</h1>
{% if STR_ADDRESS_TEXT %}<p><a href="{{ wwwroot }}/utilisateurs/adresse.php">{{ STR_ADDRESS_TEXT }}</a></p>{% endif %}
<form class="entryform form-inline order_step1_form" role="form" id="entryformstep" method="post" action="{{ action|escape('html') }}">
	<div class="row formulaire-achat">
		<div class="col-sm-6">
			<fieldset>
				<legend>{{ STR_INVOICE_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}: </legend>
				<div>
					<label for="societe1">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="societe1" id="societe1" size="32" value="{{ societe1|str_form_value }}" />
				</div>
				<div>
					<label for="nom1">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="nom1" id="nom1" size="32" value="{{ nom1|str_form_value }}" />
					{{ nom1_error }}
				</div>
				<div>
					<label for="prenom1">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="prenom1" id="prenom1" size="32" value="{{ prenom1|str_form_value }}" />
					{{ prenom1_error }}
				</div>
				<div>
					<label for="email1">{{ STR_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="email" name="email1" id="email1" size="32" value="{{ email1|str_form_value }}" />
					{{ email1_error }}
				</div>
				<div>
					<label for="contact1">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="tel" name="contact1" id="contact1" size="32" value="{{ contact1|str_form_value }}" />
					{{ contact1_error }}
				</div>
				<div>
					<label for="adresse1">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<textarea {% if order_step1_adresse_ship_disabled %}readonly="readonly"{% endif %} class="form-control" cols="50" rows="3" name="adresse1" id="adresse1">{{ adresse1 }}</textarea>
					{{ adresse1_error }}
				</div>
				<div>
					<label for="code_postal1">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input {% if order_step1_adresse_ship_disabled %}readonly="readonly"{% endif %} class="form-control" type="text" name="code_postal1" id="code_postal1" size="32" value="{{ code_postal1|str_form_value }}" />
					{{ code_postal1_error }}
				</div>
				<div>
					<label for="ville1">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input {% if order_step1_adresse_ship_disabled %}readonly="readonly"{% endif %} class="form-control" type="text" name="ville1" id="ville1" size="32" value="{{ ville1|str_form_value }}" />
					{{ ville1_error }}
				</div>
				<div>
					<label for="pays1">{{ STR_COUNTRY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<select{% if order_step1_adresse_ship_disabled %}readonly="readonly"{% endif %}  class="form-control" name="pays1" id="pays1">
						{{ pays1_options }}
					</select>
					{{ pays1_error }}
				</div>
			</fieldset>
		</div>
		{% if is_mode_transport %}
		<div class="col-sm-6">
			<fieldset>
				<legend>{{ STR_SHIP_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</legend>
				{% if (text_temp_STR_ADDRESS) %}{{ text_temp_STR_ADDRESS }}{% endif %}
				<div>
					<label for="societe2">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="societe2" id="societe2" size="32" value="{{ societe2|str_form_value }}" />
				</div>
				<div>
					<label for="nom2">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="nom2" id="nom2" size="32" value="{{ nom2|str_form_value }}" />
					{{ nom2_error }}
				</div>
				<div>
					<label for="prenom2">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="prenom2" id="prenom2" size="32" value="{{ prenom2|str_form_value }}" />
					{{ prenom2_error }}
				</div>
				<div>
					<label for="email2">{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="email" name="email2" id="email2" size="32" value="{{ email2|str_form_value }}" />
					{{ email2_error }}
				</div>
				<div>
					<label for="contact2">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="tel" name="contact2" id="contact2" size="32" value="{{ contact2|str_form_value }}" />
					{{ contact2_error }}
				</div>
				<div>
					<label for="adresse2">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<textarea class="form-control" cols="50" rows="3" name="adresse2" id="adresse2">{{ adresse2 }}</textarea>
					{{ adresse2_error }}
				</div>
				<div>
					<label for="code_postal2">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="code_postal2" id="code_postal2" size="32" value="{{ code_postal2|str_form_value }}" />
					{{ code_postal2_error }}
				</div>
				<div>
					<label for="ville2">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<input class="form-control" type="text" name="ville2" id="ville2" size="32" value="{{ ville2|str_form_value }}" />
					{{ ville2_error }}
				</div>
				<div>
					<label for="pays2">{{ STR_COUNTRY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
					<select class="form-control" name="pays2" id="pays2">
						{{ pays2_options }}
					</select>
					{{ pays2_error }}
				</div>
			</fieldset>
		</div>
		{% endif %}
	</div>
	<div class="row">
		<div class="col-sm-12">
			{% if is_payment_cgv %}
			<fieldset>
				<legend>{{ STR_PAYMENT }}{{ STR_BEFORE_TWO_POINTS }}: </legend>{% if (STR_ERR_PAYMENT) %}<p class="alert alert-danger">{{ STR_ERR_PAYMENT }}</p>{% endif %}
				<div>{{ payment_error }}{{ payment_select }}</div>
			</fieldset>
			{% endif %}
			<fieldset>
				<legend>{{ STR_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}: </legend>
				<div><textarea class="form-control" name="commentaires" cols="54" rows="5">{{ commentaires }}</textarea></div>
			</fieldset>
			{% if order_process_disable_cgv is empty %}
			<p><input type="checkbox" name="cgv" value="1" /> {{ STR_CGV_OK }}</p>
			{% endif %}
			{% if register_during_order_process %}
			<p><input type="checkbox" name="register_during_order_process" value="1" />{{ STR_CREATE_ACCOUNT_FUTURE_USE }}</p>
			{% else %}
			<br />
			{% endif %}
			<div class="center">
				<input type="submit" value="{{ STR_ETAPE_SUIVANTE|str_form_value }}" class="btn btn-lg btn-primary" />
			</div>
		</div>
	</div>
</form>