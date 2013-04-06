{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_affiliation.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete">{{ STR_ADMIN_LEGAL_TITLE }}</td>
		</tr>
		<tr>
			<td>
				<table class="full_width">
					{% for l in langs %}
					<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>
					<tr>
						<td colspan="2"><b>{{ STR_ADMIN_TITLE }} <span class="etoile">*</span></b>{{ STR_BEFORE_TWO_POINTS }}: {{ l.error }}</td>
					</tr>
					<tr>
						<td colspan="2"><input style="width:100%" type="text" name="titre_{{ l.lng }}" size="50" value="{{ l.titre|str_form_value }}" /></td>
					</tr>
					<tr>
						<td class="label" colspan="2">{{ STR_ADMIN_LEGAL_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td colspan="2">{{ l.texte_te }}</td>
					</tr>
					{% endfor %}
					<tr>
						<td colspan="2" class="center"><input class="bouton" type="submit" value="{{ normal_bouton|str_form_value }}" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>	