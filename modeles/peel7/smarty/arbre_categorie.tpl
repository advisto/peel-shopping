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
// $Id: arbre_categorie.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
*}{if empty($hidden)}<span typeof="Breadcrumb"><a property="url" href="{$href|escape:'html'}" title="{$name|escape:'html'}"><span property="title">{$name}</span></a></span>{else}
<span typeof="Breadcrumb"><span property="url" content="{$href|escape:'html'}"><span property="title" content="{$name|escape:'html'}"></span></span></span>
{/if}