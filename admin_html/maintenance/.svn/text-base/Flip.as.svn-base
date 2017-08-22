class Flip extends MovieClip{
	private var __intervalId:Number;
	private var __oldTop:MovieClip;
	private var __oldBottom:MovieClip;
	private var __currentTop:MovieClip;
	private var __currentBottom:MovieClip;
	private var __currentText:String;
	private var __oldText:String;
	private var __bg1:MovieClip;
	private var __bg2:MovieClip;
	private var __bg3:MovieClip;
	private var __bgColor:Number;
	private var __textColor:Number;
	private var __bgShadowAlpha:Number;
	public function Flip(){
		__oldText = "";
		__currentText = "";
		text = "";
	}
	public function onLoad(){
		bgColor = 0x262626;
		textColor = 0xFFFFFF;
	}
	private function freshText(){
		__oldTop.text.textMask.text_txt.text = __oldText;
		__oldBottom.text.textMask.text_txt.text = __oldText;
		__currentTop.text.textMask.text_txt.text = __currentText;
		__currentBottom.text.textMask.text_txt.text = __currentText;
	}
	public function set text(value){
		__oldText = __currentText;
		__currentText = value;
		freshText();
		gotoAndStop(_totalframes);
	}
	public function get text(){
		return __currentText;
	}
	public function updateText(value){
		__oldText = __currentText;
		__currentText = value;
		freshText();
		gotoAndPlay(1);
	}
	public function set bgColor(value){
		__bgColor = value;
		var c = new Color(__bg1.color);
		c.setRGB(__bgColor);
		var c = new Color(__bg2.color);
		c.setRGB(__bgColor);
		var c = new Color(__bg3.color);
		c.setRGB(__bgColor);
		
		var c = new Color(__oldTop.bg.color);
		c.setRGB(__bgColor);
		var c = new Color(__oldBottom.bg.color);
		c.setRGB(__bgColor);
		var c = new Color(__currentTop.bg.color);
		c.setRGB(__bgColor);
		var c = new Color(__currentBottom.bg.color);
		c.setRGB(__bgColor);
	}
	public function get bgColor(){
		return __bgColor;
	}
	public function set textColor(value){
		__textColor = value;
		var c = new Color(__oldTop.text.color);
		c.setRGB(__textColor);
		var c = new Color(__oldBottom.text.color);
		c.setRGB(__textColor);
		var c = new Color(__currentTop.text.color);
		c.setRGB(__textColor);
		var c = new Color(__currentBottom.text.color);
		c.setRGB(__textColor);
	}
	public function get textColor(){
		return __textColor;
	}
	public function set bgShadowAlpha(value){
		__bgShadowAlpha = value;
		__bg1.light._alpha = __bgShadowAlpha;
		__bg2.light._alpha = __bgShadowAlpha;
		__bg3.light._alpha = __bgShadowAlpha;
		
		__oldTop.bg.light._alpha = __bgShadowAlpha;
		__oldBottom.bg.light._alpha = __bgShadowAlpha;
		__currentTop.bg.light._alpha = __bgShadowAlpha;
		__currentBottom.bg.light._alpha = __bgShadowAlpha;
	}
	public function get bgShadowAlpha(){
		return __bgShadowAlpha;
	}
}