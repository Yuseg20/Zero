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
				}else if(!pwd.test(password) || $.trim(password).length == 0){
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
							yui.messageDialog('body',{
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
							
							//切换请求状态
							isRequest = false;
						}else if(data.status == '-1'){
							//用户名已存在
							regHint.css({
								'color':'#F00'
							});
							regHint.text('用户名已存在');
							
							//切换请求状态
							isRequest = false;
						}else if(data.status == '-2'){
							//注册失败
							regHint.css({
								'color':'#F00'
							});
							regHint.text('注册失败');
							
							//切换请求状态
							isRequest = false;
						}
					}
				},
				error:function(xhr){
					regHint.css({
						'color':'#F00'
					});
					regHint.text('请求出错');
					
					//切换请求状态
					isRequest = false;
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
							yui.messageDialog('body',{
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
							yui.messageDialog('body',{
								text: '注销成功'
							});
							
							//切换请求状态
							isRequest = false;
							
							//切换用户显示状态
							$('#logined').css('display', 'none');
							$('#logout').css('display', 'block');
							
							//跳转至主页
							if(typeof(nowPage) != 'undefined'){
								window.location.href=webDName + webRoot;
							}
						}
					}
				},
				error:function(xhr){
					//切换请求状态
					isRequest = false;
				}
			});
			
			return false;
        });
	}
	
	//个人中心四项功能
	this.personOpt = function(){
		var isRequest = null;
		var loadingDialog = null;
		var messageDialog = null;
		var isRequesting = false;
		
		//绑定发布事件
		function bindPublish(){
			$('#btn-publish').click(function(e) {
				//获取标题和描述
				var title = $('#ask-title').val();
				var desc = $('#ask-desc').val();
				
				//请求中拦截
				if(isRequesting){
					return;
				}
				
				//移除上一个消息提示框
				if(messageDialog){
					messageDialog.remove();
				}
				
				//移除上一个加载提示框
				if(loadingDialog){
					loadingDialog.remove();
				}
				
				//检测标题和描述
				if($.trim(title) == ''){
					messageDialog = yui.messageDialog('body',{
						text: '标题不能为空'
					});
					return;
				}else if(title.length > 20){
					messageDialog = yui.messageDialog('body',{
						text: '标题长度不能超过 20 个字符'
					});
					return;
				}else if(desc.length > 600){
					messageDialog = yui.messageDialog('body',{
						text: '描述长度不能超过 600 个字符'
					});
				}

				//显示加载提示框
				loadingDialog = yui.loadingDialog('body');
				
				//改变请求中状态
				isRequesting = true;
				
				//发送保存请求
				var sendData = {
									"q":"zero/person/publish",
									"title": title,
									"desc": desc
							  	};
				isRequest = $.ajax({
					url: webDName + webRoot +'/index.php',
					type: 'POST',
					data: sendData,
					dataType: 'json',
					success: function(data, status){
						//移除加载提示框
						loadingDialog.hidden();
						
						if(data.status == 0){
							//发布成功
							
							getAsk();
							
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '发布成功'
							});
							
							//改变请求中状态
							isRequesting = false;
						}else if(data.status == -1){
							//用户未登录
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '请先登录'
							});
							
							//改变请求中状态
							isRequesting = false;
						}else if(data.status == -2){
							//发布失败
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '发布失败'
							});
							
							isRequesting = false;
						}
					},
					error: function(jqXHR, status, error){
						if(status == 'abort'){
							return;
						}
						
						//移除加载提示框
						loadingDialog.hidden();
						
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请求出错'
						});
						
						//改变请求中状态
						isRequesting = false;
					}
				});
			});
		}
		
		//绑定我要提问事件
		function bindAsk(){
			$('#btn-ask').click(function(e) {				
				//终止之前所有请求
				if(isRequest){
					isRequest.abort();
				}
				
				//移除上一个消息提示框
				if(messageDialog){
					messageDialog.remove();
				}
				
				//移除上一个加载提示框
				if(loadingDialog){
					loadingDialog.remove();
				}

				//显示加载提示框
				loadingDialog = yui.loadingDialog('body');
				
				//发送保存请求
				var sendData = {
									"q":"zero/person/getCreateAsk"
							  	};
				isRequest = $.ajax({
					url: webDName + webRoot +'/index.php',
					type: 'POST',
					data: sendData,
					dataType: 'json',
					success: function(data, status){
						//移除加载提示框
						loadingDialog.hidden();
						
						if(data.status == 0){
							//获取成功
							
							$('#person-right').html(data.content);
							
							//绑定发布事件
							bindPublish();
						}else if(data.status == -1){
							//用户未登录
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '请先登录'
							});
						}else if(data.status == -2){
							//修改失败
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '保存失败'
							});
						}
					},
					error: function(jqXHR, status, error){
						if(status == 'abort'){
							return;
						}
						
						//移除加载提示框
						loadingDialog.hidden();
						
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请求出错'
						});
					}
				});
			});
		}
		
		//我的提问分页显示
		function myAskPageShow(page){
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求我的回答页面
			var sendData = {
								"q":"zero/person/getMyAskList",
								"page":page
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功,列表替换
						$('#ask-list').html(data.content);
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){					
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
		}
		
		//获取提问
		function getAsk(){
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求基本信息页面
			var sendData = {"q":"zero/person/getMyAsk"};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						$('#person-right').html(data.content);
						
						//绑定我要提问事件
						bindAsk();
						
						//分页组件
						yui.paging('#paging',{
							pagesize:data.pagesize,
							page:function(obj){
								myAskPageShow(obj.current);
							},
							prev:function(obj){
								myAskPageShow(obj.current);
							},
							next:function(obj){
								myAskPageShow(obj.current);
							}
						});
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){					
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
		}
		
		getAsk();
		
		//我的提问
		$('#person-my-ask').click(function(e) {
            getAsk();
        });
		
		//我的回答分页显示
		function myAnswerPageShow(page){
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求我的回答页面
			var sendData = {
								"q":"zero/person/getMyAnswerList",
								"page":page
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功,列表替换
						$('#answer-list').html(data.content);
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
		}
		
		//我的回答
		$('#person-my-answer').click(function(e) {
            //终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求我的回答页面
			var sendData = {"q":"zero/person/getMyAnswer"};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						$('#person-right').html(data.content);
						
						//分页组件
						yui.paging('#paging',{
							pagesize:data.pagesize,
							page:function(obj){
								myAnswerPageShow(obj.current);
							},
							prev:function(obj){
								myAnswerPageShow(obj.current);
							},
							next:function(obj){
								myAnswerPageShow(obj.current);
							}
						});
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){					
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
		
		//绑定保存个人基本信息事件
		function bindSaveInfo(){
			$('#btn-save-info').click(function(e) {
				var nickname = $('#info-nickname').val();
				
				//终止之前所有请求
				if(isRequest){
					isRequest.abort();
				}
				
				//移除上一个消息提示框
				if(messageDialog){
					messageDialog.remove();
				}
				
				//移除上一个加载提示框
				if(loadingDialog){
					loadingDialog.remove();
				}
				
				//昵称空间超限提示
				if(nickname.length > 12){
					messageDialog = yui.messageDialog('body',{
						text: '昵称不能超过 12 个字符'
					});
					return;
				}
				
				//显示加载提示框
				loadingDialog = yui.loadingDialog('body');
				
				//发送保存请求
				var sendData = {
									"q":"zero/person/saveBaseInfo",
									"nickname":nickname
							  	};
				isRequest = $.ajax({
					url: webDName + webRoot +'/index.php',
					type: 'POST',
					data: sendData,
					dataType: 'json',
					success: function(data, status){
						//移除加载提示框
						loadingDialog.hidden();
						
						if(data.status == 0){
							//修改成功
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '保存成功'
							});
						}else if(data.status == -1){
							//用户未登录
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '请先登录'
							});
						}else if(data.status == -2){
							//修改失败
							//显示消息提示框
							messageDialog = yui.messageDialog('body',{
								text: '保存失败'
							});
						}
					},
					error: function(jqXHR, status, error){
						if(status == 'abort'){
							return;
						}
						
						//移除加载提示框
						loadingDialog.hidden();
						
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请求失败'
						});
					}
				});
			});
		}
		
		//基本信息
		$('#person-base-info').click(function(e) {
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求基本信息页面
			var sendData = {"q":"zero/person/getBaseInfo"};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						$('#person-right').html(data.content);
						
						//绑定保存个人基本信息事
						bindSaveInfo();
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){					
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
		
		//绑定保存修改密码事件
		function bindSavePassword(){
			$('#btn-save-password').click(function(e) {
				var oldPassword = $('#old-password');
				var newPassword1 = $('#new-password-1');
				var newPassword2 = $('#new-password-2');
				
				//终止上一个Ajax请求
				if(isRequest){
					isRequest.abort();
				}
				
				//移除上一个消息提示框
				if(messageDialog){
					messageDialog.remove();
				}
				
				//移除上一个加载提示框
				if(loadingDialog){
					loadingDialog.remove();
				}
				
				//检测密码有效性
				var pwd = new RegExp('[A-Za-z0-9+-/*_]*', 'g');
				var pwd1 = oldPassword.val();
				var pwd2 = newPassword1.val();
				var pwd3 = newPassword2.val();
				if(!pwd.test(pwd2) || $.trim(pwd1).length == 0 || $.trim(pwd2).length == 0 || $.trim(pwd3).length == 0){
					messageDialog = yui.messageDialog('body',{
						text: '密码无效'
					});
					return;
				}else if(pwd2 != pwd3){
					messageDialog = yui.messageDialog('body',{
						text: '新密码不一致'
					});
					
					return;
				}else if(pwd2.length < 6 || pwd3.length > 20){
					messageDialog = yui.messageDialog('body',{
						text: '密码长度 6~20 位'
					});
					
					return;
				}
				
				//显示加载提示框
				loadingDialog = yui.loadingDialog('body');
				
				//发送修改请求
				var sendData = {
									"q":"zero/person/savePassword",
									"oldPwd": pwd1,
									"newPwd": pwd2
								};
				isRequest = $.ajax({
					url: webDName + webRoot +'/index.php',
					type: 'POST',
					data: sendData,
					dataType: "json",
					success: function(data, status){
						//移除加载提示框
						loadingDialog.hidden();
						
						if(data.status == 0){
							//修改成功
							messageDialog = yui.messageDialog('body',{
								text: '保存成功'
							});
						}else if(data.status == -1){
							//未登录
							messageDialog = yui.messageDialog('body',{
								text: '请先登录'
							});
						}else if(data.status == -2){
							//旧密码错误
							messageDialog = yui.messageDialog('body',{
								text: '旧密码有误'
							});
						}else if(data.status == -3){
							//修改失败
							messageDialog = yui.messageDialog('body',{
								text: '保存失败'
							});
						}
					},
					error: function(jqXHR, status, error){
						if(status == 'abort'){
							return;
						}
						
						//移除加载提示框
						loadingDialog.hidden();
						
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请求失败'
						});
					}
				});
			});
		}
		
		//密码修改
		$('#person-pwd-update').click(function(e) {
			//终止之前所有请求
			if(isRequest != null){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求密码修改页面
			var sendData = {"q":"zero/person/getPasswordUpdate"};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						$('#person-right').html(data.content);
						
						//绑定保存修改密码事件
						bindSavePassword();
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求
					if(status == 'abort'){
						return;
					}
					
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
	}
	
	//提交答案
	this.submitAnswer = function(){
		var loadingDialog = null;
		var messageDialog = null;
		var isRequesting = false;
		
		$('#btn-submit-answer').click(function(e) {
			if(isRequesting){
				return;
			}
			
			var txt = "";
			var html = "";
			ue.ready(function(){
				//获取答案内容
				txt = ue.getContentTxt();
				html = ue.getContent();
			});
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			if($.trim(txt) == ''){
				//显示消息提示框
				messageDialog = yui.messageDialog('body',{
					text: '请填写答案'
				});
				
				return;
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求密码修改页面
			var sendData = {
								"q":"zero/Article/submitAnswer",
								"aid":aid,
								"content":html
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '感谢您的分享'
						});
					}else if(data.status == -1){
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请先登录'
						});
					}else if(data.status == -2){
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '提交失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求
					if(status == 'abort'){
						return;
					}
					
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
	}
	
	//答案分页
	this.answerPaging = function(pagesize){
		var isRequest = null;
		var loadingDialog = null;
		var messageDialog = null;
		
		//答案分页显示
		function answerPageShow(page){
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求我的回答页面
			var sendData = {
								"q":"zero/Article/getAnswerPage",
								"aid":aid,
								"page":page
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功,列表替换
						$('#ul-answer-show').html(data.content);
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
		}
		
		//分页组件
		yui.paging('#paging',{
			pagesize:pagesize,
			page:function(obj){
				answerPageShow(obj.current);
			},
			prev:function(obj){
				answerPageShow(obj.current);
			},
			next:function(obj){
				answerPageShow(obj.current);
			}
		});
	}
	
	//更新浏览量
	this.updatePV = function(){
		//Ajax更新页面浏览量
		var sendData = {
							"q":"zero/Article/updatePV",
							"aid":aid
						};
		isRequest = $.ajax({
			url: webDName + webRoot +'/index.php',
			type: 'POST',
			data: sendData
		});
	}
	
	//在线用户统计
	this.onlines = function(){
		//获取在线用户数
		var sendData = {
							"q":"zero/Index/getOnlines"
						};
		isRequest = $.ajax({
			url: webDName + webRoot +'/index.php',
			type: 'POST',
			data: sendData,
			dataType: "json",
			success: function(data, status){
				if(data.status == 0){
					if(typeof(counter) != 'undefined'){
						counter.update(parseInt(data.onlines));
					}
				}
			},
			error: function(jqXHR, status, error){
			}
		});
	}
	
	//随机切换问答
	this.randChange = function(){
		var loadingDialog = null;
		var messageDialog = null;
		var isRequesting = false;
		
		$('#rand-change').click(function(e) {
			if(isRequesting){
				return;
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
			var sendData = {
								"q":"zero/Index/getRandAsk"
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功
						$('#rand-ask-list').html(data.content);
						//window.alert(data.content);
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '获取数据失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求
					if(status == 'abort'){
						return;
					}
					
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
	}
	
	//我要提问
	this.indexAsk = function(){
		var loadingDialog = null;
		var messageDialog = null;
		var isRequesting = false;
		
		$('#btn-ask').click(function(e) {
			if(isRequesting){
				return;
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
			var sendData = {
								"q":"zero/Index/wantAsk"
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//已登录
						window.location.href = webDName + webRoot +'?q=zero/person/index&target=ask';
					}else{
						//未登录
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '请先登录'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求
					if(status == 'abort'){
						return;
					}
					
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
        });
	}
	
	//搜索
	this.aSearch = function(){
		function sousuo(keyword){
			window.location.href = webDName + webRoot +'?q=zero/index/search&keyword='+ keyword;
		}
		
		$('#txt-search').keypress(function(e){
			if(e.keyCode == 13){
				sousuo($(this).val());
			}
		});
		
		$('#btn-search').click(function(e) {
            sousuo($('#txt-search').val());
        });
	}
	
	//搜索结果分页
	this.searchPaging = function(keyword){
		var isRequest = null;
		var loadingDialog = null;
		var messageDialog = null;
		
		//分页显示
		function pageShow(page){
			//终止上一个请求
			if(isRequest){
				isRequest.abort();
			}
			
			//移除上一个加载提示框
			if(loadingDialog){
				loadingDialog.remove();
			}
			
			//移除上一个消息提示框
			if(messageDialog){
				messageDialog.remove();
			}
			
			//显示新的加载提示框
			loadingDialog = yui.loadingDialog('body');
			
            //Ajax请求搜索结果页面
			var sendData = {
								"q":"zero/Index/ajaxSearch",
								"keyword":keyword,
								"page":page
							};
			isRequest = $.ajax({
				url: webDName + webRoot +'/index.php',
				type: 'POST',
				data: sendData,
				dataType: "json",
				success: function(data, status){
					//移除加载提示框
					loadingDialog.hidden();
					
					if(data.status == 0){
						//获取成功,列表替换
						$('#ul-s-result').html(data.content);
					}else{
						//获取失败
						//显示消息提示框
						messageDialog = yui.messageDialog('body',{
							text: '加载失败'
						});
					}
				},
				error: function(jqXHR, status, error){
					//用户终止上一个请求的错误
					if(status == 'abort'){
						return;
					}
					
					//其他错误
					//移除加载提示框
					loadingDialog.hidden();
					
					//显示消息提示框
					messageDialog = yui.messageDialog('body',{
						text: '请求出错'
					});
				}
			});
		}
		
		//分页
		yui.paging('#paging',{
			pagesize:pagesize,
			page:function(obj){
				pageShow(obj.current);
			},
			prev:function(obj){
				pageShow(obj.current);
			},
			next:function(obj){
				pageShow(obj.current);
			}
		});
	}
}
