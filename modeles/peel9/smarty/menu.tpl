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
// $Id: menu.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
			</div>
		</div>
		{$MODULES_HEADER_TOP_MENU}
	</div>
	<div class="navbar-inner main_menu_wide">
		<div class="container">
{$affiche_contenu_html_menu}
			<div class="navbar-collapse collapse">
				<nav class="main_menu">
					<ul id="menu1" class="nav navbar-nav">
						{foreach $menu as $item}
							{if $item.label=='divider'}
						<li role="presentation" class="divider"></li>
							{else}
						<li class="menu_main_item menu_{$item.name}{if $item.selected} active{/if}{if !empty($item.submenu) || !empty($item.submenu_global)} dropdown{/if}{if $item.class} {$item.class}{/if}">
								{if $item.href}
							<a id="{$item.id|str_form_value}" {if !empty($item.submenu) || !empty($item.submenu_global)}href="{$item.href|htmlspecialchars}" class="dropdown-toggle" data-toggle="dropdown" role="button"{else}href="{$item.href|htmlspecialchars}"{/if}>{if $item.name == 'home'}<span class="glyphicon glyphicon-home"></span>{else}{$item.label}{/if}{if !empty($item.submenu) || !empty($item.submenu_global)} <span class="caret"></span>{/if}</a>
								{else}
							<span>{$item.label}</span>
								{/if}
								{if !empty($item.submenu)}
							<ul class="sousMenu dropdown-menu" role="menu" aria-labelledby="{$item.id|str_form_value}">
									{foreach $item.submenu as $sitem}
										{if $sitem.label=='divider'}
								<li role="presentation" class="divider"></li>
										{else}
								<li{if $sitem.selected} class="active"{/if}>
											{if $sitem.href}
									<a href="{$sitem.href|htmlspecialchars}">{$sitem.label}</a>
											{else}
									<span>{$sitem.label}</span>
											{/if}
								</li>
										{/if}
									{/foreach}
							</ul>
								{/if}
							{$item.submenu_global}
						</li>
							{/if}
						{/foreach}
					</ul>
				</nav>