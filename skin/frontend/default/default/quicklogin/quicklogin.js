var checkurl = URL + "customer/account/signupformpopup/";
/* URL is define in header.phtml */
	function showForgotSection (it, box) {
			var vis = (box.checked) ? "block" : "none";
			document.getElementById(it).style.display = vis;
			}
	        if($('alogin')){
				T$('alogin').onclick = function()
				{
                TINY.box.show({url: checkurl ,width:620,height:100,opacity:20,topsplit:10});
				
				}
			} 
			 else if($('aclose')){
				T$('aclose').onclick = function()
				{
				TINY.box.alpha(p,-1,0,3)
				}
			}
                
		/*Ajax Login Function */
		function loginAjax() {		
			var valid = new Validation('login-form');
			 if(valid.validate()){
			    var request = new Ajax.Request(
				 URL + "customer/account/ajaxLogin",
				{
				    method:'post',
				    onComplete: function(){
				       
				    },
				    onSuccess: function(transport){
				       if (transport && transport.responseText){
					 try{
					    response = eval('(' + transport.responseText + ')');
					  }
					  catch (e) {
						response = {};
					  }
					}
					
					if (response.success){
					   alert('Successfully Loggedin');
					   redirectTime = "1";
                       redirectURL = URL;
					   setTimeout("location.href = redirectURL;",redirectTime);
					}else{
					    if ((typeof response.message) == 'string') {
						alert(response.message);
					    } 
					    return false;
					}
				    },
				    onFailure: function(){
				      alert("Failed");
				    },
				    parameters: Form.serialize('login-form')
				}
			      );
			  }else{
			 
			    return false;
			  }
			  
		}	
            /*Ajax Register Customer Function */
                function registerAjax() {		
						
					 var valid = new Validation('regis-form');
					 if(valid.validate()){
						  var request = new Ajax.Request(
						URL + "customer/account/ajaxCreate",
						{
							method:'post',
							onComplete: function(){
							   
							},
							onSuccess: function(transport){
							   if (transport && transport.responseText){
							 try{
								response = eval('(' + transport.responseText + ')');
							  }
							  catch (e) {
								response = {};
							  }
							}
							
							if (response.success){
							       alert('Successfully Registered');
								   redirectTime = "1";
								   redirectURL = URL;
								   //redirectURL = URL + "customer/account";
								   setTimeout("location.href = redirectURL;",redirectTime);
							    }else{
								if ((typeof response.message) == 'string') {
								alert(response.message);
								} 
								return false;
							}
							},
							onFailure: function(){
							  alert("Failed");
							},
							parameters: Form.serialize('regis-form')
						}
						  );
					  }else{
					 
						return false;
					  }
			  
		        }	
		/*Forget Password Function */
		function forgetpass(){	
			var req2 = new Ajax.Request(URL + "customer/account/ajaxForgotPassword/",
			 {
				method:'post',
				parameters: $('forgot-form').serialize(true) ,
				onSuccess: function(transport){
				   var response = eval('(' + transport.responseText + ')');
				   if(response.success){
					  alert(response.message);
					  TINY.box.hide();
					 

				   }else{
					 alert(response.message);
				   }
				},
				onFailure: function(){ alert('Something went wrong...') }
			 });
 
        }