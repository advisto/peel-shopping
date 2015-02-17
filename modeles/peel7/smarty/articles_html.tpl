{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: articles_html.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}{if $is_content}
	<div class="rubrique">
		{foreach $data as $item}
		<div class="rubrique_title">
			{if $item.src}
			<img src="{$item.src|escape:'html'}" style="margin: 5px;" height="100" align="left" alt="" />
			{/if}
			<h3><a href="{$item.href|escape:'html'}">{$item.titre|html_entity_decode_if_needed}</a></h3>
			{$item.chapo|html_entity_decode_if_needed}
			<div class="inside_rubrique">
			{if $item.is_texte}
				<div class="left col-sm-6"><a href="{$item.href|escape:'html'}" class="btn btn-primary btn-red"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-right">&#160;</span>{$item.titre|html_entity_decode_if_needed}</a></div>
			{/if}
				<div class="right col-sm-6"><a href="{$haut_de_page_href|escape:'html'}"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-up">&#160;</span>{$haut_de_page_txt}</a></div>
			</div>
			<p style="clear:both;"></p>
			<hr />
		</div>
		{/foreach}
	</div>
{/if}
<p>{$multipage}</p>