var menu=function()
{
	var t=15,z=50,s=6,a;
	function dd(n)
	{
		this.n=n;this.h=[];
		this.c=[]
	}
dd.prototype.init=function(p,c)
	{	
		a=c;
		var w=document.getElementById(p),s=w.getElementsByTagName('ul'),l=s.length,i=0;
		for(i;i<l;i++)
		{
			var h=s[i].parentNode;this.h[i]=h;
			this.c[i]=s[i];
			h.onmouseover=new Function(this.n+'.st('+i+',true)');
			h.onmouseout=new Function(this.n+'.st('+i+')');
		}
	}
dd.prototype.st=function(x,f)
{	
		var c=this.c[x],h=this.h[x],p=h.getElementsByTagName('a')[0];
		clearInterval(c.t);
		c.style.overflow='hidden';
		if(f)
		{
			p.className+=' '+a;
			if(!c.mh)
			{
				c.style.display='block';
				c.style.height='';c.mh=c.offsetHeight;c.style.height=0
			}
if(c.mh==c.offsetHeight)
{
	c.style.overflow='visible'
	}
else
	{
		c.style.zIndex=z;z++;
		c.t=setInterval(function(){sl(c,1)},t)
	}
		}
	else
	{
		p.className=p.className.replace(a,'');
		c.t=setInterval(function(){sl(c,-1)},t)
	}
}
function sl(c,f)
{
	var h=c.offsetHeight;
	if((h<=0&&f!=1)||(h>=c.mh&&f==1))
	{
		if(f==1)
		{
			c.style.filter='';
			c.style.opacity=1;
			c.style.overflow='visible'
		}
		clearInterval(c.t);
		return
	}
	var d=(f==1)?Math.ceil((c.mh-h)/s):Math.ceil(h/s),o=h/c.mh;
	c.style.opacity=o;
	c.style.filter='alpha(opacity='+(o*100)+')';
	c.style.height=h+(d*f)+'px'
}
return{dd:dd}}();

      function init () {
	var tables = document.getElementsByTagName("table");
	for (var i = 0; i < tables.length; i++) {
	  if (tables[i].className.match(/zebra/)) {
	    zebra(tables[i]);
	  }
	}
      }

      function zebra (table) {
	var current = "oddRow";
	var trs = table.getElementsByTagName("tr");
	for (var i = 0; i < trs.length; i++) {
	  trs[i].className += " " + current;
	  current = current == "evenRow" ? "oddRow" : "evenRow";
	}
      }
	  
	  var tooltip=function(){
	var id = 'tt';
	var top = 3;
	var left = 3;
	var maxw = 300;
	var speed = 10;
	var timer = 20;
	var endalpha = 95;
	var alpha = 0;
	var tt,t,c,b,h;
	var ie = document.all ? true : false;
	return{
		show:function(v,w){
			if(tt == null){
				tt = document.createElement('div');
				tt.setAttribute('id',id);
				t = document.createElement('div');
				t.setAttribute('id',id + 'top');
				c = document.createElement('div');
				c.setAttribute('id',id + 'cont');
				b = document.createElement('div');
				b.setAttribute('id',id + 'bot');
				tt.appendChild(t);
				tt.appendChild(c);
				tt.appendChild(b);
				document.body.appendChild(tt);
				tt.style.opacity = 0;
				tt.style.filter = 'alpha(opacity=0)';
				document.onmousemove = this.pos;
			}
			tt.style.display = 'block';
			c.innerHTML = v;
			tt.style.width = w ? w + 'px' : 'auto';
			if(!w && ie){
				t.style.display = 'none';
				b.style.display = 'none';
				tt.style.width = tt.offsetWidth;
				t.style.display = 'block';
				b.style.display = 'block';
			}
			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
			h = parseInt(tt.offsetHeight) + top;
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(1)},timer);
		},
		pos:function(e){
			var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
			tt.style.top = (u - h) + 'px';
			tt.style.left = (l + left) + 'px';
		},
		fade:function(d){
			var a = alpha;
			if((a != endalpha && d == 1) || (a != 0 && d == -1)){
				var i = speed;
				if(endalpha - a < speed && d == 1){
					i = endalpha - a;
				}else if(alpha < speed && d == -1){
					i = a;
				}
				alpha = a + (i * d);
				tt.style.opacity = alpha * .01;
				tt.style.filter = 'alpha(opacity=' + alpha + ')';
			}else{
				clearInterval(tt.timer);
				if(d == -1){tt.style.display = 'none'}
			}
		},
		hide:function(){
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tooltip.fade(-1)},timer);
		}
	};
}();