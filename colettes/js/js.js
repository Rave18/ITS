function edit(str,form){
	if (str == "") {
	    document.getElementById("ajax").innerHTML = "";
	    return;
	} else { 
	    if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("ajax").innerHTML = xmlhttp.responseText;
	        }
	    };
	    xmlhttp.open("GET","ajax/ajaxowner.php?edit&val="+str+"&form="+form,true);
	    xmlhttp.send();
		$("#modaltrig").click();
	}
}

function change(value,total){
	if (value == "" || value == '0') {
	    document.getElementById("xchange").innerHTML = "Change: ";
	    return;
	} else {
		if(total > value){
			alert("Not enough cash.");
			document.getElementById("xchange").innerHTML = "Change: ";
		}else{
			document.getElementById("xchange").innerHTML = "Change: " + (value - total);
		}
	}
}

function addx(form){
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("ajax").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?add&form="+form,true);
    xmlhttp.send();
	$("#modaltrig").click();
}

function branch_inve(){	
	str = $("select[name='branch']").val();
	search = $("input[name='search']").val();
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("b_inve").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?branch_inve="+str+"&search="+search,true);
    xmlhttp.send();
}

function b_sales(str){	
	br = $("select[name='br']").val();
	frd = $("input[name='frd']").val();
	tod = $("input[name='tod']").val();
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("b_salesx").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?b_sales="+br+"&frd="+frd+"&tod="+tod+"&rep="+str,true);
    xmlhttp.send();
}

function b_inve(){	
	br = $("select[name='brx']").val();
	frd = $("input[name='frdx']").val();
	tod = $("input[name='todx']").val();
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("b_invex").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?b_inve="+br+"&frdx="+frd+"&todx="+tod,true);
    xmlhttp.send();
}

function recipex(strx,val){
	str = $("select[name='recipe']").val();
	if(str == "" && strx != ""){
		str = strx;
	}
	if(str == ""){
		document.getElementById("a_recipe").innerHTML = "";
	}else{
		qty = $("input[name='qty']").val();
		if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("a_recipe").innerHTML = xmlhttp.responseText;
	            if($("#red").val() == 1){
			    	$("button[name='create'").attr('disabled',true); 
			    }else{
			    	$("button[name='create'").attr('disabled',false); 
			    }
	        }
	    };
	    xmlhttp.open("GET","ajax/ajaxowner.php?recipe="+str+"&qty="+qty,true);
	    xmlhttp.send();
	}
}

function sales(str){
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("ajax").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?sales="+str,true);
    xmlhttp.send();
	$("#modaltrig").click();
}

function cart(str){
	if(str == ""){
		document.getElementById("ajax").innerHTML = "";
	}else{
		qty = $("input[name='qty"+str+"']").val();
		price = $("input[name='price"+str+"']").val();
		if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("cart").innerHTML = xmlhttp.responseText;
	        }
	    };
	    xmlhttp.open("GET","ajax/ajaxowner.php?cart="+str+"&qty="+qty+"&price="+price,true);
	    xmlhttp.send();
	}
}

function checkout(str,sales){
	if($("input[name='tendered']").val() > 0){
		if(str == ""){
			//document.getElementById("ajax").innerHTML = "";
		}else{
			if (window.XMLHttpRequest) {
		        // code for IE7+, Firefox, Chrome, Opera, Safari
		        xmlhttp = new XMLHttpRequest();
		    } else {
		        // code for IE6, IE5
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    xmlhttp.onreadystatechange = function() {
		        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		            document.getElementById("cart").innerHTML = xmlhttp.responseText;
		            //ocument.getElementById("cart").innerHTML = "";
		        }
		    };
		    xmlhttp.open("GET","ajax/ajaxowner.php?checkout="+str,true);
		    window.open('main/print?id=' + sales + '&tendered=' + $("input[name='tendered']").val(),'_blank');
		    xmlhttp.send();
		}
	}else{
		alert("Enter tendered amount");
		$("input[name='tendered']").focus();
	}
}

function clearcart(str){
	if(str == ""){
		//document.getElementById("ajax").innerHTML = "";
	}else{
		if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("cart").innerHTML = xmlhttp.responseText;
	            //ocument.getElementById("cart").innerHTML = "";
	        }
	    };
	    xmlhttp.open("GET","ajax/ajaxowner.php?clearcart="+str,true);
	    xmlhttp.send();
	}
}


function prodlist(str,prod){
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("prodlist").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?prodlist="+str+"&recipexx="+prod,true);
    xmlhttp.send();
}

function r_search(str){	
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("r_search").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?r_search="+str,true);
    xmlhttp.send();
	
}

function prodx_inve(str){	
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("prodx_inve").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?prodx_inve="+str,true);
    xmlhttp.send();
	
}

function bod_sales(str){	
	frd = $("input[name='frd']").val();
	tod = $("input[name='tod']").val();
	if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("bod_salesx").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","ajax/ajaxowner.php?bod_sales="+str+"&frd="+frd+"&tod="+tod+"&rep="+str,true);
    xmlhttp.send();	
}