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
// $Id: attributs_option_image.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<a{if $file_type=='image'} {if $set} id="zoom1"{/if}{if $lightbox} class="lightbox" onclick="return false;"{/if}{else} target="attributs_option_pdf"{/if} href="{$href|escape:'html'}"><img src="{$src|escape:'html'}" alt="" /></a>
