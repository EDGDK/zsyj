
function login(){
	var username = $('#username').val();
	var password = $('#password').val();
	var userCode = $("#userCode").val();
	if(username == '' || username == null){
		alert("用户名不能为空！");
		$('#username').focus();
		return;
	}
	if(password == '' || password == null){
		alert("密码不能为空！");
		$('#password').focus();
		return;
	}
	if(userCode == '' || userCode == null){
		alert("验证码不能为空！");
		$('#userCode').focus();
		return;
	}
	$.ajax({
		url:loginUrl,
		type:"post", 
		dataType:"text",
		data:"username="+username+"&password="+password+"&userCode="+userCode,
		success:function(data){
			if('failure'==data){
			   $('#username').val("");
			   $('#password').val("");
			   $('#userCode').val("");
			   $('#certImg').click();
			   alert("用户名或密码错误！");
		   }else if("codeWrong"==data){
			   $('#userCode').val("");
			   $('#certImg').click();
			   alert("验证码错误！");
		   }else if('success' == data){
			   window.location.href = userUrl;
		   }
		}, 
		error:function(data){
			alert("登陆失败！");
		}
	});
}

function SubmitKey(button,event)  
{   
	if (event.keyCode == 13)  
	{   
		event.returnValue = false;  
		login();
	}
}

