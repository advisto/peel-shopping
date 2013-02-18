{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_clean_folders.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="post" action="{{ action_thumbs|escape('html') }}">
	<p>{{ STR_TEXT_CONFIG }}</p>
	<div class="center">
		<label>
			<input class="bouton" type="submit" value="{{ STR_CLEAN|str_form_value }}" />
		</label>
  	</div>
</form><br /><br />
<hr/>
<form method="post" action="{{ action_cache|escape('html') }}">
	<p>{{ STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE_EXPLAIN }}</p>
	<div class="center">
		<label>
			<input class="bouton" type="submit" value="{{ STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE|str_form_value }}" />
		</label>
  	</div>
</form><br /><br />
<hr />
<center>
	<form method="post" action="{{ action_images|escape('html') }}">
		<table>
			<tr>
				<td colspan="2">{{ STR_ADMIN_CLEAN_FOLDERS_OPTIMIZE_IMAGES_EXPLAIN }}</td>
			</tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_IMAGE_SHORT_PATH }}</b></td>
				<td>{{ dirroot }}/<input size="50" type="text" name="file_shortpath" value="{{ file_shortpath|str_form_value }}" /></td>
			<tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_MINIMAL_SIZE }}</b></td>
				<td><input size="10" type="text" name="size_ko" value="{{ size_ko|str_form_value }}" /><b> {{ STR_KILOBYTE }}</b></td>
			</tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_QUALITY }}</b></td>
				<td><input size="3" type="text" name="tx_qualite" value="{{ tx_qualite|str_form_value }}" /><b>%</b></td>
			</tr>
			<tr>
				<td colspan="2" class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_ENLIGHTEN_IMAGE }} </b><input type="checkbox" name="enlighten" value="1"{% if enlighten == "on" %} checked="checked"{% endif %} /></td>
			</tr>
			<tr>
				<td colspan="2" height="15"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><input class="bouton" type="submit" value="{{ STR_SUBMIT|str_form_value }}" onclick="return confirm('{{ STR_ADMIN_CONFIRM_JAVASCRIPT|filtre_javascript(true,true,true) }}');" /></td>
			</tr>
		</table>
	</form>
</center>