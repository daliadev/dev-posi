
var NavigatorAgent = function() {

	this.agent = navigator.userAgent.toLowerCase();
	this.agentName = "n/a";
	this.agentVersion = null;

	/*
	console.log('userAgent : ' + navigator.userAgent);
	console.log('appVersion : ' + navigator.appVersion);
	console.log('appName : ' + navigator.appName);
	console.log('appCodeName : ' + navigator.appCodeName);
	console.log('platform : ' + navigator.platform);
	console.log('product : ' + navigator.product);
	console.log('vendor : ' + navigator.vendor);
	*/

	var detectRegexp = null;
	var matchVersion = null;
	var versionPointIndex = null;


	/* Browser Detection regexp */

	// Test for MSIE
	if (this.agent.indexOf('msie') != -1) {

		var detectRegexp = /msie (\d+\.\d+)/;
		this.agentName = 'ie';
	}

	// Test for Trident rv:x.x -> IE > 9
	else if (this.agent.indexOf('trident') != -1) {

		var detectRegexp = /trident.*rv[\s:]*(\d+\.\d+)/;
		this.agentName = 'ie';
	}

	// Test for firefox
	else if (this.agent.indexOf('firefox') != -1) {

		var detectRegexp = /firefox[\/\s](\d+\.\d+)/; 
		this.agentName = 'firefox';
	}

	// Test for safari
	else if (this.agent.indexOf('safari') != -1 && this.agent.indexOf('chrome') == -1 && this.agent.indexOf('opr') == -1 && this.agent.indexOf('android') == -1) {

		var detectRegexp = /version[\/\s](\d+\.\d+)/;
		this.agentName = 'safari';
	}

	// Test for Chrome
	else if (this.agent.indexOf('chrome') != -1 && this.agent.indexOf('opr') == -1 && this.agent.indexOf('opera') == -1) {

		var detectRegexp = /chrome[\/\s](\d+\.\d+)/;
		this.agentName = 'chrome';
	}

	// Test for Opera >= 15
	else if (this.agent.indexOf('opr') != -1) {

		var detectRegexp = /opr\/(\d+\.\d+)/;
		this.agentName = 'opera';
	}

	// Test for Opera < 15
	else if (this.agent.indexOf('opera') != -1) {

		var detectRegexp = /opera[\/\s](\d+\.\d+)/;
		this.agentName = 'opera';
	}

	// Test for Android
	if (this.agent.indexOf('android') != -1) {

		var detectRegexp = /android[\/\s](\d+\.\d+)/;
		this.agentName = 'android';
	}

	// Test for all Mobile
	/*
	if (this.agent.indexOf('android') != -1) {
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(ua)) {

		// Check if the orientation has changed 90 degrees or -90 degrees... or 0
		window.addEventListener("orientationchange", function () {
			alert(window.orientation);
		});
	}
	*/


	/* Browser version detection */

	if (detectRegexp.test(this.agent)) {

		matchVersion = RegExp.$1
		versionPointIndex = matchVersion.indexOf('.') != -1 ? matchVersion.indexOf('.') : matchVersion.length - 1;
		matchVersion = parseFloat(matchVersion.substring(0, versionPointIndex + 2));

		if (matchVersion != null && matchVersion >= 0) {

			//this.agentVersion = parseInt(matchVersion);
			this.agentVersion = matchVersion;
		}
	}


	/* Browser version */
	/*
	if (matchVersion != null && matchVersion >= 0) {

		//this.agentVersion = parseInt(matchVersion).toString() + "+";
		this.agentVersion = matchVersion;
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


	this.isAudioEnabled = function() {

		if ((this.agentName == 'ie' && this.agentVersion >= 9) || 
			(this.agentName == 'chrome' && this.agentVersion >= 4) || 
			(this.agentName == 'firefox' && this.agentVersion >= 3.5) || 
			(this.agentName == 'safari' && this.agentVersion >= 4) || 
			(this.agentName == 'opera' && this.agentVersion >= 10.5)) {

			return true;
		}
		else {
			return false;
		}

		//return null;
	};

	this.isVideoEnabled = function() {

		if ((this.agentName == 'ie' && this.agentVersion >= 9) || 
			(this.agentName == 'chrome' && this.agentVersion >= 4) || 
			(this.agentName == 'firefox' && this.agentVersion >= 3.5) || 
			(this.agentName == 'safari' && this.agentVersion >= 4) || 
			(this.agentName == 'opera' && this.agentVersion >= 10.5)) {

			return true;
		}
		else {
			return false;
		}
		//return null;
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