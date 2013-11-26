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
// $Id: index.tpl 38682 2013-11-13 11:35:48Z gboussin $
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