{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: arbre_rubrique.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}{% if not (hidden) %}<span typeof="Breadcrumb"><a property="url" href="{{ href|escape('html') }}" title="{{ label|escape('html') }}"><span property="title">{{ label }}</span></a></span>{% else %}
<span typeof="Breadcrumb"><span property="url" content="{{ href|escape('html') }}"><span property="title" content="{{ label }}"></span></span></span>
{% endif %}