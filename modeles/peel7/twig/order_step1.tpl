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
// $Id: order_step1.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}{% if (error_cvg) %}
	<p>{{ error_cvg }}</p>
{% endif %}
<h2 class="order_step1">&nbsp;{{ STR_STEP1 }}</h2>
<form id="entryformstep" method="post" action="{{ action|escape('html') }}">
	<div class="stepgauche">
		<fieldset>
			<legend>{{ STR_INVOICE_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}: </legend>
			<p class="right">
				<label for="societe1">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="societe1" id="societe1" size="32" value="{{ societe1|str_form_value }}" />
			</p>
			{{ nom1_error }}
			<p class="right">
				<label for="nom1">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="nom1" id="nom1" size="32" value="{{ nom1|str_form_value }}" />
			</p>
			{{ prenom1_error }}
			<p class="right">
				<label for="prenom1">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="prenom1" id="prenom1" size="32" value="{{ prenom1|str_form_value }}" />
			</p>
			{{ email1_error }}
			<p class="right">
				<label for="email1">{{ STR_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="email1" id="email1" size="32" value="{{ email1|str_form_value }}" />
			</p>
			{{ contact1_error }}
			<p class="right">
				<label for="contact1">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="contact1" id="contact1" size="32" value="{{ contact1|str_form_value }}" />
			</p>
			{{ adresse1_error }}
			<p class="right" style="margin-bottom:35px;">
				<label for="adresse1">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<textarea cols="50" rows="3" name="adresse1" id="adresse1" class="formulaire-achat textarea-formulaire">{{ adresse1 }}</textarea>
			</p>
			{{ code_postal1_error }}
			<p class="right">
				<label for="code_postal1">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="code_postal1" id="code_postal1" size="32" value="{{ code_postal1|str_form_value }}" />
			</p>
			{{ ville1_error }}
			<p class="right">
				<label for="ville1">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="ville1" id="ville1" size="32" value="{{ ville1|str_form_value }}" />
			</p>
			{{ pays1_error }}
			<p class="right">
				<label for="pays1">{{ STR_COUNTRY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<select class="formulaire-achat" name="pays1" id="pays1">
					{{ pays1_options }}
				</select>
			</p>
		</fieldset>
	</div>
	{% if is_mode_transport %}
	<div class="stepdroite">
		<fieldset>
			<legend>{{ STR_SHIP_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</legend>
			{% if (text_temp_STR_ADDRESS) %}{{ text_temp_STR_ADDRESS }}{% endif %}
			
			<p class="right">
				<label for="societe2">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="societe2" id="societe2" size="32" value="{{ societe2|str_form_value }}" />
			</p>
			{{ nom2_error }}
			<p class="right">
				<label for="nom2">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="nom2" id="nom2" size="32" value="{{ nom2|str_form_value }}" />
			</p>
			{{ prenom2_error }}
			<p class="right">
				<label for="prenom2">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="prenom2" id="prenom2" size="32" value="{{ prenom2|str_form_value }}" />
			</p>
			{{ email2_error }}
			<p class="right">
				<label for="email2">{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="email2" id="email2" size="32" value="{{ email2|str_form_value }}" />
			</p>
			{{ contact2_error }}
			<p class="right">
				<label for="contact2">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="contact2" id="contact2" size="32" value="{{ contact2|str_form_value }}" />
			</p>
			{{ adresse2_error }}
			<p class="right" style="margin-bottom:35px;">
				<label for="adresse2">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<textarea cols="50" rows="3" class="formulaire-achat textarea-formulaire" name="adresse2" id="adresse2">{{ adresse2 }}</textarea>
			</p>
			{{ code_postal2_error }}
			<p class="right">
				<label for="code_postal2">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="code_postal2" id="code_postal2" size="32" value="{{ code_postal2|str_form_value }}" />
			</p>
			{{ ville2_error }}
			<p class="right">
				<label for="ville2">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<input class="formulaire-achat" type="text" name="ville2" id="ville2" size="32" value="{{ ville2|str_form_value }}" />
			</p>
			{{ pays2_error }}
			<p class="right">
				<label for="pays2">{{ STR_COUNTRY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: </label>
				<select class="formulaire-achat" name="pays2" id="pays2">
					{{ pays2_options }}
				</select>
			</p>
		</fieldset>
	</div>
	{% endif %}
	<div style="float:left;">
		{% if is_payment_cgv %}
		<fieldset>
			<legend>{{ STR_PAYMENT }}{{ STR_BEFORE_TWO_POINTS }}: </legend>{% if (STR_ERR_PAYMENT) %}<p class="global_error">{{ STR_ERR_PAYMENT }}</p>{% endif %}
			<p>{{ payment_error }}{{ payment_select }}</p>
		</fieldset>
		{% endif %}
		<fieldset>
			<legend>{{ STR_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}: </legend>
			<p><textarea class="formulaire-achat" name="commentaires" cols="54" rows="5">{{ commentaires }}</textarea></p>
		</fieldset>
		<div class="center">
			<p><input type="checkbox" name="cgv" value="1" />{{ STR_CGV_OK }}</p>
			<input type="submit" value="{{ STR_ETAPE_SUIVANTE|str_form_value }}" class="clicbouton" />
		</div>
	</div>
</form>