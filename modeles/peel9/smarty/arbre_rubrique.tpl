{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: arbre_rubrique.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}{if empty($hidden)}<span property="itemListElement" typeof="ListItem">{if !empty($arbre_rubrique_iteration)}<span property="title">{$label}</span>{else}<a property="item" typeof="WebPage" href="{$href|escape:'html'}" title="{$label|escape:'html'}"><span property="name">{$label}</span></a>{/if}<meta property="position" content="{$level|escape:'html'}" /></span>{else}
<span property="itemListElement" typeof="ListItem"><span property="item" typeof="WebPage" content="{$href|escape:'html'}"><span property="name" content="{$label|escape:'html'}"></span></span><meta property="position" content="{$level|escape:'html'}" /></span>
{/if}