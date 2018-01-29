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
// $Id: admin_google_sitemap.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<?xml version="1.0" encoding="{{ GENERAL_ENCODING|upper }}"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
{% for wwwroot in wwwroot_array %}
<url><loc>{{ wwwroot }}</loc><lastmod>{{ date }}</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>
{% endfor %}
{% for product_category_url in product_category_url_array %}
<url><loc>{{ product_category_url }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>
{% endfor %}
{% for content_category_url in content_category_url_array %}
<url><loc>{{ content_category_url }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
{% endfor %}
{% for p in products %}
<url><loc>{{ p }}</loc><lastmod>{{ date }}</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>
{% endfor %}
{% for p in content_url_array %}
<url><loc>{{ p }}</loc><lastmod>{{ date }}</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
{% endfor %}
{% for legal_url in legal_url_array %}
<url><loc>{{ legal_url }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.3</priority></url>
{% endfor %}
{% for account_register_url in account_register_url_array %}
<url><loc>{{ account_register_url }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.2</priority></url>
{% endfor %}
{% for account_url in account_url_array %}
<url><loc>{{ account_url }}</loc><lastmod>{{ date }}</lastmod><changefreq>monthly</changefreq><priority>0.1</priority></url>
{% endfor %}
</urlset>