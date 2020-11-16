{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributs_option_image.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<a{% if file_type!='image' %} {% if set %} id="zoom1"{% endif %} class="lightbox" onclick="return false;" {% else %} target="attributs_option_pdf"{% endif %} href="{{ href|escape('html') }}"><img src="{{ src|escape('html') }}" alt="" /></a>