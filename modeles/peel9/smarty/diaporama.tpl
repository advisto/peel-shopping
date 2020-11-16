{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: diaporama.tpl 64866 2020-10-30 14:12:06Z sdelaporte $
*}
<div class="col-md-12">
	<div class="row">
	{foreach $diaporama as $diapo}
		<div class="col-md-{floor(12/$nb_colonnes_md)} col-sm-{floor(12/$nb_colonnes_sm)}">
			<div class="diaporama_image_container">
				<a id="zoom1" typeof="ImageObject" class="lightbox" href="{$diapo.image}" onclick="return false;">
					<img property="image" id="mainProductImage" class="zoom" src="{$diapo.thumbs}" alt="">
				</a>
			</div>
		</div>
			{if $diapo.is_row_md}
				<div class="clearfix visible-md visible-lg"></div>
			{/if}
			{if $diapo.is_row_sm}
				<div class="clearfix visible-sm"></div>
			{/if}
	{/foreach}
	</div>
</div>