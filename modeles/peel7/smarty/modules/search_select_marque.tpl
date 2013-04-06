{* Smarty
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
// $Id: search_select_marque.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<select id="brand" name="brand" onchange="gotobrand(this.options[this.selectedIndex].value)">
	<option value="">{$STR_SEARCH_BRAND}</option>
	{foreach $options as $o}
	<option{if isset($o.id)} id="{$o.id}"{/if} value="{$o.value|str_form_value}">{$o.name|html_entity_decode_if_needed}</option>
	{/foreach}
</select>