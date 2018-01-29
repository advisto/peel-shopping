{* Smarty
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
// $Id: admin_google_sitemap.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<?xml version="1.0" encoding="{$GENERAL_ENCODING|upper}"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
{foreach $wwwroot_array as $wwwroot}
<url><loc>{$wwwroot}</loc><lastmod>{$date}</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>
{/foreach}
{foreach $product_category_url_array as $product_category_url}
<url><loc>{$product_category_url}</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>
{/foreach}
{foreach $content_category_url_array as $content_category_url}
<url><loc>{$content_category_url}</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
{/foreach}
{foreach $products as $p}
<url><loc>{$p}</loc><lastmod>{$date}</lastmod><changefreq>weekly</changefreq><priority>0.6</priority></url>
{/foreach}
{foreach $content_url_array as $p}
<url><loc>{$p}</loc><lastmod>{$date}</lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
{/foreach}
{foreach $legal_url_array as $legal_url}
<url><loc>{$legal_url}</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.3</priority></url>
{/foreach}
{foreach $account_register_url_array as $account_register_url}
<url><loc>{$account_register_url}</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.2</priority></url>
{/foreach}
{foreach $account_url_array as $account_url}
<url><loc>{$account_url}</loc><lastmod>{$date}</lastmod><changefreq>monthly</changefreq><priority>0.1</priority></url>
{/foreach}
</urlset>