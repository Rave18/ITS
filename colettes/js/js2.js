$(document).ready(function(){
	$('#new_cat').change(function(){
	    if($('#new_cat').is(":checked")){ 	        
	    	$("#category").hide();	        
	    	$("#new_catx").show();
	    	$("#category").attr('required',false);
	    	$("#new_catx").attr('required',true);
	    }else{
	    	$("#category").show();	        
	    	$("#new_catx").hide();
	    	$("#category").attr('required',true);
	    	$("#new_catx").attr('required',false);
	    }
	});
	$('select[name = "servicecomp"]').change(function() {
    	var selected = $(this).val();
    	if(selected == 'new'){
    		$('#sernew').show();
    		$('input[name = "email"]').attr('required',true); 
    		$('input[name = "cname"]').attr('required',true); 
      	}else{
      		$('#sernew').hide();
      		$('input[name = "email"]').attr('required',false); 
      		$('input[name = "cname"]').attr('required',false); 
      	}
    });
	$('input[type="text"]').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $('select[name = "recipe"]').change(function() {
		var selected = $(this).val();
		if(selected == '1'){
			$('#psets').show();
			$('input[name = "per_sets"]').attr('required',true); 
		}else{
			$('#psets').hide();
			$('input[name = "per_sets"]').attr('required',false);  
		}
	});
});

function change_x(){
	$('select[name = "recipe"]').click(function() {
		var selected = $(this).val();
		if(selected == 1){
			$('#psets').show();
			$('input[name = "per_sets"]').attr('required',true); 
		}else{
			$('#psets').hide();
			$('input[name = "per_sets"]').attr('required',false);  
		}
	});
}

function click_x(){
	$('#check').change(function(){
	    if($('#check').is(":checked")){ 	        
	    	$("#category").hide();	        
	    	$("#new_raw").show();
	    	$("#category").attr('required',false);
	    	$("#new_raw").attr('required',true);
	    }else{
	    	$("#category").show();	        
	    	$("#new_raw").hide();
	    	$("#category").attr('required',true);
	    	$("#new_raw").attr('required',false);
	    }
	});
}