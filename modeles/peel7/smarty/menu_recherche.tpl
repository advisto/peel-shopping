{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: menu_recherche.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<div{if $display_mode == 'header'} id="top_search"{/if}>
	<form class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}" id="recherche">
		<fieldset>
			<input type="hidden" name="match" id="search_match" value="1" />
			<div id="search_wrapper" class="input-group">
				<input type="text" class="form-control" name="search" id="search" value="" placeholder="{$STR_SEARCH}" />
				<span class="input-group-addon">
					<select class="form-control" id="search_category" name="categorie">
						<option value="">{$STR_CATEGORY}</option>
						{$select_categorie}
					</select>
				</span>
				<span class="input-group-btn">
					<input type="submit" class="btn btn-default btn-header_search" value="GO" />
				</span>
			</div><!-- /input-group -->
		</fieldset>
	</form>
</div>