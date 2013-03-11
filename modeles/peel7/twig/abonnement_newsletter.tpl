{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: abonnement_newsletter.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<h1 class="page_title">{{ STR_NEWSLETTER_TITLE }}</h1>
<div class="page_content">
{% if (errors) %}
	{{ errors.token }}
	{{ errors.email }}
	{{ errors.notif }}
{% else %}
	<p class="global_success">{{ newsletter_subscribe_txt }}</p>
{% endif %}
</div>