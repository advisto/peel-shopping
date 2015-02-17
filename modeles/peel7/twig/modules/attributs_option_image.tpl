{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributs_option_image.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}<a{% if !is_pdf %} {% if set %} id="zoom1"{% endif %} class="lightbox" onclick="return false;" {% else %} target="attributs_option_pdf"{% endif %} href="{{ href|escape('html') }}"><img src="{{ src|escape('html') }}" alt="" /></a>