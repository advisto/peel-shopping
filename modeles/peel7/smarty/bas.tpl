{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}								</td>
							</tr>
							<tr>
								<td class="center">
									{$MODULES_BOTTOM_MIDDLE}
								</td>
							</tr>
						</table>
					</div>
					<div class="middle_column_footer">&nbsp;</div>
				</div>
				<!-- Fin middle_column -->
				{if $page_columns_count == 3}
				<!-- Début right_column -->
				<div class="right_column">
				{$MODULES_RIGHT}
				</div>
				<!-- Fin right_column -->
				{/if}
			</div>
			<!-- Fin main_content -->
			<!-- Début Footer -->
			<footer id="footer">
				<div class="affiche_contenu_html_footer">
					{if isset($CONTENT_HOME_BOTTOM)}
					{$CONTENT_HOME_BOTTOM}
					{/if}
					{$CONTENT_FOOTER}
				</div>
				{$MODULES_FOOTER}
				{$FOOTER}
			</footer>
			<!-- Fin Footer -->
		</div>
		<!-- Fin Total -->
		{if isset($add_cart_alert)}
			<script><!--//--><![CDATA[//><!--
			alert('{$add_cart_alert|filtre_javascript:true:true:false}');
			//--><!]]></script>
		{/if}
		{$tag_analytics}
		{if isset($butterflive_tracker)}
			{$butterflive_tracker}
		{/if}
		{if isset($peel_debug)}
			{foreach $peel_debug as $key => $item_arr}
				<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if} {if isset($item_arr.template)}{$item_arr.template}{/if}</span><br />
			{/foreach}
		{/if}
	</body>
</html>