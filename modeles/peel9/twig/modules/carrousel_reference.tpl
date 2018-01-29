{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: carrousel_reference.tpl 54014 2017-06-09 09:26:20Z jlesergent $
*}
{if !empty($references)}
	{if !empty($module_best_sellers_return_result_as_link)}
	{foreach $references as $ref}
		{$ref.html}<br />
	{/foreach}
	
	{else}
	<div class="col-md-2"></div>
	<div id="carrousel_reference" class="col-md-8 carousel slide" data-ride="carousel" data-interval="10000">
	  <!-- Indicators -->
	  {*<ol class="carousel-indicators">
		{for $i=0 to floor((count($references)-1)/$nb_col_md)}
			<li data-target="#carrousel_reference" data-slide-to="{$i}" class="{if $i==0}active{/if}"></li>
		{/for}
	  </ol>*}

		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<div class="item active">
		{foreach $references as $ref}
			{if $ref.i%1!=1 && $ref.i>1}
			</div>
			<div class="item">
			{/if}
				<div class="center col-sm-{floor(12/$nb_col_sm)} col-md-{floor(12/$nb_col_md)}{if ($ref.i-1)%$nb_col_md>$nb_col_xs-1} hidden-xs{/if}{if ($ref.i-1)%$nb_col_md>$nb_col_sm-1} hidden-sm{/if}">
					{$ref.html}
				</div>
		{/foreach}
			</div>
		</div>

		{if floor((count($references)-1)/$nb_col_md)>0}
		<!-- Controls -->
		<a class="left carousel-control" href="#carrousel_reference" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
		<a class="right carousel-control" href="#carrousel_reference" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
		{/if}
	</div>
	<div class="col-md-2"></div>
	{/if}
{/if}