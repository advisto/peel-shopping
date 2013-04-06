{# Twig
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
// $Id: meta.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<meta charset="{{ charset }}" />
<title>{{ title|escape('html') }}</title>
<meta name="keywords" content="{{ keywords|str_form_value }}" />
<meta name="description" content="{{ description|str_form_value }}" />
<meta name="robots" content="All" />
{% if (site) %}
<meta name="author" content="{{ site|str_form_value }}" />
<meta name="publisher" content="{{ site|str_form_value }}" />
{% endif %}
<meta name="generator" content="{{ generator }}" />
<meta name="robots" content="all" />
{% if (facebook_tag) %}{{ facebook_tag }}{% endif %}
