//------------------------------------------------------------------
// version 1.0
// create by tw.jason.chen@gmail.com
//------------------------------------------------------------------
class FlipCountdownClock extends MovieClip {
	//---------------------------------------------------------------------------------------
	//define vars
	//---------------------------------------------------------------------------------------
	private var __tickId:Number;
	private var __targetTimeText:String;
	private var __targetTimeZone:Number;
	private var __targetTime:Date;
	private var __oldTime:Date;
	private var __currentTime:Date;
	private var __day1;
	private var __day2;
	private var __day3;
	private var __hour1;
	private var __hour2;
	private var __min1;
	private var __min2;
	private var __sec1;
	private var __sec2;
	private var __bgColor:Number;
	private var __textColor:Number;
	private var __bgShadowAlpha:Number;
	private var __config_xml:XML;
	public var onFinish:Function;
	public var onConfigLoad:Function;
	//---------------------------------------------------------------------------------------
	//constructor
	//---------------------------------------------------------------------------------------
	public function FlipCountdownClock() {
		//---------------------------------------------------------------------------------------
		//init vars
		//---------------------------------------------------------------------------------------
		var target = this;
		__config_xml = new XML();
		__config_xml.ignoreWhite = true;
		//parse config on config loaded
		__config_xml.onLoad = function(success){
			if(success){
				var config = target.parseXml(this.firstChild);
				var targetTimeText = config.targetTimeText[0]["@value"];
				var targetTimeZone =  parseFloat(config.targetTimeZone[0]["@value"]);
				var bgShadowAlpha =  parseFloat(config.bgShadowAlpha[0]["@value"]);
				var textColor =  parseInt(config.textColor[0]["@value"],16);
				var bgColor =  parseInt(config.bgColor[0]["@value"],16);
				
				target.targetTimeText = targetTimeText;
				if(!isNaN(targetTimeZone)){
					target.targetTimeZone = targetTimeZone;
				}
				if(!isNaN(bgShadowAlpha)){
					target.bgShadowAlpha = bgShadowAlpha;
				}
				if(!isNaN(textColor)){
					target.textColor = textColor;
				}
				if(!isNaN(bgColor)){
					target.bgColor = bgColor;
				}
				target.startTrace();

				target.onConfigLoad();
			}
		}
		__targetTimeZone = 0;
	}
	public function onLoad() {
		__day1.text = "0";
		__day2.text = "0";
		__day3.text = "0";
		__hour1.text = "0";
		__hour2.text = "0";
		__min1.text = "0";
		__min2.text = "0";
		__sec1.text = "0";
		__sec2.text = "0";
		__day1.text = "0";
		bgColor = 0x262626;
		textColor = 0xFFFFFF;
	}
	//---------------------------------------------------------------------------------------
	//method
	//---------------------------------------------------------------------------------------
	//parse xml
	private function parseXml(node:XMLNode):Object {
		var nodeData:Object = {};
		switch (node.nodeType) {
		case 1 :
		case 4 :
			var nodeData = {};
			var attr = {};
			for (var varName in node.attributes) {
				attr[varName] = node.attributes[varName];
			}
			nodeData["@attributes"] = attr;
			var childNodeData:Object;
			var childNode:XMLNode;
			var childNodesLength = node.childNodes.length;
			for (var i = 0; i<childNodesLength; i++) {
				childNode = node.childNodes[i];
				var childNodeData = parseXml(childNode);
				switch (childNode.nodeType) {
				case 1 :
				case 4 :
					if (nodeData[childNode.nodeName] == undefined) {
						nodeData[childNode.nodeName] = [];
					}
					nodeData[childNode.nodeName].push(childNodeData);
					break;
				case 3 :
					nodeData["@value"] = childNodeData;
					break;
				}
			}
			break;
		case 3 :
			nodeData = node.nodeValue;
			break;
		}
		return nodeData;
	}
	//check time
	private function checkTime(target, update) {
		target.__currentTime = new Date();
		if(target.__targetTime.getTime()<(target.__currentTime.getTime()+target.__currentTime.getTimezoneOffset()*60*1000)){
			target.__day1.text = "0"
			target.__day2.text = "0"
			target.__day3.text = "0"
			target.__hour1.text = "0"
			target.__hour2.text = "0"
			target.__min1.text = "0"
			target.__min2.text = "0"
			target.__sec1.text = "0"
			target.__sec2.text = "0"
			target.onFinish();
			target.stopTrace();
			return false;
		}
		var oldTimeText = target.timeFormat(target.__targetTime.getTime()-(target.__oldTime.getTime()+target.__oldTime.getTimezoneOffset()*60*1000));
		var currentTimeText = target.timeFormat(target.__targetTime.getTime()-(target.__currentTime.getTime()+target.__currentTime.getTimezoneOffset()*60*1000));
		var oldTimeChars = oldTimeText.split("");
		var currentTimeChars = currentTimeText.split("");
		if (update && !isNaN(target.__targetTime)) {
			target.__day1.text = currentTimeChars[0];
			target.__day2.text = currentTimeChars[1];
			target.__day3.text = currentTimeChars[2];
			target.__hour1.text = currentTimeChars[4];
			target.__hour2.text = currentTimeChars[5];
			target.__min1.text = currentTimeChars[7];
			target.__min2.text = currentTimeChars[8];
			target.__sec1.text = currentTimeChars[10];
			target.__sec2.text = currentTimeChars[11];
		} else {
			if (currentTimeChars[0] != oldTimeChars[0]) {
				target.__day1.updateText(currentTimeChars[0]);
			}
			if (currentTimeChars[1] != oldTimeChars[1]) {
				target.__day2.updateText(currentTimeChars[1]);
			}
			if (currentTimeChars[2] != oldTimeChars[2]) {
				target.__day3.updateText(currentTimeChars[2]);
			}
			if (currentTimeChars[4] != oldTimeChars[4]) {
				target.__hour1.updateText(currentTimeChars[4]);
			}
			if (currentTimeChars[5] != oldTimeChars[5]) {
				target.__hour2.updateText(currentTimeChars[5]);
			}
			if (currentTimeChars[7] != oldTimeChars[7]) {
				target.__min1.updateText(currentTimeChars[7]);
			}
			if (currentTimeChars[8] != oldTimeChars[8]) {
				target.__min2.updateText(currentTimeChars[8]);
			}
			if (currentTimeChars[10] != oldTimeChars[10]) {
				target.__sec1.updateText(currentTimeChars[10]);
			}
			if (currentTimeChars[11] != oldTimeChars[11]) {
				target.__sec2.updateText(currentTimeChars[11]);
			}
		}
		target.__oldTime = target.__currentTime;
		return true;
	}
	//time format
	private function timeFormat(mTime) {
		var time = Math.floor(mTime/1000);
		var s = time%60;
		var i = Math.floor(time%(60*60)/60);
		var h = Math.floor(time%(24*60*60)/(60*60));
		var d = Math.floor(time/(24*60*60));
		return textFormat(d, 3, "0")+"-"+textFormat(h, 2, "0")+"-"+textFormat(i, 2, "0")+"-"+textFormat(s, 2, "0");
	}
	//text format
	private function textFormat(text, length, fillChar) {
		text = text.toString();
		while (text.length<length) {
			text = fillChar+text;
		}
		if(text.length>length){
			text = text.substr(text.length-length,length);
		}
		return text;
	}
	//start
	private function startTrace(){
		_visible = true;

		var flag = checkTime(this, true);
			stopTrace();
		if(flag){
			__tickId = setInterval(checkTime, 100, this, false);
		}
	}
	//stop
	private function stopTrace(){
		clearInterval(__tickId);
	}
	//loadConfig
	public function loadConfig(value){
		_visible = false;
		__config_xml.load(value);
	}
	//---------------------------------------------------------------------------------------
	//properties
	//---------------------------------------------------------------------------------------
	//targetTimeText
	public function set targetTimeText(value) {
		__targetTimeText = value;
		var times = __targetTimeText.split("-");
		var y = parseInt(times[0]);
		var m = parseInt(times[1])-1;
		var d = parseInt(times[2]);
		var h = parseInt(times[3])
		var i = parseInt(times[4])-__targetTimeZone*60;
		var s = parseInt(times[5]);
		__targetTime = new Date(y, m, d, h, i, s, 0);
	}
	//targetTimeText
	public function get targetTimeText() {
		return __targetTimeText;
	}
	//targetTimeZone
	public function set targetTimeZone(value) {
		__targetTimeZone = value;
		var times = __targetTimeText.split("-");
		var y = parseInt(times[0]);
		var m = parseInt(times[1])-1;
		var d = parseInt(times[2]);
		var h = parseInt(times[3]);
		var i = parseInt(times[4])-__targetTimeZone*60;
		var s = parseInt(times[5]);
		__targetTime = new Date(y, m, d, h, i, s, 0);
	}
	//targetTimeZone
	public function get targetTimeZone() {
		return __targetTimeZone;
	}
	//bgColor
	public function set bgColor(value){
		__bgColor = value;
		__day1.bgColor = __bgColor;
		__day2.bgColor = __bgColor;
		__day3.bgColor = __bgColor;
		__hour1.bgColor = __bgColor;
		__hour2.bgColor = __bgColor;
		__min1.bgColor = __bgColor;
		__min2.bgColor = __bgColor;
		__sec1.bgColor = __bgColor;
		__sec2.bgColor = __bgColor;
	}
	//bgColor
	public function get bgColor(){
		return __bgColor;
	}
	//textColor
	public function set textColor(value){
		__textColor = value;
		__bgColor = value;
		__day1.textColor = __textColor;
		__day2.textColor = __textColor;
		__day3.textColor = __textColor;
		__hour1.textColor = __textColor;
		__hour2.textColor = __textColor;
		__min1.textColor = __textColor;
		__min2.textColor = __textColor;
		__sec1.textColor = __textColor;
		__sec2.textColor = __textColor;
	}
	//textColor
	public function get textColor(){
		return __textColor;
	}
	//bgShadowAlpha
	public function set bgShadowAlpha(value){
		__bgShadowAlpha = value;
		__day1.bgShadowAlpha = __bgShadowAlpha;
		__day2.bgShadowAlpha = __bgShadowAlpha;
		__day3.bgShadowAlpha = __bgShadowAlpha;
		__hour1.bgShadowAlpha = __bgShadowAlpha;
		__hour2.bgShadowAlpha = __bgShadowAlpha;
		__min1.bgShadowAlpha = __bgShadowAlpha;
		__min2.bgShadowAlpha = __bgShadowAlpha;
		__sec1.bgShadowAlpha = __bgShadowAlpha;
		__sec2.bgShadowAlpha = __bgShadowAlpha;
	}
	//bgShadowAlpha
	public function get bgShadowAlpha(){
		return __bgShadowAlpha;
	}
}
