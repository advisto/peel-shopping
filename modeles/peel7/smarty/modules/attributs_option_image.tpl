{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributs_option_image.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}{if $set}
<a onclick="return(window.open(this.href)?false:true);" id="zoom1" class="lightbox" href="{$href|escape:'html'}"><img src="{$src|escape:'html'}" alt="" /></a>
{else}
<a class="lightbox" href="{$href|escape:'html'}"><img src="{$src|escape:'html'}" alt="" /></a>
{/if}