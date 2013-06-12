{* Smarty
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
// $Id: installation_bdd.tpl 37098 2013-06-01 22:27:03Z gboussin $
*}<div id="contourMenu">
	<div id="menuHorizontal">
		<h1>{$step_title|escape:'html'}</h1>
	</div>
</div>
<!-- Contenu -->
<div id="contenu">
	<p>{$STR_ADMIN_INSTALL_DATABASE_INTRO_1|escape:'html'}<br />
	{$STR_ADMIN_INSTALL_DATABASE_INTRO_2|escape:'html'}</p>
	<p>{$STR_ADMIN_INSTALL_DATABASE_INTRO_3|escape:'html'}<br />
	{$STR_ADMIN_INSTALL_DATABASE_INTRO_4|escape:'html'}</p>
	{$confirm_message}
	<form action="choixbase.php" method="post" class="left">
		<table>
			<tr>
				<td colspan="2">
					<p><i>{$STR_ADMIN_INSTALL_EXPLAIN_SSL|escape:'html'}</i></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p><label>{$STR_ADMIN_INSTALL_URL_STORE|escape:'html'}</label> <input type="url" style="width:350px" name="wwwroot" placeholder="http://" value="{$wwwroot_value|str_form_value}" /></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<h2>{$STR_ADMIN_INSTALL_LANGUAGE_CHOOSE|escape:'html'}</h2>
					<p>{html_checkboxes name='langs' options=$select_languages selected=$install_langs_value separator='<br />'}</p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<h2>{$STR_ADMIN_INSTALL_SSL_ADMIN|escape:'html'}</h2>
					<p>
						<input type="radio" name="admin_force_ssl" value="0"{if $admin_force_ssl_selected == false} checked="checked"{/if} /> {$STR_ADMIN_INSTALL_SSL_ADMIN_NO|escape:'html'}<br />
						<input type="radio" name="admin_force_ssl" value="1"{if $admin_force_ssl_selected == true} checked="checked"{/if} /> {$STR_ADMIN_INSTALL_SSL_ADMIN_YES|escape:'html'}<br />
						{if $ssl_admin_explain == true}<i><a href="{$url_installation|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN|escape:'html'}</a></i>{/if}
					</p>
				</td>
			</tr>
			<tr>
				<td style="width:150px;height:30px;">
					<label>{$STR_ADMIN_INSTALL_DATABASE_SERVER|escape:'html'}</label>
				</td>
				<td>
					<input type="text" name="serveur" size="30" value="{$serveur_value|str_form_value}" />
					<i>{$STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN|escape:'html'}</i>
				</td>
			</tr>
			<tr>
				<td style="width:150px;height:30px;">
					<label>{$STR_ADMIN_INSTALL_DATABASE_USERNAME|escape:'html'}{$STR_BEFORE_TWO_POINTS}:</label>
				</td>
				<td>
					<input type="text" name="utilisateur" size="30" value="{$utilisateur_value|str_form_value}" />
				</td>
			</tr>
			<tr>
				<td style="width:150px;height:30px;">
					<label>{$STR_PASSWORD|escape:'html'}{$STR_BEFORE_TWO_POINTS}:</label>
				</td>
				<td>
					<input type="password" name="motdepasse" size="30" value="{$motdepasse_value|str_form_value}" />
				</td>
			</tr>

			<tr>
				<td>
					<input style="clear: both; float: left;" type="submit" value="{$STR_CONTINUE|str_form_value}" class="bouton" />
				</td>
			</tr>
		</table>
	</form>
</div>