function show_interstitiel(div_id, background_color, html_haut, html_mil, html_bas)
{
	if(document.getElementById)
	{
		document.getElementById(div_id).style.width = "100%";
		document.getElementById(div_id).style.height =  "100%";
		document.getElementById(div_id).style.display =  "block";
		if(document.getElementById(div_id+"_background")) {
			document.getElementById(div_id+"_background").style.width = "100%";
			document.getElementById(div_id+"_background").style.height = "100%";
			document.getElementById(div_id+"_background").style.display =  "block";
			if(background_color!=''){
				document.getElementById(div_id+"_background").style.backgroundColor=background_color;
			}
		} else if(background_color!=''){
			document.getElementById(div_id).style.backgroundColor=background_color;
		}
		document.getElementById(div_id).innerHTML = html_haut+html_mil+html_bas;
		setTimeout('close_interstitiel("'+div_id+'")',10000);
	}
}

function close_interstitiel(div_id)
{
   if(document.getElementById)
    {
		document.getElementById(div_id).style.display = 'none';
		document.getElementById(div_id).style.width = 0;
		document.getElementById(div_id).style.height =  0;
		document.getElementById(div_id).innerHTML = '';
		if(document.getElementById(div_id+"_background")) {
			document.getElementById(div_id+"_background").display = 'none';
			document.getElementById(div_id+"_background").style.width = 0;
			document.getElementById(div_id+"_background").style.height = 0;
			document.getElementById(div_id+"_background").innerHTML = '';
		}
	}
}

function set_cookie_value(variable, value)
{
	var dateExp = new Date();
	dateExp.setTime(dateExp.getTime() + 30 * 24 * 3600 * 1000);
	dateExp = dateExp.toGMTString();
	document.cookie = variable + '=' + escape(value) + '; path=/; expires=' + dateExp + ';';
}

function get_cookie_value(variable)
{
	variable += "=";
	var cook = document.cookie;
	var place = cook.indexOf(variable,0);
	if (place <= -1){
		return("0");
	} else {
		end = cook.indexOf(";",place)
		if (end <= -1) {
			return(unescape(cook.substring(place+variable.length,cook.length)));
		} else {
			return(unescape(cook.substring(place+variable.length,end)));
		}
	}
}

function getScreenInnerWidth(){
	var screen_width;
	if(document.documentElement.clientHeight){
	   screen_width = document.documentElement.clientWidth;
	} else if(window.innerWidth) {
	   screen_width = window.innerWidth-18;
	} else if(screen.height) {
	   screen_width = screen.width-18;
	} else if(document.body.clientHeight) {
	   screen_width = document.body.clientWidth;
	} else {
	   screen_width = 1000;
	}
	return screen_width;
}

function getScreenInnerHeight(){
	var screen_width;
	if(document.documentElement.clientHeight){
	   screen_height = document.documentElement.clientHeight;
	} else if(window.innerWidth) {
	   screen_height = window.innerHeight;
	} else if(screen.height) {
	   screen_height = screen.height;
	} else if(document.body.clientHeight) {
	   screen_height = document.body.clientHeight;
	} else {
	   screen_height = 800;
	}
	return screen_height;
}
