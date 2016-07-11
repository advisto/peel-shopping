{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: menu_recherche.tpl 50572 2016-07-07 12:43:52Z sdelaporte $
*}<div{if $display_mode == 'header'} id="top_search"{/if}{if $add_webpage_microdata} vocab="http://schema.org/" typeof="WebSite"{/if}>
	{if $add_webpage_microdata}<meta property="url" content="{$wwwroot|escape:'html'}/" />{/if}
	<form class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}" id="recherche"{if $add_webpage_microdata} property="potentialAction" typeof="http://schema.org/SearchAction"{/if}>
		{if $add_webpage_microdata}<meta property="target" content="{$action|escape:'html'}?search={ldelim}search{rdelim}" />{/if}
		<fieldset>
			<input type="hidden" name="match" id="search_match" value="1" />
			<div id="search_wrapper" class="input-group">
				<input type="text" class="form-control" name="search" id="search" value="" title="{$STR_SEARCH|escape:'html'}" placeholder="{$STR_SEARCH|escape:'html'}"{if $add_webpage_microdata} property="query-input"{/if} />
				{if !empty($select_categorie)}<span class="input-group-addon">
					<select class="form-control" id="search_category" name="{$category_input_name}">
						<option value="">{$STR_CATEGORY}</option>
						{$select_categorie}
					</select>
				</span>{/if}
				{if !empty($additionnal_select)}<span class="input-group-addon">{$additionnal_select}</span>{/if}
				<span class="input-group-btn">
					<input type="submit" class="btn btn-default btn-header_search" value="GO" />
				</span>
				{if !empty($additionnal_button)}<span class="input-group-addon">{$additionnal_button}</span>{/if}
			</div><!-- /input-group -->
		</fieldset>
	</form>
</div>