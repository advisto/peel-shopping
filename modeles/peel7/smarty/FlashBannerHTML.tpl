{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: FlashBannerHTML.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<object wmode="transparent" data="{$url|escape:'html'}# type="application/x-shockwave-flash" width="{$width}" height="{$height}">
	<param name="movie" value="{$url}" />
	<param name="quality" value="high" />
	<param name="menu" value="false" />
	<param name="wmode" value="transparent">
	{if $mode_transparent == TRUE}
	<param name="wmode" value="transparent" />
	{/if}
 	<param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />
</object>