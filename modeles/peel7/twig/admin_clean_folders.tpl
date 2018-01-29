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
// $Id: admin_clean_folders.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="center">
	<a class="btn btn-primary" href="{{ wwwroot }}/?update=1">CSS &amp; Javascript{{ STR_BEFORE_TWO_POINTS }}: {{ STR_REFRESH }}</a>
</div>
<hr />
<form class="entryform form-inline" role="form" method="post" action="{{ action_thumbs|escape('html') }}">
	<div class="alert alert-info"><p>{{ STR_TEXT_CONFIG }}</p></div>
	<div class="center">
		<label>
			<input class="btn btn-primary" type="submit" value="{{ STR_CLEAN|str_form_value }}" />
		</label>
  	</div>
</form>
<hr/>
<form class="entryform form-inline" role="form" method="post" action="{{ action_cache|escape('html') }}">
	<div class="alert alert-info"><p>{{ STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE_EXPLAIN }}</p></div>
	<div class="center">
		<label>
			<input class="btn btn-primary" type="submit" value="{{ STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE|str_form_value }}" />
		</label>
  	</div>
</form>
<hr />
<center>
	<form class="entryform form-inline" role="form" method="post" action="{{ action_images|escape('html') }}">
		<table class="main_table">
			<tr>
				<td colspan="2">
					<div class="alert alert-info"><p>{{ STR_ADMIN_CLEAN_FOLDERS_OPTIMIZE_IMAGES_EXPLAIN }}</p></div>
				</td>
			</tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_IMAGE_SHORT_PATH }}</b></td>
				<td><span style="word-wrap: break-word;">{{ dirroot }}/</span><input style="width:250px;" size="50" type="text" class="form-control" name="file_shortpath" value="{{ file_shortpath|str_form_value }}" /></td>
			<tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_MINIMAL_SIZE }}</b></td>
				<td><input style="width:250px;" type="text" class="form-control" name="size_ko" value="{{ size_ko|str_form_value }}" /> {{ STR_KILOBYTE }}</td>
			</tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_QUALITY }}</b></td>
				<td><input style="width:250px;" type="text" class="form-control" name="tx_qualite" value="{{ tx_qualite|str_form_value }}" /> %</td>
			</tr>
			<tr>
				<td class="top"><b>{{ STR_ADMIN_CLEAN_FOLDERS_ENLIGHTEN_IMAGE }}</b></td>
				<td><input type="checkbox" name="enlighten" value="1"{% if enlighten == "on" %} checked="checked"{% endif %} /></td>
			</tr>
			<tr>
				<td colspan="2" height="15"></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><input class="btn btn-primary" type="submit" value="{{ STR_SUBMIT|str_form_value }}" onclick="return advisto_form_confirm('{{ STR_ADMIN_CONFIRM_JAVASCRIPT|filtre_javascript(true,true,true) }}', this);" /></td>
			</tr>
		</table>
	</form>
</center>