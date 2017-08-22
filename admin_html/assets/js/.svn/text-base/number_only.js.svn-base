function numbersonly(e){
var unicode=e.charCode? e.charCode : e.keyCode
    
if (unicode!=8 ){ //if the key isn't the backspace key (which we should allow)
	if (unicode<48||unicode>57) //if not a number
	return false //disable key press
}
   
        

    
}

function formatCurrency(num) {
num = num.toString().replace(/\$|\,/g,'');
if(isNaN(num))
num = "0";
sign = (num == (num = Math.abs(num)));
num = Math.floor(num*100+0.50000000001);
cents = num%100;
num = Math.floor(num/100).toString();
if(cents<10)
cents = "0" + cents;
for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
num = num.substring(0,num.length-(4*i+3))+','+
num.substring(num.length-(4*i+3));




return (((sign)?'':'-') + 'Rp' + num + '.' + cents);
}

function Satuan()
{
    var vol=0;
    var jumlah=0;
    if(document.getElementById("vol").value!="")
    vol= Math.abs(document.getElementById("vol").value);
    else
        vol=1;
    jumlah=document.getElementById("jumlah").value;

    num = Math.abs(jumlah.toString().replace(/\$|\,/g,''));
    
    var b=0;
    b=num/vol;

    num=b;
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));

   document.getElementById("satuan").value=num;
}

function selisih(pagu1,nilai_kontrak1,sisa)
{
    //var c=document.getElementById(pagu).value;
    //alert(c);
    var pagu=0;
    var nilai_kontrak=0;
    if(document.getElementById(pagu1).value!="")
    pagu= Math.abs(document.getElementById(pagu1).value);
    else
        pagu=1;
    nilai_kontrak=document.getElementById(nilai_kontrak1).value;

    num = Math.abs(nilai_kontrak.toString().replace(/\$|\,/g,''));

    var b=0;
    b=pagu-num;
    if(pagu>num)
       hasil="";
    else hasil="-";

    num=b;
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));

    hasil+=num;
   document.getElementById(sisa).value=hasil;
}