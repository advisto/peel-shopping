{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: alpha.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}<h1 property="name" class="page_title">{$title}</h1>
<div class="page_content">
	<table class="full_width" cellpadding="3">
		{foreach $map as $letter}
			<tr><td colspan="2">{$letter.value}</td></tr>
			{foreach $letter.items as $item}
				<tr><td><a href="{$item.href|escape:'html'}">{$item.name|html_entity_decode_if_needed} ({$item.count})</a></td></tr>
			{/foreach}
		{/foreach}
	</table>
</div>