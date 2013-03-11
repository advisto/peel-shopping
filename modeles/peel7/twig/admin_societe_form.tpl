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
// $Id: admin_societe_form.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS }}</td>
		</tr>
		<tr>
			<td colspan="2"><p>{{ STR_ADMIN_SOCIETE_FORM_EXPLAIN }}</p></td>
		</tr>
		<tr>
			<td style="width:250px;">{{ STR_COMPANY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="societe" style="width:100%" value="{{ societe|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="prenom" style="width:100%" value="{{ prenom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="nom" style="width:100%" value="{{ nom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="email" style="width:100%" value="{{ email|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="siteweb" style="width:100%" value="{{ siteweb|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_SIREN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="siren" style="width:100%" value="{{ siren|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_VAT_INTRACOM }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="tvaintra" style="width:100%" value="{{ tvaintra|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_CNIL_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="cnil" style="width:100%" value="{{ cnil|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea name="adresse" class="textarea-formulaire">{{ adresse }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="code_postal" style="width:100%" value="{{ code_postal|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="ville" style="width:100%" value="{{ ville|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="pays" style="width:100%" value="{{ pays|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="tel" style="width:100%" value="{{ tel|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="fax" style="width:100%" value="{{ fax|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="code_banque" style="width:100%" value="{{ code_banque|str_form_value }}" maxlength="5" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_COUNTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="code_guichet" style="width:100%" value="{{ code_guichet|str_form_value }}" maxlength="5" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="numero_compte" style="width:100%" value="{{ numero_compte|str_form_value }}" maxlength="11" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_RIB }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="cle_rib" style="width:100%" value="{{ cle_rib|str_form_value }}" maxlength="2" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_DOMICILIATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="domiciliation" style="width:100%" value="{{ domiciliation|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_IBAN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="iban" style="width:100%" value="{{ iban|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_SWIFT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="swift" style="width:100%" value="{{ swift|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ACCOUNT_MASTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="titulaire" style="width:100%" value="{{ titulaire|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><p>{{ STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS }}</p></td>
		</tr>
		<tr>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea name="adresse2" class="textarea-formulaire">{{ adresse2 }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="code_postal2" style="width:100%" value="{{ code_postal2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="ville2" style="width:100%" value="{{ ville2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="pays2" style="width:100%" value="{{ pays2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="tel2" style="width:100%" value="{{ tel2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="fax2" style="width:100%" value="{{ fax2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>