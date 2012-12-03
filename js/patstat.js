
// Internet Explorer stub for the console
if(typeof(console) == 'undefined' || typeof(console.log) == 'undefined') {
	function Console() {
		this.log = function(message) {
			// I could create a hidden, or 
			// foreground div and send messages to it
		};
	}	
	console = new Console();
}
	
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
		contents.innerHTML= '<img style="align: center; valign: center" src="images/ajax-loader.gif"/>';
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

function reloadCourseIntoParent(id) {
	var contents = parent.document.getElementById('contents');
	if(contents) {
		console.log('setting animated icon');
		contents.innerHTML= '<img style="align: center; valign: center" src="ajax-loader.gif"/>';
		// do AJAX call
		var xmlhttp = getXmlHttpRequestObject();
		xmlhttp.open("GET", "course.php?courseid=" + id, false);
		console.log('reloading course with id ' + id);
		xmlhttp.send();
		parent.document.getElementById("contents").innerHTML = xmlhttp.responseText;
	}
}


function divide (numerator, denominator) {
	if(numerator == denominator) {
		// denominator fits exactly once into numerator, thus result is 1, remainder is 0
		//console.log('dividing ' + numerator + ' : ' + denominator + ' = 1 R 0');
		return [1, 0];		
	} else if(numerator < denominator) {
		// denominator fits 0 times into the numerator, and it's all remainder
		//console.log('dividing ' + numerator + ' : ' + denominator + ' = 0 R ' + numerator);
		return [0, numerator];	
	}
	var remainder = numerator % denominator;
	var result =  ( numerator - remainder ) / denominator;
	//console.log('dividing ' + numerator + ' : ' + denominator + ' = ' + result + ' R ' + remainder);
	return [result, remainder];
}

function Timer() {
	
	this.timer = null;
	this.interval = 1000; // default: fires every second
	this.value = 0;
	
	this.formatTime = function(time) {
		if(time == 0) {
			return '0 sec';
		} 
		var result1 = divide(time, 3600);
		var hours = result1[0];
		var result2 = divide(result1[1], 60);
		var mins = result2[0];
		var secs = result2[1];
		var result = '';
		if(hours > 0) {
			if(hours == 1) result = result + '1 ora ';
			else result = result + hours + ' ore ';
		}
		if(mins > 0) result = result + mins + ' min ';
		result = result + secs + ' sec';
		return result;
	};
	
	this.callback = function() {
		//console.log("timer called...");
		this.value = this.value + 1;
		document.getElementById("timer").innerHTML = "avanzamento: " + this.formatTime(this.value);	
	};
	
	this.start = function(time) {
		if(time) this.interval = time;	
		console.log("timer starting...");	
		this.timer = setInterval("timer.callback()", this.interval);
		this.value = 0;
	};
	
	this.pause = function() {
		console.log("timer pausing, duration so far: " + this.value);		
		clearInterval(this.callback);
		return this.value;
	}
	
	this.resume = function() {
		console.log("timer resuming...");	
		this.timer = setInterval("timer.callback()", this.interval);
	}
	
	this.stop = function() {
		console.log("timer stopping, duration: " + this.value);		
		clearInterval(this.callback);
		return this.value;
	};  
}

var timer = new Timer();

function registerProgress(elemid, courseid) {
	var duration = timer.stop();
	var xmlhttp = getXmlHttpRequestObject();
	console.log('registering progress of element with id ' + elemid + ', duration ' + duration + ' seconds');
	xmlhttp.open("GET", "register.php?elemid=" + elemid + "&duration=" + duration, false);
	xmlhttp.send();
	console.log("   ... element " + elemid + " progres registration result: " + xmlhttp.responseText);
	reloadCourseIntoParent(courseid);	
	console.log("   ... undelying page reloaded to " + courseid);
}


function goToFirstElement(id) {	
	showPopWin('element.php?elementid=' + id, 900, 710);
}

function goToElement(oldElemId, newElemId) {
	//var duration = timer.stop();
	//registerProgress(oldElemId, duration);
	console.log('going to element: ' + newElemId);
	document.body.style.cursor = "wait";
	//document.body.style = 'cursor: url("/siparium/images/wait.gif")';
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

function goToAdmin(url) {
	showPopWin(url, 800, 650);
}


function closePopup() {
	var element = parent.document.getElementById('popCloseBox');
	if(element) {
		console.log("popCloseBox element found");
		if(element.onclick) {
			console.log("popCloseBox element.onclick found");
			element.onclick();
		}
	} else {
		console.log("popCloseBox element NOT found");
	}
		
}

console.log("loading version 1.1");

function showUserParticipations() {
	var checkbox = document.getElementById('showusers');
	if(checkbox.checked) {
		var div = document.getElementById('showusers_area');
		if(div) {
			div.innerHTML= '<p align="center"><img align="center" valign="center" src="images/ajax-loader.gif"/></p>';
			// do Ajax Call
			//get our browser specific XmlHttpRequest object.
			var xmlhttp = getXmlHttpRequestObject();
			xmlhttp.open("GET", "listusers.php", true);
			console.log('retrieving list of user involved in training');
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState==4) {
					document.getElementById('showusers_area').innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.send();
		}
	} else {
		document.getElementById('showusers_area').innerHTML = '';
	}	
}

function goToChart(url) {	
	showPopWin(url, 900, 710);
}

/*
field[i].checked = true ;
*/

/********** DETECT WHEN BROWSER LOSES FOCUS *********************/
function onBlur() {
    //document.body.className = 'blurred';
	console.log("window is losing focus");
	if(timer) timer.pause();
};
function onFocus(){
    //document.body.className = 'focused';
	console.log("window has regained focus");
	if(timer) timer.resume();
};

//if (/*@cc_on!@*/false) { // check for Internet Explorer
//    document.onfocusin = onFocus;
//    document.onfocusout = onBlur;
//} else {
//    window.onfocus = onFocus;
//    window.onblur = onBlur;
//}
