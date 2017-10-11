// JavaScript Document

function Processor(){
	
	//注册
	this.authRegister = function(){
		//请求标志
		var isRequest = false;
		
		$('#btn-now-register').click(function(e) {
			if(isRequest){
				return;
			}
			
			var regHint = $('#reg-hint');
            var username = $('#reg-txt-username').val();
			var nickname = $('#reg-txt-nickname').val();
			var password = $('#reg-txt-password').val();
			var rePassword = $('#reg-txt-re-password').val();
			
			//检测用户名
			var user = new RegExp('^[A-Za-z][A-Za-z0-9_]*$', 'g');
			if(!user.test(username)){
				regHint.css({
					'color':'#F00'
				});
				regHint.text('用户名无效');
				
				return;
			}else{
				regHint.text('');
			}
			
			//检测密码
			var pwd = new RegExp('[A-Za-z0-9+-/*_]*', 'g');
			if(password != rePassword){
				regHint.css({
					'color':'#F00'
				});
				regHint.text('密码不一致');
				
				return;
			}else{
				if(password.length < 6 || password.length > 20){
					regHint.css({
						'color':'#F00'
					});
					regHint.text('密码长度 6~20 位');
					
					return;
				}else if(!pwd.test(password)){
					regHint.css({
						'color':'#F00'
					});
					regHint.text('密码无效');
					
					return;
				}
				
				regHint.text('');
			}
			
			//切换请求状态
			isRequest = true;
			
			//发送注册请求
			var sendData = {"q":'zero/auth/register',
							"username":username,
							"nickname":nickname,
							"password":password,
							"rePassword":rePassword};
			$.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType:"json",
				success: function(data, status){
					//用户注册
					if(data.type == 'user_register'){
						if(data.status == '0'){
							//注册成功
							
							//显示提示框
							ui.messageDialog('body',{
								text: '欢迎来到 Zero 社区'
							});
							
							//隐藏组件面板
							$('#com').css('display', 'none');
										
							//切换用户显示状态
							$('#logout').css({
								'display':'none'
							});
							
							$('#logined').css({
								'display':'block'
							});
						}else if(data.status == '-1'){
							//用户名已存在
							regHint.css({
								'color':'#F00'
							});
							regHint.text('用户名已存在');
							
							//切换请求状态
							isRequest = true;
						}else if(data.status == '-2'){
							//注册失败
							regHint.css({
								'color':'#F00'
							});
							regHint.text('注册失败');
							
							//切换请求状态
							isRequest = true;
						}
					}
				},
				error:function(xhr){
					regHint.css({
						'color':'#F00'
					});
					regHint.text('请求出错');
					
					//切换请求状态
					isRequest = true;
				}
			});
        });
	}
	
	//登录
	this.authLogin = function(){
		//请求标志
		var isRequest = false;
		
		function authLogin(){
			if(isRequest){
				return;
			}
			
			var loginHint = $('#login-hint');
            var username = $('#login-txt-username').val();
			var password = $('#login-txt-password').val();
			
			//切换按钮状态样式
			function changeBtn(style){
				if(style == 0){//正常样式
					//切换按钮样式
					$('#btn-login').html('登&nbsp;&nbsp;&nbsp;&nbsp;录');
					$('#btn-login').css({
						'background-image':'none'
					});
				}else{//请求中样式
					//切换按钮样式
					$('#btn-login').html('');
					$('#btn-login').css({
						'background-image':'url('+ webDName + webRoot +'/src/images/loading.gif)'
					});
				}
			}
			
			//检测用户名
			var user = new RegExp('^[A-Za-z][A-Za-z0-9_]*$', 'g');
			if(!user.test(username)){
				loginHint.css({
					'color':'#F00'
				});
				loginHint.text('用户名无效');
				
				return;
			}else{
				loginHint.text('');
			}
			
			//检测密码
			var pwd = new RegExp('[A-Za-z0-9+-/*_]*', 'g');
			if(password.length < 6 || password.length > 20){
				loginHint.css({
					'color':'#F00'
				});
				loginHint.text('密码长度 6~20 位');
				
				return;
			}else if(!pwd.test(password)){
				loginHint.css({
					'color':'#F00'
				});
				loginHint.text('密码无效');
				
				return;
			}
			
			//切换请求状态
			isRequest = true;
			//切换按钮状态
			changeBtn(1);
			
			//发送登录请求
			var sendData = {"q":'zero/auth/login',
							"username":username,
							"password":password};
			$.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType:"json",
				success: function(data, status){
					//用户登录
					if(data.type == 'user_login'){
						if(data.status == '0'){
							//登录成功
							
							//显示提示框
							ui.messageDialog('body',{
								text: '登录成功'
							});
							
							//切换请求状态
							isRequest = false;
							//切换按钮状态
							changeBtn(0);
							
							//隐藏组件面板
							$('#com').css('display', 'none');
										
							//切换用户显示状态
							$('#logout').css({
								'display':'none'
							});
							
							$('#logined').css({
								'display':'block'
							});
						}else if(data.status == '-1'){
							//登录失败
							loginHint.css({
								'color':'#F00'
							});
							loginHint.text('登录失败，请检查用户名或密码');
							
							//切换请求状态
							isRequest = false;
							//切换按钮状态
							changeBtn(0);
						}
					}
				},
				error:function(xhr){
					loginHint.css({
						'color':'#F00'
					});
					loginHint.text('请求出错');
					
					//切换请求状态
					isRequest = false;
					//切换按钮状态
					changeBtn(0);
				}
			});
		}
		
		$('#login-txt-username').keypress(function(e) {
            //回车键
			if(e.keyCode == 13){
				authLogin();
			}
        });
		
		$('#login-txt-password').keypress(function(e) {
            //回车键
			if(e.keyCode == 13){
				authLogin();
			}
        });
		
		$('#btn-login').click(function(e) {
			authLogin();
        });
	}
	
	//注销
	this.logout = function(){
		//请求标志
		var isRequest = false;
		
		//退出单击事件
		$('#logined .logout').click(function(e) {
			if(isRequest){
				return false;
			}
			
			//切换请求状态
			isRequest = true;
			
			//待发送数据
			var sendData = {"q":'zero/auth/logout'};
			
			//发送数据
			$.ajax({
				url: webDName + webRoot +"/index.php",
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					if(data.type == 'user_logout'){
						if(data.status == 0){
							//注销成功
							
							//消息提示
							ui.messageDialog('body',{
								text: '注销成功'
							});
							
							//切换请求状态
							isRequest = false;
							
							//切换用户显示状态
							$('#logined').css('display', 'none');
							$('#logout').css('display', 'block');
						}
					}
				},
				error:function(xhr){
					window.alert('Request Error');
					//切换请求状态
					isRequest = false;
				}
			});
			
			return false;
        });
	}
	
	//我要提问
	this.ask = function(){
		//请求标志
		var isRequest = false;
		
		//我要提问单击事件
		$('#btn-ask').click(function(e) {
            if(isRequest){
				return;
			}
			
			//待发送数据
			var sendData = {"q":'zero/article/checkcurrentuser'};
        });
	}
}
