{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user_origins.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<select class="form-control" id="origin" name="origin" onchange="origin_change(this.value, {$origin_infos.origin_other_ids_for_javascript})">
	<option value="">{$origin_infos.STR_CHOOSE}...</option>
	{foreach $origin_infos.options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
	{/foreach}
</select>
<span id="origin_other" {if !$origin_infos.is_origin_other_activated} style="display:none"{/if}><input name="origin_other" value="{$origin_infos.origin_other|str_form_value}" class="origin_other" /></span>