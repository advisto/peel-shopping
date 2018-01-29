{# Twig
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
// $Id: sellermania.tpl 47592 2015-10-30 16:40:22Z sdelaporte $
#}<?xml version="1.0" encoding="{{ page_encoding }}"?>
<SellermaniaWs>
	{% for it in items %}
		<UpdateInventory>
			<Sku>{{ it.reference|strip_tags }}</Sku>
			<Title>![CDATA[{{ it.title|strip_tags }}]]</Title>
			<ItemNote>![CDATA[{{ it.descriptif||str_shorten(1000,'','...') }}]]</ItemNote>
			<Description>![CDATA[{{ it.description|str_shorten(1000,'','...') }}]]</Description>
			{% if it.promotion > 0 %}<BuyItNowPrice>{{ it.promotion }}</BuyItNowPrice>{% endif %}
		</UpdateInventory>
	{% endfor %}
</SellermaniaWs>