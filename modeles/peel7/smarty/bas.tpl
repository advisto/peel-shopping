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
// $Id: bas.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}
							<div class="row bottom_middle">
								<div class="col-md-12">
									{$MODULES_BOTTOM_MIDDLE}
								</div>
							</div>
						</div>
						<div class="middle_column_footer">&nbsp;</div>
					</div>
				</div>
				<!-- Fin middle_column -->
				{if $page_columns_count == 3}
				<!-- Début right_column -->
				<div class="right_column container">
					<div class="row">
						{$MODULES_RIGHT}
					</div>
				</div>
				<!-- Fin right_column -->
				{/if}
			</div>
			<!-- Fin main_content -->	
			<div class="push"></div>
		</div>
		<!-- Fin Total -->
		<!-- Début Footer -->
		<div id="footer" class="clearfix">
			{if !empty($CONTENT_HOME_BOTTOM) || !empty($CONTENT_FOOTER) || !empty($footer_column)}
			<div class="container">
				<div class="affiche_contenu_html_footer">
					{if isset($CONTENT_HOME_BOTTOM)}
					{$CONTENT_HOME_BOTTOM}
					{/if}
					{$CONTENT_FOOTER}
				{if !empty($footer_column)}
					<div class="footer_column">{$footer_column}</div>
				{/if}
				</div>
			</div>
			{/if}
			<footer class="footer">
				<div class="container">
					{$MODULES_FOOTER}
					<div class="col-sm-{$block_columns_width_sm} col-md-{$block_columns_width_md} footer_col">
						{$FOOTER}
					</div>
					<div class="clearfix visible-sm"></div>
					{if isset($rss)}
						{$rss}
					{/if}
					<div class="clearfix"></div>
					{if !empty($flags_links_array) || !empty($flags)}<div id="flags_xs" class="pull-right visible-xs">{if !empty($flags_links_array)}{'&nbsp;'|implode:$flags_links_array}{/if}{$flags}</div>{/if}
					{if !empty($module_devise)}<div id="currencies_xs" class="pull-right visible-xs">{$module_devise}</div>{/if}
					<div class="clearfix"></div>
				{if !empty($footer_link)}
					<div class="footer_link">{$footer_link}</div>
				{/if}
				</div>
			{if !empty($footer_bottom)}
				<div class="footer_bottom">{$footer_bottom}</div>
			{/if}
			</footer>
		</div>
		<!-- Fin Footer -->
		{$js_output}
		{$tag_analytics}
		{if isset($end_javascript)}
			{$end_javascript}
		{/if}
		{if isset($butterflive_tracker)}
			{$butterflive_tracker}
		{/if}
		{if isset($peel_debug)}
			{foreach $peel_debug as $key => $item_arr}
				<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - Start{$STR_BEFORE_TWO_POINTS}{{math equation="x*y" x=$item_arr.start y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if} {if isset($item_arr.template)}{$item_arr.template}{/if}</span><br />
			{/foreach}
		{/if}
	</body>
</html>