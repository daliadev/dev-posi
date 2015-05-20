
var NavigatorAgent = function() {

	this.agent = navigator.userAgent.toLowerCase();
	this.agentName = "n/a";
	this.agentVersion = "n/a";

	console.log('userAgent : ' + navigator.userAgent);
	console.log('appVersion : ' + navigator.appVersion);
	console.log('appName : ' + navigator.appName);
	console.log('appCodeName : ' + navigator.appCodeName);
	console.log('platform : ' + navigator.platform);
	console.log('product : ' + navigator.product);
	console.log('vendor : ' + navigator.vendor);


	/*
	var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
    var is_explorer = navigator.userAgent.indexOf('MSIE') > -1;
    var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
    var is_safari = navigator.userAgent.indexOf("Safari") > -1;
    var is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;
    if ((is_chrome)&&(is_safari)) {is_safari=false;}
    if ((is_chrome)&&(is_opera)) {is_chrome=false;}

	Or for Safari only, use this :
    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
    	alert('Its Safari');
    }

    unfortunately the above examples will also detect android's default browser as Safari, which it is not. I used 
    navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1 &&  navigator.userAgent.indexOf('Android') == -1

    The following identifies Safari 3.0+ and distinguishes it from Chrome:
	isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/)

	*/



	// Browser Detection - Internet Explorer

	/* From http://www.javascriptkit.com/javatutors/navigator.shtml */

	var detectIERegexp;
	var ieVersion = null;

	if (this.agent.indexOf('msie') != -1) {

		var detectIERegexp = /msie (\d+\.\d+);/ // Test for MSIE x.x
	}
	else {

		var detectIERegexp = /trident.*rv[\s:]*(\d+\.\d+)/ // Test for rv:x.x or rv x.x where Trident string exists
	}
	
	if (detectIERegexp.test(this.agent)) {

		//if some form of IE
		this.agentName = 'ie';
		//console.log(RegExp.$1);
		ieVersion = RegExp.$1
		var pointIndex = ieVersion.indexOf('.') != -1 ? ieVersion.indexOf('.') : ieVersion.length - 1;
		ieVersion = parseFloat(ieVersion.substring(0, pointIndex + 1));
		//ieVersion = parseFloat(ieVersion); // capture de la portion x.x et stockage sous forme de nombre
	}

	console.log(ieVersion);
    
    if (ieVersion != null && ieVersion >= 1) {

		this.agentVersion = parseInt(ieVersion).toString() + "+";
	}

	/*                                                                                 
	if (ieVersion >= 5) {
		this.agentVersion = parseInt(ieVersion).toString() + "+";
	}
	*/
	/*
	if (ieVersion >= 12) {
		this.agentVersion = '12+';
	}
	else if (ieVersion >= 11) {
		this.agentVersion = '11+';
	}
	else if (ieVersion >= 10) {
		this.agentVersion = '10+';
	}
	else if (ieVersion >= 9) {
		this.agentVersion = '9+';
	}
	else if (ieVersion >= 8) {
		this.agentVersion = '8+';
	}
	else if (ieVersion >= 7) {
		this.agentVersion = '7+';
	}
	else if (ieVersion >= 6) {
		this.agentVersion = '6+';
	}
	else if (ieVersion >= 5) {
		this.agentVersion = '5+';
	}
	*/
	/*
	else {
		this.agentVersion = "none";
	}
	*/




	// Browser Detection - FireFox

	var ffVersion = null;
	
	if (/Firefox[\/\s](\d+\.\d+)/.test(this.agent)) { 

		this.agentName = 'firefox';
		ffVersion = RegExp.$1
		var pointIndex = ffVersion.indexOf('.') != -1 ? ffVersion.indexOf('.') : ffVersion.length - 1;
		ffversion = ffVersion.substring(0, pointIndex + 1);
		ffVersion = parseFloat(ffversion); // capture de la portion x.x et stockage sous forme de nombre
	}

	if (ffVersion != null && ffVersion >= 1) {

		this.agentVersion = parseInt(ffVersion).toString() + "+";
	}
	/*
	if (ffVersion >= 35) {
		this.agentVersion = "35+";
	}
	else if (ffVersion >= 5) {
		this.agentVersion = "5+";
	}
	else if (ffVersion >= 4) {
		this.agentVersion = "4+";
	}
	else if (ffVersion >= 3) {
		this.agentVersion = "3+";
	}
	else if (ffVersion >= 2) {
		this.agentVersion = "2+";
	}
	else if (ffVersion >= 1) {
		this.agentVersion = "1+";
	}
	*/
	// else {
	// 	this.agentVersion = "none";
	// }



	// Browser Detection - Chrome

	/*
	if (ua.lastIndexOf('Chrome/') > 0) {
		var version = ua.substr(ua.lastIndexOf('Chrome/') + 7, 2);
		alert("chrome " + version);
	}
	*/

	var detectChromeRegexp;
	var chromeVersion = null;

	if (this.agent.indexOf('chrome') != -1 && this.agent.indexOf('safari') != -1) {

		var detectChromeRegexp = /chrome[\/\s](\d+\.\d+)/; // Test for MSIE x.x
	}
	
	if (detectChromeRegexp.test(this.agent)) { 

		//if some form of IE
		this.agentName = 'chrome';
		//console.log(RegExp.$1);
		chromeVersion = RegExp.$1
		var pointIndex = chromeVersion.indexOf('.') != -1 ? chromeVersion.indexOf('.') : chromeVersion.length - 1;
		chromeVersion = parseFloat(chromeVersion.substring(0, pointIndex + 1));
	}

	if (chromeVersion != null && chromeVersion >= 1) {

		this.agentVersion = parseInt(chromeVersion).toString() + "+";
	}





	// Browser Detection - Safari

	var detectSafRegexp;
	var safVersion = null;
	/*
	var is_safari = navigator.userAgent.indexOf("Safari") > -1;
    if ((is_chrome)&&(is_safari)) {is_safari=false;}
    if ((is_chrome)&&(is_opera)) {is_chrome=false;}

    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
    	alert('Its Safari');
    }

    navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1 &&  navigator.userAgent.indexOf('Android') == -1
	*/

	if (this.agent.indexOf('safari') != -1 && this.agent.indexOf('chrome') == -1 && this.agent.indexOf('android') == -1) {

		var detectSafRegexp = /version[\/\s](\d+\.\d+)/; // Test for MSIE x.x
	}
	
	if (detectSafRegexp.test(this.agent)) { 

		//if some form of IE
		this.agentName = 'safari';
		//console.log(RegExp.$1);
		safVersion = RegExp.$1
		var pointIndex = safVersion.indexOf('.') != -1 ? safVersion.indexOf('.') : safVersion.length - 1;
		safVersion = parseFloat(safVersion.substring(0, pointIndex + 1));
	}

	if (safVersion != null && safVersion >= 1) {

		this.agentVersion = parseInt(safVersion).toString() + "+";
	}


	// Browser Detection - Opera
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
	

	//Javascript Browser Detection - Safari
	/*
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
	*/

	this.getUserAgent = function() {

		return this.agent;
	};

	this.getName = function() {

		return this.agentName;
	};

	this.getVersion = function() {
		
		return this.agentVersion;
	};


	this.isAudioEnable = function() {

		return null;
	};

	this.isVideoEnable = function() {

		return null;
	};

	this.isPngEnable = function() {

		return null;
	};
	
}

/*
NavigatorAgent.prototype.getName = function() {

	return this.agentName;
}


NavigatorAgent.prototype.getVersion = function() {
	
	return this.agentVersion;
}

NavigatorAgent.prototype.isAudioEnable = function() {

	return this.agentName;
}

NavigatorAgent.prototype.isVideoEnable = function() {

	return this.agentName;
}

NavigatorAgent.prototype.isPngEnable = function() {

	return this.agentName;
}
*/