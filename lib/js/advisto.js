function Compter(Target, max, nomchamp, returnStrLen)
{
    StrLen = Target.value.length
    if (StrLen > max )
    {
        Target.value = Target.value.substring(0,max);
        CharsLeft = max;
    } else {
        CharsLeft = StrLen;
    }
	if (returnStrLen) {
		nomchamp.value = StrLen;
	} else {
		nomchamp.value = max - CharsLeft;
	}
}

function frmsubmit(func) {
    frm = document.getElementById("caddieFormArticle");
    frm.func.value = func;
    frm.submit();
	return false;
}

function switch_product_images(new_main, new_main_zoom, vignette_id){
    var current_main = document.getElementById('mainProductImage').src;
    var current_main_zoom = document.getElementById('zoom1').href;
    document.getElementById(vignette_id).innerHTML = '<a onclick="switch_product_images(\''+current_main+'\', \''+current_main_zoom+'\', \''+vignette_id+'\'); return false;"><img src="'+current_main+'" name="'+vignette_id+'" alt="" width="50" /></a>';
    document.getElementById('mainProductImage').src = new_main;
    document.getElementById('zoom1').href = new_main_zoom;
}
function switch_product_tab(tab_to_highlight_id,title_to_highlight_id){
    var current_tab=document.getElementById('current_tab_id').value;
    var current_tab_title=document.getElementById('current_tab_title').value;

    document.getElementById(current_tab).style.display='none';
    document.getElementById(current_tab).style.visibility='hidden';
    document.getElementById(current_tab_title).className='tab';

    document.getElementById(tab_to_highlight_id).style.display='block';
    document.getElementById(tab_to_highlight_id).style.visibility='visible';
    document.getElementById(title_to_highlight_id).className='current_tab';

    document.getElementById('current_tab_id').value=tab_to_highlight_id;
    document.getElementById('current_tab_title').value=title_to_highlight_id;
}

function popupSmileys (src) {
    window.open(src,'', 'top=20, left=20, width=220, height=360, resizable=yes, toolbar=no, scrollbars=yes, status=yes');
}

/*
 * Fonction qui teste la force d'un mot de passe
 * 0 = rien , 1 = fable, 2 = moyen et 3 = fort
 */
function password_level(str){
    var level = 0; // niveau de force
    var diff_param = 0; // nombres de paramètres différents
    var length = str.length; // longueur du mot de passe

    if(length > 0){
        var up_char = new RegExp("[A-Z]"); // lettres majuscules
        var low_char = new RegExp("[a-z]"); // lettres majuscules
        var sp_char = new RegExp("[^(A-Za-z0-9)]"); // caractères spéciaux
        var num_car = new RegExp("[0-9]"); // chiffres

        if(up_char.test(str)) diff_param++; // si str contient les majuscules
        if(low_char.test(str)) diff_param++; // si str contient les minuscule
        if(sp_char.test(str)) diff_param++; // si str contient les car sepciaux
        if(num_car.test(str)) diff_param++; // si str contient les chiffres

        if((length > 6) && (diff_param == 4)){ // 4 paramètres différents et plus de 6 car
            level = 3;
        }else if(length > 5){
            if(diff_param == 3){  //3 paramètres différents
                level = 2;
            }else{
                level = 1;
            }
        }else{
            level = 0;
        }
    }
    return level;
}

/*
 * Fonctions permettant de changer les images de niveau du mot de passe
 */
 
function set_password_image(select, level, img_dir, bootstrap){
    (function($) {
		if(bootstrap) {
			percent = Math.round(level*100/3);
			if(level==0) { style='danger'; }
			else if(level==1) { style='warning'; }
			else if(level==2) { style='info'; }
			else if(level==3) { style='success'; }
			$(select).html('<div class="progress progress-striped active"><div class="progress-bar progress-bar-'+style+'" role="progressbar" aria-valuenow="'+percent+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percent+'%"><span class="sr-only">'+percent+'% Complete (success)</span></div></div>');
		} else {
			$(select).html("<img src='"+img_dir+"/psd_level_"+level+".jpg' />");
		}
   })(jQuery)
}
function set_password_image_level(id_input_psw, img_dir, id_image, bootstrap){
	(function($) {
		set_password_image("#"+id_image, 0, img_dir, bootstrap);
		$("#"+id_input_psw).keyup(function(){
			set_password_image("#"+id_image, password_level($("#"+id_input_psw).val()), img_dir, bootstrap)
		})
    }) (jQuery)
}

