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
// $Id: bas.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
							</div>
							{if !empty($MODULES_BOTTOM_MIDDLE)}
							<div class="bottom_middle row">
								{$MODULES_BOTTOM_MIDDLE}
							</div>
							{/if}
							<div class="middle_column_footer">&nbsp;</div>
						</div>
						{if !empty($MODULES_RIGHT)}
						<!-- Début right_column -->
						<div class="side_column right_column col-sm-3 col-lg-2">
							{$MODULES_RIGHT}
						</div>
						<!-- Fin right_column -->
						{/if}
					</div>
				</div>
				<!-- Fin middle_column -->
				{if !empty($MODULES_BELOW_MIDDLE)}
				<!-- Début below_middle -->
				<div class="below_middle container">
					<div class="row">
						{$MODULES_BELOW_MIDDLE}
					</div>
				</div>
				<!-- Fin below_middle -->
				{/if}
			</div>
			<!-- Fin main_content -->	
			<div class="push"></div>
		</div>
		<!-- Fin Total -->
		{if !empty($scroll_to_top)}<div class="scroll_to_top"><a href="#"><span class="glyphicon glyphicon-circle-arrow-up"></span></a></div>{/if}
		{if !empty($call_back_form)} {$call_back_form} {/if}

		<!-- Début Footer -->
		<div id="footer" class="clearfix">
			{if !empty($CONTENT_HOME_BOTTOM) || !empty($CONTENT_FOOTER) || !empty($footer_column)}
			<div class="container">
				<div class="affiche_contenu_html_footer">
					{if isset($CONTENT_HOME_BOTTOM)}
					{$CONTENT_HOME_BOTTOM}
					{/if}
					{$CONTENT_FOOTER}
				</div>
			</div>
			{/if}
			<footer class="footer">
				<div class="container">
					{* FOOTER_FULL_CUSTOM_HTML est une zone HTML personnalisée dans laquelle à partir de l'administration on peut insérer des tags qui appellent des fonctions d'affichage. *}
					<div class="row">
					{if empty($display_footer_full_custom_html)}
							{$MODULES_FOOTER}
						{if !empty($footer_column)}
							<div class="footer_column">{$footer_column}</div>
						{/if}
							{$FOOTER}
							<div class="clearfix visible-sm"></div>
							{if isset($rss)}
								{$rss}
							{/if}
					{else}
						{$FOOTER_FULL_CUSTOM_HTML}
					{/if}
					</div>
					<div class="clearfix"></div>
					{if !empty($flags_links_array) || !empty($flags)}<div id="flags_xs" class="pull-right visible-xs">{if !empty($flags_links_array)}{'&nbsp;'|implode:$flags_links_array}{/if}{$flags}</div>{/if}
					{if !empty($module_devise)}<div id="currencies_xs" class="pull-right visible-xs">{$module_devise}</div>{/if}
					<div class="clearfix"></div>
					{if !empty($footer_link)}
					<div class="footer_link">{$footer_link}</div>
					<div class="clearfix"></div>
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
		{if isset($peel_debug)}
			<div class="clearfix"></div>
			{foreach $peel_debug as $key => $item_arr}
			<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - Start{$STR_BEFORE_TWO_POINTS}{{math equation="x*y" x=$item_arr.start y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if}{if isset($item_arr.template)}{$item_arr.template}{/if}{if isset($item_arr.text)}{$item_arr.text}{/if}</span><br />
			{/foreach}
		{/if}
	</body>
</html>