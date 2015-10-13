/***********************************************
* Pausing up-down scroller- Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function pausescroller(content, divId, delay){
	this.content=content; //message array content
	this.tickerid=divId; //ID of ticker div to display information
	this.delay=delay; //Delay between msg change, in miliseconds.
	this.mouseoverBol=0; //Boolean to indicate whether mouse is currently over scroller (and pause it if it is)
	this.hiddendivpointer=1; //index of message array for hidden div
	if(document.getElementById(divId)) {
		document.getElementById(divId).innerHTML = '<div class="innerDiv" style="position: absolute; width: 96%" id="'+divId+'1">'+content[0]+'</div><div class="innerDiv" style="position: absolute; width: 96%; visibility: hidden" id="'+divId+'2">'+content[1]+'</div>';
		var scrollerinstance=this;
		scrollerinstance.initialize();
	}
}

// initialize()- Initialize scroller method.
// -Get div objects, set initial positions, start up down animation

pausescroller.prototype.initialize=function(){
	this.tickerdiv=document.getElementById(this.tickerid);
	this.visiblediv=document.getElementById(this.tickerid+"1");
	this.hiddendiv=document.getElementById(this.tickerid+"2");
	this.visibledivtop=parseInt(pausescroller.getCSSpadding(this.tickerdiv));
	//set width of inner DIVs to outer DIV's width minus padding (padding assumed to be top padding x 2)
	//this.visiblediv.style.width=this.hiddendiv.style.width=this.tickerdiv.offsetWidth-(this.visibledivtop*2)+"px";
	this.getinline(this.visiblediv, this.hiddendiv);
	this.hiddendiv.style.visibility="visible";
	var scrollerinstance=this;
	document.getElementById(this.tickerid).onmouseover=function(){scrollerinstance.mouseoverBol=1};
	document.getElementById(this.tickerid).onmouseout=function(){scrollerinstance.mouseoverBol=0};
	if (window.attachEvent) {
		//Clean up loose references in IE
		window.attachEvent("onunload", function(){scrollerinstance.tickerdiv.onmouseover=scrollerinstance.tickerdiv.onmouseout=null});
	}
	setTimeout(function(){scrollerinstance.animateup()}, this.delay);
}

// animateup()- Move the two inner divs of the scroller up and in sync

pausescroller.prototype.animateup=function(){
	var scrollerinstance=this;
	if (parseInt(this.hiddendiv.style.top)>(this.visibledivtop+8)){
		this.visiblediv.style.top=parseInt(this.visiblediv.style.top)-8+"px";
		this.hiddendiv.style.top=parseInt(this.hiddendiv.style.top)-8+"px";
		setTimeout(function(){scrollerinstance.animateup()}, 50);
	}else{
		this.getinline(this.hiddendiv, this.visiblediv);
		this.swapdivs();
		setTimeout(function(){scrollerinstance.setmessage()}, this.delay);
	}
}

// swapdivs()- Swap between which is the visible and which is the hidden div

pausescroller.prototype.swapdivs=function(){
	var tempcontainer=this.visiblediv;
	this.visiblediv=this.hiddendiv;
	this.hiddendiv=tempcontainer;
}

pausescroller.prototype.getinline=function(div1, div2){
	div1.style.top=this.visibledivtop+"px";
	div2.style.top=Math.max(div1.parentNode.offsetHeight, div1.offsetHeight)+"px";
}

// setmessage()- Populate the hidden div with the next message before it's visible

pausescroller.prototype.setmessage=function(){
	var scrollerinstance=this;
	if (this.mouseoverBol==1) {
		//if mouse is currently over scoller, do nothing (pause it)
		setTimeout(function(){scrollerinstance.setmessage()}, 100);
	} else {
		var i=this.hiddendivpointer;
		var ceiling=this.content.length;
		this.hiddendivpointer=((i+1>ceiling-1)? 0 : i+1);
		this.hiddendiv.innerHTML=this.content[this.hiddendivpointer];
		this.animateup();
	}
}

pausescroller.getCSSpadding=function(tickerobj){ //get CSS padding value, if any
	if (tickerobj.currentStyle) {
		return tickerobj.currentStyle["paddingTop"];
	} else if (window.getComputedStyle) {
		//if DOM2
		return window.getComputedStyle(tickerobj, "").getPropertyValue("padding-top");
	} else {
		return 0;
	}
}