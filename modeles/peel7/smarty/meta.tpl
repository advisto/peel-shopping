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
// $Id: meta.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<meta charset="{$charset}" />
<title>{$title|escape:'html'}</title>
<meta name="keywords" content="{$keywords|str_form_value}" />
<meta name="description" content="{$description|str_form_value}" />
<meta name="robots" content="All" />
{if !empty($site)}
<meta name="author" content="{$site|str_form_value}" />
<meta name="publisher" content="{$site|str_form_value}" />
{/if}
<meta name="generator" content="{$generator}" />
<meta name="robots" content="all" />
{if isset($facebook_tag)}{$facebook_tag}{/if}
