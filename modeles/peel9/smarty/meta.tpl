{* Smarty
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
// $Id: meta.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if !empty($charset)}<meta charset="{$charset}" />{/if}
<title>{$title|escape:'html'}</title>
<meta name="keywords" content="{$keywords|str_form_value}" />
<meta name="description" content="{$description|str_form_value}" />
{if !empty($site)}
<meta name="author" content="{$site|str_form_value}" />
{/if}
<meta name="generator" content="{$generator|str_form_value}" />
<meta name="robots" content="{$robots|str_form_value}" />
{if isset($facebook_tag)}{$facebook_tag}{/if}
{if isset($specific_meta)}{$specific_meta}{/if}
