var ie4=document.all&&!document.getElementById;
var DOM2=document.getElementById;
var faderdelay=0;
var index=0;
var index_max=0;
/*Rafael Raposo edited function*/
//function to change content
// Modified by Advisto / PEEL
function changecontent(scrollerdiv_id){
  if (index[scrollerdiv_id]>=index_max[scrollerdiv_id]){
	index[scrollerdiv_id]=0;
  }
  if (DOM2){
    document.getElementById(scrollerdiv_id).style.color="rgb("+startcolor[0]+", "+startcolor[1]+", "+startcolor[2]+")";
    document.getElementById(scrollerdiv_id).innerHTML=begintag+scrollercontent[scrollerdiv_id][index[scrollerdiv_id]]+closetag;
    if (fadelinks){
      linkcolorchange(1, scrollerdiv_id);
    }
    colorfade(1, scrollerdiv_id);
  } else if (ie4) {
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
      obj[i].style.color=getstepcolor(step);
  }
}
function colorfade(step, scrollerdiv_id) {
  if(step<=maxsteps) {
    document.getElementById(scrollerdiv_id).style.color=getstepcolor(step);
    if (fadelinks)
      linkcolorchange(step, scrollerdiv_id);
    step++;
    fadecounter[scrollerdiv_id]=setTimeout("colorfade("+step+",'"+scrollerdiv_id+"')",stepdelay);
  }else{
    clearTimeout(fadecounter[scrollerdiv_id]);
    document.getElementById(scrollerdiv_id).style.color="rgb("+endcolor[0]+", "+endcolor[1]+", "+endcolor[2]+")";
    setTimeout("changecontent('"+scrollerdiv_id+"')", delay);

  }
}

/*Rafael Raposo's new function*/
// Modified by Advisto / PEEL
function getstepcolor(step) {
  var diff;
  var newcolor=new Array(3);
  for(var i=0;i<3;i++) {
    diff = (startcolor[i]-endcolor[i]);
    if(diff > 0) {
      newcolor[i] = startcolor[i]-(Math.round((diff/maxsteps))*step);
    } else {
      newcolor[i] = startcolor[i]+(Math.round((Math.abs(diff)/maxsteps))*step);
    }
  }
  return ("rgb(" + newcolor[0] + ", " + newcolor[1] + ", " + newcolor[2] + ")");
}