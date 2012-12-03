function Timer() {
	
	this.timer = null;
	
	this.value = 0;
	
	this.formatTime = function(time) {
		if(time == 0) {
			return '0 sec';
		} else {
			var hours = divide(time, 3600);
			//console.log("hours: " + hours);
			var mins = divide((time - (hours * 3600)), 60);
			//console.log("mins: " + mins);
			var secs = (time - ((hours * 60) + mins)) % 60;
			//console.log("secs: " + secs);
			return (hours > 0 ? (hours == 1 ? hours + ' ora ' : hours + ' ore ' ) : ' ') + ' ' + (mins > 0 ? mins + ' min ' : ' ') + secs + ' sec';
		}
	}
	
	this.callback = function() {
		//console.log("timer called...");
		this.value = this.value + 1;
		document.getElementById("timer").innerHTML = "avanzamento: " + this.formatTime(this.value);	
	};
	
	this.start = function() {
		console.log("timer starting...");	
		this.timer = setInterval("timer.callback()", 1000);
		this.value = 0;
	};
	
	this.stop = function() {
		console.log("timer stopping, duration: " + this.value);		
		clearInterval(this.callback);
		return this.value;
	};  
}

function Console() {
	
	this.DEBUG = 0;
	this.INFO = 1;
	this.WARN = 2;
	this.ERROR = 3;
	this.FATAL = 4;
	
	this.level = DEBUG;
	
	
	this.log = function(message) {
		if(this.level == Console.INFO) {
			
		if(time == 0) {
			return '0 sec';
		} else {
			var hours = divide(time, 3600);
			//console.log("hours: " + hours);
			var mins = divide((time - (hours * 3600)), 60);
			//console.log("mins: " + mins);
			var secs = (time - ((hours * 60) + mins)) % 60;
			//console.log("secs: " + secs);
			return (hours > 0 ? (hours == 1 ? hours + ' ora ' : hours + ' ore ' ) : ' ') + ' ' + (mins > 0 ? mins + ' min ' : ' ') + secs + ' sec';
		}
	}
	
	this.callback = function() {
		//console.log("timer called...");
		this.value = this.value + 1;
		document.getElementById("timer").innerHTML = "avanzamento: " + this.formatTime(this.value);	
	};
	
	this.start = function() {
		console.log("timer starting...");	
		this.timer = setInterval("timer.callback()", 1000);
		this.value = 0;
	};
	
	this.stop = function() {
		console.log("timer stopping, duration: " + this.value);		
		clearInterval(this.callback);
		return this.value;
	};  
}


var timer = new Timer();





/*
function AJAX() {
	this.xmlhttp = null;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		return xmlhttp;
	} catch (e) { }

	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		return xmlhttp;
	} catch (e) { }
		
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest(); //Not IE
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP"); //IE
	} else {
		log.console("browser doesn't support the XmlHttpRequest object");
	}
	
	this.send = function(method, url, async, callback) {
		if(xmlhttp) {
			xmlhttp.open(method, url, async);
			if(async) {
				xmlhttp.onreadystatechange = callback;
			}
			xmlhttp.send();
  		}
	}
}
*/

	
	
//Gets the browser specific XmlHttpRequest Object
function getXmlHttpRequestObject() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		return xmlhttp;
	} catch (e) { }

	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		return xmlhttp;
	} catch (e) { }
	
	
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest(); //Not IE
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP"); //IE
	} else {
		//Display your error message here. 
		//and inform the user they might want to upgrade
		//their browser.
		alert("Your browser doesn't support the XmlHttpRequest object.  Better upgrade to Firefox.");
	}
	return null;
}


function goToCourse(id) {
	var contents = document.getElementById('contents');
	if(contents) {
		console.log('setting animated icon');
		contents.innerHTML= '<img style="align: center; valign: center" src="ajax-loader.gif"/>';
		// do Ajax Call
		//get our browser specific XmlHttpRequest object.
		var xmlhttp = getXmlHttpRequestObject();
		xmlhttp.open("GET", "course.php?courseid=" + id, true);
		console.log('calling with id ' + id);
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState==4) {
    				document.getElementById("contents").innerHTML = xmlhttp.responseText;
   			}
  		}
		xmlhttp.send();
	}
}


function divide ( numerator, denominator ) {
	var remainder = numerator % denominator;
	return quotient = ( numerator - remainder ) / denominator;
}


function registerProgress(elemid/*, duration*/) {
	var duration = timer.stop();
	var xmlhttp = getXmlHttpRequestObject();
	console.log('registering progress of element with id ' + elemid + ', duration ' + duration + ' seconds');
	xmlhttp.open("GET", "register.php?elemid=" + elemid + "&duration=" + duration, false);
	xmlhttp.send();
	console.log("   ... element " + elemid + " progres registration result: " + xmlhttp.responseText);
	/*
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState==4) {
			console.log("element progress registered: " + xmlhttp.responseText);
			//document.getElementById("contents").innerHTML = xmlhttp.responseText;
		}
	}
	
	*/
}

function goToFirstElement(id) {
	showPopWin('element.php?elementid=' + id, 900, 710);
}

function goToElement(oldElemId, newElemId) {
	//var duration = timer.stop();
	//registerProgress(oldElemId, duration);
	console.log('going to element: ' + newElemId);
	document.location = 'element.php?elementid=' + newElemId;
}

function grow(element, src) {
	if(element) {
		//console.log("inflating element " + element);
		element.src=src;
		document.body.style.cursor = 'pointer';
	}
}

function shrink(element, src) {
	if(element) {
		//console.log("shrinking element " + element);
		element.src = src;
		document.body.style.cursor = 'default';
	}
}
