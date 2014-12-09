var delay = 3000; //set delay between message change (in miliseconds)
var maxsteps=10; // number of steps to take to change from start opacity to end opacity
var stepdelay=25; // time in miliseconds of a single step
var startopacity=0; // start opacity
var endopacity=1; // end opacity
var begintag='<div>';
var closetag='</div>';
var fadelinks=0;  //should links inside scroller content also fade like text? 0 for no, 1 for yes.
var fadecounter=new Array();

/*Rafael Raposo edited function*/
//function to change content
// Modified by Advisto / PEEL
function changecontent(scrollerdiv_id){
	if (index[scrollerdiv_id]>=scrollercontent[scrollerdiv_id].length){
		index[scrollerdiv_id]=0;
	}
	if (document.getElementById){
		document.getElementById(scrollerdiv_id).style.opacity=startopacity;
		document.getElementById(scrollerdiv_id).innerHTML=begintag+scrollercontent[scrollerdiv_id][index[scrollerdiv_id]]+closetag;
		if (fadelinks){
			linkcolorchange(1, scrollerdiv_id);
		}
		colorfade(1, scrollerdiv_id);
	} else if (document.all && !document.getElementById) {
		// IE 4
		document.all.fscroller.innerHTML=begintag+scrollercontent[scrollerdiv_id][index[scrollerdiv_id]]+closetag;
	}
	index[scrollerdiv_id]++;
}

// colorfade() partially by Marcio Galli for Netscape Communications.
/*Rafael Raposo edited function*/
// Modified by Dynamicdrive.com and by Advisto / PEEL

function linkcolorchange(step, scrollerdiv_id){
	var obj=document.getElementById(scrollerdiv_id).getElementsByTagName("A");
	if (obj.length>0){
		for (i=0;i<obj.length;i++)
			obj[i].style.opacity=getstepopacity(step);
	}
}
function colorfade(step, scrollerdiv_id) {
	if(step<=maxsteps) {
		document.getElementById(scrollerdiv_id).style.opacity=getstepopacity(step);
		if (fadelinks) {
			linkcolorchange(step, scrollerdiv_id);
		}
		step++;
		fadecounter[scrollerdiv_id]=setTimeout("colorfade("+step+",'"+scrollerdiv_id+"')",stepdelay);
	} else {
		clearTimeout(fadecounter[scrollerdiv_id]);
		document.getElementById(scrollerdiv_id).style.opacity=endopacity;
		setTimeout("changecontent('"+scrollerdiv_id+"')", delay);
	}
}

// Modified by Advisto / PEEL
function getstepopacity(step) {
	var newopacity=0;
	newopacity = (endopacity/maxsteps)*step;
	return newopacity.toFixed(1);
}