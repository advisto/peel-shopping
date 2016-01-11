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
// $Id: FlashBannerHTML.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<object data="{$url|escape:'html'}" type="application/x-shockwave-flash" width="{$width}" height="{$height}">
	<param name="movie" value="{$url}" />
	<param name="quality" value="high" />
	<param name="menu" value="false" />
	{if $mode_transparent == TRUE}
	<param name="wmode" value="transparent" />
	{/if}
 	<param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />
</object>