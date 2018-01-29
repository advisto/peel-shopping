{# Twig
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
// $Id: admin_home_peel_desc1.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{{ STR_ADMIN_INDEX_PEEL_DESC1 }}
<p><img src="{{ src|escape('html') }}" alt="link_icon" style="margin-right:5px;" /><a href="{{ last_offers_href|escape('html') }}">{{ STR_ADMIN_INDEX_PEEL_LAST_OFFERS }}</a></p>
<p><img src="{{ src|escape('html') }}" alt="link_icon" style="margin-right:5px;" /><a href="{{ custom_modules_href|escape('html') }}">{{ STR_ADMIN_INDEX_CUSTOM_MODULES }}</a></p>
<p><img src="{{ src|escape('html') }}" alt="link_icon" style="margin-right:5px;" /><a href="{{ contact_us_href|escape('html') }}">{{ STR_ADMIN_CONTACT_US }}</a></p>