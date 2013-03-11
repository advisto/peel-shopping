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
// $Id: compte_mini.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<div style="padding:5px;">
	<a href="{$membre_href|escape:'html'}">{$STR_HELLO}&nbsp;{$prenom|html_entity_decode_if_needed} {$nom_famille|html_entity_decode_if_needed}</a>
	<br /><a href="{$sortie_href|escape:'html'}">{$STR_DECONNECT}</a>
	<br /><a href="{$compte_href|escape:'html'}">{$STR_COMPTE}</a>
	<br /><a href="{$history_href|escape:'html'}">{$STR_ORDER_HISTORY}</a>
</div>
{if isset($fb_deconnect_lbl)}
	<br /><a id="facebook_connect_logout" href="#">{$fb_deconnect_lbl}</a>
{/if}