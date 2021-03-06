{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: devises.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="select_currency">
	<select class="form-control" name="devise" onchange="document.location='{$url_part|htmlspecialchars|addslashes}'+this.options[this.selectedIndex].value" aria-label="{$STR_MODULE_DEVISES_CHOISIR_DEVISE|str_form_value}">
	{foreach $options as $o}
		<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name|html_entity_decode_if_needed}</option>
	{/foreach}
	</select>
</div>