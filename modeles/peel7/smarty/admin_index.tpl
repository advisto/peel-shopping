{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_index.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
*}{if isset($KeyyoCalls)}{$KeyyoCalls}{/if}
<div style="margin-left:-15px; margin-right:-15px">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-6 col-sm-6">{$orders}</div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$sales}</div>
			<div class="clearfix visible-md visible-sm"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$products}</div>
			<div class="clearfix visible-lg"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$delivery}</div>
			<div class="clearfix visible-md visible-sm"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$users}</div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$peel}</div>
		</div>
	</div>
</div>
<br />
<p class="alert alert-danger center"><a href="{$sortie_href|escape:'html'}" class="alert-link">{$STR_ADMIN_INDEX_SECURITY_WARNING}</a></p>