function origin_change(value, origin_other_ids) {
	for(var i = 0; i < origin_other_ids.length; i++) {
		if(origin_other_ids[i] == value) {
			document.getElementById("origin_other").style.display="inline";
			return true;
		}
	}
	document.getElementById("origin_other").style.display="none";
}

function advisto_confirm(message, url) {
	bootbox.confirm(message, function(result) {
			if(result) { window.open(url,'_self'); }
		});
	return false;
}
function compose_member_list() {
	$show_div_id = $( "#friend_select" ).val();
	$( "#friend"+$show_div_id ).toggle();
	$("#input_friend"+$show_div_id ).prop("disabled",!$("#input_friend"+$show_div_id).prop("disabled"))
	return false;
}

function advisto_form_confirm(message, button) {
	bootbox.confirm(message, function(result) {
			if(result) { button.type='text'; button.form.submit(); }
		});
	return false;
}

function getDeviceWidth() {
    if (typeof (window.innerWidth) == 'number') {
        //Non-IE
        return window.innerWidth;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        return document.documentElement.clientWidth;
    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        //IE 4 compatible
        return document.body.clientWidth;
    }
    return $(window).width();
}

/* Cache global pour autocomplete */
var autocomplete_cache = {};
function bind_search_autocomplete(search_item_id, search_rpc_url, submit_auto) {
	if(typeof(autocomplete_cache[search_rpc_url]) == 'undefined') {
		autocomplete_cache[search_rpc_url] = {};
	}
	$("#"+search_item_id).autocomplete({
		appendTo: "#"+search_item_id+"_wrapper",
		html: true, 
		select: function(event, ui) { 
			$("#"+search_item_id).val(ui.item.value);
			if(submit_auto) {
				$("#"+search_item_id+"_match").val(3);
				$("#"+search_item_id+"_category").val('');
				$("#"+search_item_id).closest("form").submit();
			}
		},
		source: function( request, response ) {
			var term = request.term;
			var search_category = $("#"+search_item_id+"_category").val();
			if (search_category+"_"+term in autocomplete_cache[search_rpc_url]) {
				response($.map(autocomplete_cache[search_rpc_url][search_category+"_"+term], function(item){
						return {
							label: item.label,
							value: item.nom
						}
				}));
				return;
			}
			$.ajax({
				url: search_rpc_url,
				dataType : 'json',
				type : 'POST',
				data : {
					return_json_array_with_raw_information : '1',
					maxRows: 12,
					search_category: search_category,
					search: request.term
				},
				success: function( data ) {
					autocomplete_cache[search_rpc_url][search_category+"_"+term] = data;
					response($.map(data, function(item){
							return {
								label: item.label,
								value: item.nom
							}
					}));
				}
			});
		}
	});
	$(window).resize(function() {
		if(!!$("#"+search_item_id).parent().children('.ui-autocomplete.ui-widget:visible').length) {
			// Si autocomplete visible, on le repositionne
			$("#"+search_item_id).autocomplete("search");
		}
	});
}

