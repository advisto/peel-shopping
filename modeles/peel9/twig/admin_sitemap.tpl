{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_sitemap.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="full_width"><tr><td class="entete">{{ STR_ADMIN_SITEMAP_TITLE }}</td></tr></table>
<ul><li><a href="{{ href|escape('html') }}">{{ STR_ADMIN_SITEMAP_OPEN }} {{ href }}</a></li></ul>