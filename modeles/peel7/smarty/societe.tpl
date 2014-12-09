{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: societe.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}<br />
{if !empty($societe)}<br /><b>{$societe|html_entity_decode_if_needed}</b>&nbsp;{/if}
{if !empty($adresse)}<br />{$adresse|html_entity_decode_if_needed}&nbsp;{/if}
{if !empty($code_postal)}<br />{$code_postal}&nbsp;{/if}
{if !empty($ville)}<br />{$ville|html_entity_decode_if_needed}&nbsp;{/if}
{if !empty($pays)}<br />{$pays|html_entity_decode_if_needed}&nbsp;{/if}
{if !empty($tel)}<br />{$tel_label}: {$tel}{/if}
{if !empty($fax)}<br />{$fax_label}: {$fax}{/if}
{if !empty($siren)}<br />{$siren_label}: {$siren}{/if}
{if !empty($tvaintra)}<br />{$tvaintra_label}: {$tvaintra}{/if}
{if !empty($cnil)}<br />{$cnil_label}: {$cnil}{/if}
<br />