(function($) {
	function reposition_dropdown(elt) {
 		var add_right = 0;
		var this_dropdown_menu = elt.children(".dropdown-menu");
		if(this_dropdown_menu.length) {
			// On calcule le bord gauche à partir du bouton parent car on n'y a pas accès lorsque la popup est cachée
			var this_dropdown_menu_max_right = elt.offset().left+parseInt(this_dropdown_menu.css('width'));
			var this_dropdown_menu_min_left = elt.offset().left+parseInt(elt.css('width'))-parseInt(this_dropdown_menu.css('width'));
			add_right = 20; // Marge barre de scroll
			this_dropdown_menu.find('.dropdown-submenu ul.dropdown-menu').each(function() {
				// 250 => Compatibilité si navigateur ne calcule pas la largeur du sous-menu
				add_right = Math.max(add_right, 250, parseInt($(this).css('width')));
			});
			if(this_dropdown_menu_max_right+add_right<getDeviceWidth()) {
				this_dropdown_menu.css('left', "0px");
				this_dropdown_menu.css('right', "auto");
			} else {
				this_dropdown_menu.css('right', Math.min(this_dropdown_menu_min_left, Math.max(parseInt(elt.css('width'))-10,0), Math.max(elt.offset().left+parseInt(elt.css('width'))+add_right-getDeviceWidth(), 0))+"px");
				this_dropdown_menu.css('left', "auto");
			}
		}
	}
	function resize_all() {
		$('.dropdown:not(.dropdown .dropdown)').each(function() {
			// Gérer les positions de dropdowns pour ne pas sortir de l'écran
			reposition_dropdown($(this));
		});
		if(!!$('#total > div.push').length) {
			// Repositionnement du sticky footer
			var footerHeight = $("#footer").height();
			$("#total").css("margin-bottom", (-footerHeight)+'px');
			$("#total > div.push").css("min-height", footerHeight+'px');
		}
		// Fermeture du datepicker pour repositionnement auto
		$(".datepicker").datepicker('hide').blur();
		if(typeof load_timepicker != "undefined") {
			$(".datetimepicker").datetimepicker('hide').blur();
		}
		if(getDeviceWidth()>=768) {
			// Fermeture du menu
			if($(".navbar-collapse").hasClass('in')) {
				$(".navbar-collapse").removeClass('in');
				$('ul.nav > li.dropdown > ul.dropdown-menu').hide();
				$('ul.nav > li.dropdown').removeClass('open');
			}
		}
		if($(window).height()) {
			// On définit la taille maximale de la carte à la hauteur visible de la page
			$(".societe_maps iframe").css("max-height", Math.max($(window).height(), 150)+'px');
		}
		advisto_scroll_banner();
	}
	// Scrolling inversé de l'image de fond des mégabannières
	function advisto_scroll_banner() {
		var windowHeight = $(window).height();
		$('img.scrolled-banner-image').each(function() {
			var divYpos = Math.round($(this).offset().top)-windowHeight;
			var divHeight = $(this).height();
			myY = Math.round(Math.max(0,Math.min(($(document).scrollTop()-Math.max(0,divYpos))/(divHeight+windowHeight-Math.max(0,-divYpos)), 1))*(divHeight-$(this).parent().height()));
			if(!!('ontouchstart' in window)) {
				// iOS et peut-être d'autres ne déclenchent l'événement scroll que ponctuellement => nécessite de faire animate pour impression fluide (mais donne impression de retard forcément)
				$(this).stop(true, false).animate({marginTop: -myY},{duration: 300, queue: false, easing: 'linear'});
			} else {
				var cssObj = { 'margin-top': -myY + 'px' }
				$(this).css(cssObj);
			}
		});
	}
	/* jQuery UI Autocomplete : autoriser du HTML */
	var proto = $.ui.autocomplete.prototype, initSource = proto._initSource;
	$.extend( proto, {
		_renderItem: function( ul, item) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( $( "<a></a>" )[ this.options.html ? "html" : "text" ]( item.label ) )
				.appendTo( ul );
		}
	});
	$(document).ready(function() {
		if(!!('ontouchstart' in window)) {
			$('#total').hover(function() {
				// Si touch actif : on veut qu'un clic dans le vide retire le hover d'autres éléments
				// => le simple fait de créer le hover sur un div englobant le site (body ne marche pas sur iOS) suffit pour déclencher le blur sur les autres
			});
		}
		// Eviter de devoir double cliquer si hover existe sur un lien
		var isScrolling = false;
		$('.image_zoom > a, a[rel="tooltip"], a[data-toggle="tooltip"], a.tooltip_link').on('touchstart', function(){isScrolling = false;}).on('touchmove', function(e){isScrolling = true;}).on('click touchend', function(e) {
			if(!isScrolling && $(this).attr('href')!='#') {
				window.location = $(this).attr('href');
			}
		});
		$('input[data-toggle="tooltip"], input.tooltip_link, button.tooltip_link').on('touchstart', function(){isScrolling = false;}).on('touchmove', function(e){isScrolling = true;}).on('click touchend', function(e) {
			if(!isScrolling) {
				$(this).closest('form').submit();
			}
		});
		// Gestion des effets Bootstrap
		if(typeof $().alert == 'function') {
			// Gère les effets Bootbox sur boites alert qui s'ouvrent ou se ferment
			$(".alert").alert();
			// Gestion slide tactile (ou même avec souris en laissant cliqué) sur carousel (avec dépendence jquery-mobile mis dans bootstrap.js)
			$(".carousel").swiperight(function() {  
				$(this).carousel("prev");  
			});  
			$(".carousel").swipeleft(function() {  
				$(this).carousel("next");  
			});  
			// Activation de Tooltip
			$("body").tooltip({
				selector: 'a[rel="tooltip"], [data-toggle="tooltip"], .tooltip_link'
			});
		}
		$('ul.nav > li.dropdown').hover(function() {
			if(!!$(this).find('.dropdown-submenu').length) {
				reposition_dropdown($(this));
			}
			// Add Hover effect to Bootstrap menus
			if(getDeviceWidth()>=768) {
				menu_delay = 130;
				$(this).find('ul.dropdown-menu:first').stop(true, true).delay(10).slideDown(menu_delay);
				// Il faut passer l'id à settimeout : astuce sinon problème si on passe this et qu'on navigue plus vite que le timeout
				if(!$(this).attr('id')) {
					$(this).attr('id', 'dropdown_'+Math.round(Math.random()*10000000));
				}
				var $this_hover = $(this).attr('id');
				setTimeout(function() {jQuery('#'+$this_hover).addClass('open');}, menu_delay+10);
			} else {
				// Pas d'animation sinon menu pas gérable
				$(this).addClass('open');
				$(this).find('ul.dropdown-menu:first').stop(true, true).show();
			}
		}, function() {
			// Add Blur effect to Bootstrap menus (loosing hover => hide)
			if(getDeviceWidth()>=768) {
				menu_delay = 130;
				$(this).find('ul.dropdown-menu:first').stop(true, true).delay(10).slideUp(menu_delay);
				var $this_blur = jQuery(this);
				setTimeout(function() {$this_blur.removeClass('open');}, menu_delay+10);
			} else	if(!!('ontouchstart' in window)) {
				// On ne referme que si en mode tactile - sinon très désagréable le hover avec souris (et on a le click => va vers lien du menu si URL sur section principale, on ne veut pas imposer double click avec souris)
				$(this).find('ul.dropdown-menu:first').stop(true, true).hide();
				$(this).removeClass('open');
			}
		});
		$("navbar-toggle").click(function() {
			if($(".navbar-collapse").hasClass('in')) {
				$('ul.nav > li.dropdown > ul.dropdown-menu').hide();
				$('ul.nav > li.dropdown').removeClass('open');
			}
		});
		$('ul.nav > li.dropdown a').click(function(e) {
			if($(this).attr('href')!='#') {
				// Allow link in main menu
				// Si écran tactile : on ne veut pas que le clic ferme le menu, mais qu'il mène vers le lien
				// (Au premier appui, on ouvre le menu avec le hover défini ci-dessus - le second appui fait le clic)
				window.location = $(this).attr('href');
				e.preventDefault(); 
			}
			e.stopPropagation();
		});
						
		$('ul.nav > li.dropdown > ul.dropdown-menu > li.dropdown-submenu > a[href="#"]').click(function(e) {
			// On ne veut pas qu'un clic sur un élément qui donne sur un sous-menu ne fasse quoique ce soit, si href=# (sinon, on veut que le lien marche)
			e.stopPropagation();
		});
		/* Sur le bouton de login, la sélection de proposition du navigateur dans les inputs fait perdre le hover. Donc on ne peut pas afficher la popup de login automatiquement.
		On peut néanmoins activer cet affichage ci-dessous en sortant du commentaire, mais dans ce cas pour fermer la popup il faut cliquer ailleurs (utilisation de blur) */
		/* $('#header_login .dropdown').hover(function() {
			if(getDeviceWidth()>=768) {
			   $(this).addClass('open');
			}
		}).blur(function() {
			$(this).removeClass('open');
		});
		*/
		resize_all();
		$(window).resize(function() {
            resize_all();
		});

		// Gère les messages de confirmation : data-confirm => déclenche boite de dialogue
		$('a[data-confirm]').click(function(ev) {
			var href = $(this).attr('href');
			return advisto_confirm($(this).attr('data-confirm'), $(this).attr('href'));
		});
		// Souvenir de clôture avec cookie
		$(".remember-close").each(function(){ 
			if($.cookie($(this).attr("id")) == "closed") {
				$(this).parent().hide();
			}
		});
		$(".remember-close").click(function(e){
			$.cookie($(this).attr("id"), "closed", { path: "/" });
		});
		// Fermeture cloud-zoom sur écran tactile
		$("#wrap").click(function(e) {  
			$("#cloud-zoom-big").click(function(e) {
				$(this).hide(); 
				e.preventDefault(); 
				e.stopPropagation();
			});
		});
		// Fermeture jqzoom sur écran tactile
		$(".image_grande").hover(function(e) {  
			$(".zoomWindow").click(function(e) {
				$(this).hide(); 
				e.preventDefault(); 
				e.stopPropagation();
			});
		});
		// Animation des liens internes aux pages
		$('#main_content a[href^="#"]:not([href="#"]):not([data-slide]):not(.tabbable a)').on('click',function (e) {
			e.preventDefault();
			var target = this.hash,
			$target = $(target);
			$('html, body').stop().animate({
				'scrollTop': $target.offset().top
			}, 1500, 'swing', function () {
				window.location.hash = target;
				$('html, body').stop().animate({'scrollTop': $target.offset().top-15}, 300);
			});
		});
		$(window).scroll(function() { advisto_scroll_banner() });
	});
})(jQuery);