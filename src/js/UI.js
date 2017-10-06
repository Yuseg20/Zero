// JavaScript Document

function UI(){
	
	//元素大小自适应
	this.elResize = function(element){
		$(window).resize(function(e) {
            element.css({
				width: $(window).width() +'px',
				height: $(window).height() +'px'
			});
        });
	}
	
	//登录、注册界面
	this.login = function(element, fun){
		var el = $('<div></div>');
		
		//隔离层
		var $insulate = $('<div></div>');
		this.elResize($insulate);
		//属性
		$insulate.css({
			width: $(window).width() +'px',
			height: $(window).height() +'px',
			position: 'fixed',
			top: 0,
			left: 0,
			zIndex: 1,
			background: 'none'
		});
		//事件
		el.show = function(){
			$insulate.css('display', 'block');
		}
		el.hidden = function(){
			$insulate.css('display', 'none');
		}
		
		//登录窗口容器
		var $loginContainer = $('<div></div>');
		$loginContainer.css({
			width: 430 +'px',
			height: 350 +'px',
			position: 'relative',
			top: '50%',
			left: '50%',
			margin: '-150px 0 0 -215px',
			background: 'none'
		});
		//添加
		$loginContainer.appendTo($insulate);
		
		//背景
		var $loginContainerbg = $('<div></div>');
		$loginContainerbg.css({
			width: '100%',
			height: '100%',
			position: 'absolute',
			top: 0,
			left: 0,
			opacity: .5,
			background: '#000'
		});
		//添加
		$loginContainerbg.appendTo($loginContainer);
		
		//主体
		var $login = $('<div></div>');
		$login.css({
			width: 410 +'px',
			height: 330 +'px',
			position: 'absolute',
			top: '50%',
			left: '50%',
			margin: '-165px 0 0 -205px',
			background: '#FFF'
		});
		//添加
		$login.appendTo($loginContainer);
		
		//标题栏
		var $titleBar = $('<div></div>');
		$titleBar.css({
			width: '100%',
			height: 50 +'px',
			borderBottom: '2px solid #019060'
		});
		//添加
		$titleBar.appendTo($login);
		
		//登录选项卡
		$loginSelect = $('<a></a>');
		$loginSelect.text('登录');
		$loginSelect.css({
		});
		//添加
		$loginSelect.appendTo($titleBar);
				
		//DOM页面渲染
		$(element).append($insulate);
		
		//执行回调函数
		if(fun) fun(el);
	}
	
	//消息提示框
	this.messageDialog = function(obj,params){
		$container = $('<div></div>');
		$containerBg = $('<div></div>');
		$content = $('<span></span>');
		
		//提示框容器
		$container.css({
			'width':100+params.text.length*12 +'px',
			'height':'85px',
			'text-align':'center',
			'line-height':'85px',
			'position':'fixed',
			'top':'50%',
			'left':'50%',
			'margin':'-42.5px 0 0 '+ -(100+params.text.length*12/2) +'px',
			'border-radius':'5px',
			'overflow':'hidden'
		});
		//window.alert(params.text.length);
		
		//提示框容器背景
		$containerBg.css({
			'width':'100%',
			'height':'100%',
			'position':'absolute',
			'opacity':'.6',
			'background':'#000'
		});
		
		//内容
		var text = params.text ? params.text : '';
		$content.text(text);
		$content.css({
			'color':'#FFF',
			'position':'relative'
		});
		
		//组装
		$containerBg.appendTo($container);
		$content.appendTo($container);
		
		//页面渲染
		$(obj).append($container);
		
		//移除消息提示框
		var hiddenTime = params.hiddenTime ? params.hiddenTime : 3000
		window.setTimeout(function(){
			$container.animate({
				'opacity':'0'
			},{
				complete:function(){
					$container.remove();
				}
			});
		},hiddenTime);
	}
}
