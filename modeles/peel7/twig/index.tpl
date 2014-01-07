{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}{% if (error) %}
<div class="alert alert-danger">
	{{ error }}
</div>
{% endif %}
{% if (home_title) %}
	{{ home_title }}
{% endif %}
<div class="page_home_content">
{% if carrousel_html %}
	{{ carrousel_html }}
{% endif %}
	{{ contenu_html }}
	{{ categorie_accueil }}
	{{ notre_selection }}
</div>