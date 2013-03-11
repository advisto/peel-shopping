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
// $Id: admin_google_sitemap.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<?xml version="1.0" encoding="{{ GENERAL_ENCODING|upper }}"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<url><loc>{{ wwwroot }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
<url><loc>{{ wwwroot }}/achat/</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
<url><loc>{{ wwwroot }}/lire/</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
<url><loc>{{ wwwroot }}/membre.php</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
<url><loc>{{ wwwroot }}/compte.php</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
<url><loc>{{ url_enregistrement }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
{% for p in products %}
<url><loc>{{ p }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
{% endfor %}
</urlset>