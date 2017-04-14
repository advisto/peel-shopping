{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: sellermania.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
*}<?xml version="1.0" encoding="{$page_encoding}"?>
<SellermaniaWs>
	{foreach $items as $it}
		<UpdateInventory>
			<Sku>{$it.reference|strip_tags}</Sku>
			<Title>![CDATA[{$it.title|strip_tags}]]</Title>
			<ItemNote>![CDATA[{$it.descriptif|str_shorten:1000:'':'...'}]]</ItemNote>
			<Description>![CDATA[{$it.description|str_shorten:1000:'':'...'}]]</Description>
			{if $it.promotion > 0}<BuyItNowPrice>{$it.promotion}</BuyItNowPrice>{/if}
		</UpdateInventory>
	{/foreach}
</SellermaniaWs>