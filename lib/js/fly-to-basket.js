var flyingSpeed = 15;

var shopping_cart_div = false;
var flyingDiv = false;
var currentProductDiv = false;

var shopping_cart_x = false;
var shopping_cart_y = false;

var slide_xFactor = false;
var slide_yFactor = false;

var diffX = false;
var diffY = false;

var currentXPos = false;
var currentYPos = false;

function shoppingCart_getTopPos(inputObj)
{
  var returnValue = inputObj.offsetTop;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
  }
  return returnValue;
}

function shoppingCart_getLeftPos(inputObj)
{
  var returnValue = inputObj.offsetLeft;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
  }
  return returnValue;
}

function addToBasket(productId)
{
	if(!shopping_cart_div) {
		shopping_cart_div = document.getElementById('fly_to_basket_destination');
	}
	if(!flyingDiv){
		flyingDiv = document.createElement('DIV');
		flyingDiv.style.position = 'absolute';
		flyingDiv.style.zIndex = 1200;
		document.body.appendChild(flyingDiv);
	}

	shopping_cart_x = shoppingCart_getLeftPos(shopping_cart_div);
	shopping_cart_y = shoppingCart_getTopPos(shopping_cart_div);

	currentProductDiv = document.getElementById('slidingProduct' + productId);

	currentXPos = shoppingCart_getLeftPos(currentProductDiv);
	currentYPos = shoppingCart_getTopPos(currentProductDiv);

	diffX = shopping_cart_x - currentXPos;
	diffY = shopping_cart_y - currentYPos;

	var shoppingContentCopy = currentProductDiv.cloneNode(true);
	shoppingContentCopy.id='';
	flyingDiv.innerHTML = '';
	flyingDiv.style.left = currentXPos + 'px';
	flyingDiv.style.top = currentYPos + 'px';
	flyingDiv.appendChild(shoppingContentCopy);
	flyingDiv.style.display='block';
	flyingDiv.style.width = currentProductDiv.offsetWidth + 'px';
	flyToBasket(productId);
}

function flyToBasket(productId)
{
	var maxDiff = Math.max(Math.abs(diffX),Math.abs(diffY));
	var moveX = (diffX / maxDiff) * flyingSpeed;;
	var moveY = (diffY / maxDiff) * flyingSpeed;

	currentXPos = currentXPos + moveX;
	currentYPos = currentYPos + moveY;

	flyingDiv.style.left = Math.round(currentXPos) + 'px';
	flyingDiv.style.top = Math.round(currentYPos) + 'px';

	if(moveX>0 && currentXPos > shopping_cart_x){
		flyingDiv.style.display='none';
	}
	if(moveX<0 && currentXPos < shopping_cart_x){
		flyingDiv.style.display='none';
	}

	if(flyingDiv.style.display=='block') {setTimeout('flyToBasket("' + productId + '")',20);}
}