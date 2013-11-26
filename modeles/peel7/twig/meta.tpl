{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: meta.tpl 38978 2013-11-24 23:18:34Z gboussin $
#}{% if (charset) %}<meta charset="{{ charset }}" />{% endif %}
<title>{{ title|escape('html') }}</title>
<meta name="keywords" content="{{ keywords|str_form_value }}" />
<meta name="description" content="{{ description|str_form_value }}" />
<meta name="robots" content="All" />
{% if (site) %}
<meta name="author" content="{{ site|str_form_value }}" />
<meta name="publisher" content="{{ site|str_form_value }}" />
{% endif %}
<meta name="generator" content="{{ generator|str_form_value }}" />
<meta name="robots" content="{{ robots|str_form_value }}" />
{% if (facebook_tag) %}{{ facebook_tag }}{% endif %}
{% if (specific_meta) %}{{ specific_meta }}{% endif %}