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
}

function switch_product_images(new_main, new_main_zoom, vignette_id){
    var current_main = document.getElementById('mainProductImage').src;
    var current_main_zoom = document.getElementById('zoom1').href;
    document.getElementById(vignette_id).innerHTML = '<a href="javascript:switch_product_images(\''+current_main+'\', \''+current_main_zoom+'\', \''+vignette_id+'\')"><img src="'+current_main+'" name="'+vignette_id+'" width="50" /></a>';
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
 * Fonction permettant de changer les images de niveau du mot de passe
 */
function set_password_image_level(id_input_psw,img_dir){
    ( function($) {
        $(document).ready(function(){
            $(function() {
                var level = 0;
                $("#pwd_level_image").html("<img src='"+img_dir+"/psd_level_"+level+".jpg' />");

                $("#"+id_input_psw).keyup(function(){
                    level = password_level($("#"+id_input_psw).val());
                    $("#pwd_level_image").html("<img src='"+img_dir+"/psd_level_"+level+".jpg' />");
                })
            });
        });
    } ) ( jQuery )
}

function updateTextField(element, text, type) {
	var nothing = '';
	var elt = jQuery("#"+element);
	if(elt.val() == text)
		elt.val(nothing);
	else if(elt.val() == nothing && type == 'blur')
		elt.val(text);
	return false;
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