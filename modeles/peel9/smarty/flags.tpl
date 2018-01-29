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
// $Id: flags.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{foreach $data as $d}
{if $display_names}<div class="full_flag">{/if}<span class="{$d.flag_css_class}" lang="{$d.lang}" title="{$d.lang_name}">{if not $d.selected}<a href="{$d.href|htmlspecialchars}" title="{$d.lang_name}">{/if}<img class="{$d.flag_css_class}" src="{$d.src|escape:'html'}" alt="{$d.lang_name}"{if $force_width} width="{$force_width}" height="{$force_width}"{/if} />{if not $d.selected}</a>{/if}</span>{if not $d@last}&nbsp;{/if}{if $display_names}<br /><a href="{$d.href|htmlspecialchars}">{$d.lang_name}</a></div>{/if}
{/foreach}