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
// $Id: search.tpl 50572 2016-07-07 12:43:52Z sdelaporte $
*}{if isset($content)}
{$content}
{elseif !empty($result) && empty($quick_add_product_from_search_page)}
{$result}
{/if}
{$form}
{if $page<=1 && empty($quick_add_product_from_search_page)} 
{$STR_SEARCH_HELP}
{/if}