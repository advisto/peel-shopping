{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: checkboxes.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}{foreach $options as $o}
	<input type="checkbox" name="{$o.name|str_form_value}[]" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} /> {$o.text}<br />
{/foreach}