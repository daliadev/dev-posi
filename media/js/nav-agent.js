
function NavigatorAgent() {

	this.agent = navigator.userAgent;
	this.agentName = null;
	this.agentVersion = null;


	// Detection script
	var ua = navigator.userAgent;
	var msie = false;
	var ff = false;
	var chrome = false;


	// Internet Explorer
	/*
	From
	http://www.javascriptkit.com/javatutors/navigator.shtml

	if (navigator.userAgent.indexOf('MSIE') != -1)
	var detectIEregexp = /MSIE (\d+\.\d+);/ //test for MSIE x.x
	else // if no "MSIE" string in userAgent
	var detectIEregexp = /Trident.*rv[ :]*(\d+\.\d+)/ //test for rv:x.x or rv x.x where Trident string exists
	
	if (detectIEregexp.test(navigator.userAgent)){ //if some form of IE
		var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
	if (ieversion>=12)
		document.write("You're using IE12 or above")
	else if (ieversion>=11)
		document.write("You're using IE11 or above")
	else if (ieversion>=10)
		document.write("You're using IE10 or above")
	else if (ieversion>=9)
		document.write("You're using IE9 or above")
	else if (ieversion>=8)
		document.write("You're using IE8 or above")
	else if (ieversion>=7)
		document.write("You're using IE7.x")
	else if (ieversion>=6)
		document.write("You're using IE6.x")
	else if (ieversion>=5)
		document.write("You're using IE5.x")
	}
	else{
		document.write("n/a")
	}
	*/

	// See also http://www.xul.fr/ecmascript/navigator.php

	
	if (/MSIE (\d+\.\d+);/.test(ua)) //test for MSIE x.x; True or False
	{
		var msie = (/MSIE (\d+\.\d+);/.test(ua)); //True or False
		var ieversion = new Number(RegExp.$1); //gets browser version
		alert("ie: " + msie + ' version:' + ieversion);
	}

	//Javascript Browser Detection - FireFox
	/*
	if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)){ //test for Firefox/x.x or Firefox x.x (ignoring remaining digits);
		var ffversion=new Number(RegExp.$1) // capture x.x portion and store as a number
	if (ffversion>=35)
		document.write("You're using FF 35 or above")
	else if (ffversion>=5)
		document.write("You're using FF 5.x or above")
	else if (ffversion>=4)
		document.write("You're using FF 4.x or above")
	else if (ffversion>=3)
		document.write("You're using FF 3.x or above")
	else if (ffversion>=2)
		document.write("You're using FF 2.x")
	else if (ffversion>=1)
		document.write("You're using FF 1.x")
	}
	else
		document.write("n/a")
	*/
	if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.ua))//test for Firefox/x.x or Firefox x.x
	{
		var ff = (/Firefox[\/\s](\d+\.\d+)/.test(navigator.ua)); //True or False
		var ffversion = new Number(RegExp.$1) //gets browser version
		alert("FF: " + ff + ' version:' + ieversion);
	}

	// Opera
	//var opera15andabove = navigator.userAgent.indexOf('OPR/') != -1 // Opera 15+ Boolean
	/*
	var opera15andabovever = /OPR\/(\d+\.\d+)/i.test(navigator.userAgent) // test and capture Opera 15+ version
	if (opera15andabovever){
		var operaver = new Number(RegExp.$1) // contains exact Opera15+ version, such as 25 for Opera 25.0
		document.write("You're using Opera" + operaver)
	}
	else{
		document.write("n/a")
	}
	*/

	/*
	Pre Opera 15
	//Note: userAgent in Opera9.24 WinXP returns: Opera/9.24 (Windows NT 5.1; U; en)
	//         userAgent in Opera 8.5 (identified as IE) returns: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Opera 8.50 [en]
	//         userAgent in Opera 8.5 (identified as Opera) returns: Opera/8.50 (Windows NT 5.1; U) [en]

	if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)){ //test for Opera/x.x or Opera x.x (ignoring remaining decimal places);
		var oprversion=new Number(RegExp.$1) // capture x.x portion and store as a number
	if (oprversion>=10)
		document.write("You're using Opera 10.x or above")
	else if (oprversion>=9)
		document.write("You're using Opera 9.x")
	else if (oprversion>=8)
		document.write("You're using Opera 8.x")
	else if (oprversion>=7)
		document.write("You're using Opera 7.x")
	else
		document.write("n/a")
	}
	else
		document.write("n/a")
	</script>
	*/



	//Javascript Browser Detection - Chrome
	if (ua.lastIndexOf('Chrome/') > 0) {
		var version = ua.substr(ua.lastIndexOf('Chrome/') + 7, 2);
		alert("chrome " + version);
	}

	//Javascript Browser Detection - Safari
	if (ua.lastIndexOf('Safari/') > 0) {
		var version = ua.substr(ua.lastIndexOf('Safari/') + 7, 2);
		alert("Safari " + version);
	}

	//Javascript Browser Detection - Android
	if (ua.indexOf("Android") >= 0) {
		var androidversion = parseFloat(ua.slice(ua.indexOf("Android") + 8));
		if (androidversion < 2.3) {
			// do whatever
			alert("This older version of Android has some issues with CSS");
		}
	}

	//Javascript Browser Detection - Mobile
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(ua)) {

		// Check if the orientation has changed 90 degrees or -90 degrees... or 0
		window.addEventListener("orientationchange", function () {
			alert(window.orientation);
		});
	}
}


NavigatorAgent.prototype.getName = function() {

	return this.agentName;
}


NavigatorAgent.prototype.getVersion = function() {
	
	return this.agentVersion;
}