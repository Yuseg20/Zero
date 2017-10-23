// JavaScript Document

function YUI(pluginRoot){
	
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
		var $container = $("<div></div>");
		var $containerBg = $('<div></div>');
		var $content = $('<span></span>');
		
		//提示框容器
		$container.css({
			'width':100+params.text.length*12 +'px',
			'height':'85px',
			'text-align':'center',
			'line-height':'85px',
			'position':'fixed',
			'top':'50%',
			'left':'50%',
			'margin':'-42.5px 0 0 '+ -(100+params.text.length*12)/2 +'px',
			'border-radius':'5px',
			'overflow':'hidden'
		});
		
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
		
		//移除消息提示框选项
		var delay = params.delay>=0 ? params.delay : 3000;
		window.setTimeout(function(){
			$container.animate({
				'opacity': 0
			},{
				complete: function(){
					$(this).remove();
				}
			});
		},delay);
		
		//返回操作标志
		return $container;
	}
	
	//加载提示框
	this.loadingDialog = function(obj,params){
		//延迟时间
		var delay = 0;
		if(params){
			delay = params.delay>=0 ? params.delay : delay;
		}
		
		var $container = $("<div></div>");
		
		//提示框容器
		$container.css({
			'width':'64px',
			'height':'30px',
			'position':'fixed',
			'top':'50%',
			'left':'50%',
			'margin':'-15px 0 0 -32px',
			'overflow':'hidden',
			'background':'url('+ pluginRoot +'/yui/images/loading.gif)'
		});
		
		//自定义事件
		//关闭消息提示框
		$container.hidden = function(){
			window.setTimeout(function(){
				$container.animate({
					'opacity': 0
				},{
					complete: function(){
						$(this).remove();
					}
				});
			},delay);
		};
		
		//页面渲染
		$(obj).append($container);
		
		//返回操作标志
		return $container;
	}
	
	//分页组件
	this.paging = function(selector, params){
		//------ 检测参数 ------
		if(!params || params.pagesize == 0){
			//无参数则返回
			return;
		}
		
		//------ 获取参数 ------
		var pagesize = params.pagesize;//总页数
		var prevEvent = params.prev ? params.prev : null;//上一页点击绑定对象
		var pageEvent = params.page ? params.page : null;//页码点击绑定对象
		var nextEvent = params.next ? params.next : null;//下一页点击绑定对象
		
		//------ 声明变量 ------
		var currentPage = 1;//当前页码
		var index = 0;//当前页码索引
		var pageNumStart = 0;//循环页码起始
		
		//------ 创建节点元素 ------
		var $ul = $('<ul></ul>');
		var $liPrev = $('<a href="javascript:void(0);" style="color:#999;"><li style="list-style-type:none">上一页</li></a>');
		var $liNext = $('<a href="javascript:void(0);" style="color:#999;"><li style="list-style-type:none">下一页</li></a>');

		//------ 初始化页码 ------
		var $liPages = new Array();
		var pageForStart = 0
		var pageForEnd = pagesize < 3 ? pagesize : 3;
		for(var i=pageForStart;i<pageForEnd;i++){
			var $liPage = $('<a href="javascript:void(0);" style="color:#999;"><li style="list-style-type:none">'+ (i+1) +'</li></a>');
			var color = '#999';
			var borderColor = '#999';
			var background = '#FFF';
			
			if((i+1) == currentPage){
				//激活当前页码显示状态
				color = '#FFF';
				borderColor = '#009966';
				background = '#009966';
			}
			
			$liPage.css({
				'display':'inline-block',
				'color':color,
				'font-size':'12px',
				'text-align':'center',
				'border':'1px solid '+ borderColor,
				'border-radius':'2px',
				'padding':'5px 10px',
				'margin':'0 0 0 5px',
				'background':background
			});
			$liPage.hover(function(){
				//鼠标移入
				if($(this).text() == currentPage) return;
				$(this).css({
					'color':'#009966',
					'border':'1px solid #009966',
					'background':'#FFF'
				});
			},function(){
				//鼠标移出
				if($(this).text() == currentPage) return;
				$(this).css({
					'color':'#999',
					'border':'1px solid #999',
					'background':'#FFF'
				});
			});
			
			$liPages[i] = $liPage;
			$liPages[i].appendTo($ul);
		}
		
		//------ 样式 ------
		
		//组件容器
		$ul.css({
			'height':'30px',
			'margin':'0 auto'
		});
		
		//上一页
		$liPrev.css({
			'display':'inline-block',
			'color':'#999',
			'font-size':'12px',
			'text-align':'center',
			'border':'1px solid #999',
			'border-radius':'2px',
			'padding':'5px 12px',
			'background':'#FFF'
		});
		$liPrev.hover(function(){
			//鼠标移入
			$(this).css({
				'color':'#009966',
				'border':'1px solid #009966',
				'background':'#FFF'
			});
		},function(){
			//鼠标移出
			$(this).css({
				'color':'#999',
				'border':'1px solid #999',
				'background':'#FFF'
			});
		});
		
		//下一页
		$liNext.css({
			'display':'inline-block',
			'color':'#999',
			'font-size':'12px',
			'text-align':'center',
			'border':'1px solid #999',
			'border-radius':'2px',
			'padding':'5px 12px',
			'margin':'0 0 0 5px',
			'background':'#FFF'
		});
		$liNext.hover(function(){
			//鼠标移入
			$(this).css({
				'color':'#009966',
				'border':'1px solid #009966',
				'background':'#FFF'
			});
		},function(){
			//鼠标移出
			$(this).css({
				'color':'#999',
				'border':'1px solid #999',
				'background':'#FFF'
			});
		});
		
		//------ 组件效果 ------
		
		//上一页效果
		function prevPage(){
			if(currentPage > 1) currentPage--;//当前页码
			if(currentPage > 2) pageNumStart = currentPage-2; else pageNumStart = 0;//循环页码起始
				
			if(currentPage == 1){
				//当前页为首页
				index = 0;
			}else if(currentPage > 1){
				//当前页大于 1 ，则激活状态为中间页码
				index = 1;
			}
			
			for(var i=0;i<$liPages.length;i++){
				pageNumStart++;
				$liPages[i].text(pageNumStart);
				
				//重置所有页码样式
				$liPages[i].css({
					'color':'#999',
					'border':'1px solid #999',
					'background':'#FFF'
				});
			}
			//切换预加载页码样式
			$liPages[index].css({
				'color':'#FFF',
				'border':'1px solid #009966',
				'background':'#009966'
			});
		}
		
		//页码效果
		function numPage(params){
			currentPage = params.pageNum;//当前页
			index = params.currentIndex;//当前页索引
			pageNumStart = pagesize <= 3 ? 0 : currentPage-2;//循环页码起始
			
			for(var i=0;i<$liPages.length;i++){
				pageNumStart++;
				$liPages[i].text(pageNumStart);

				//重置所有页码样式
				$liPages[i].css({
					'color':'#999',
					'border':'1px solid #999',
					'background':'#FFF'
				});
			}
			//切换预加载页码样式
			$liPages[index].css({
				'color':'#FFF',
				'border':'1px solid #009966',
				'background':'#009966'
			});
		}
		
		//下一页效果
		function nextPage(){
			if(currentPage < pagesize) currentPage++;//当前页码
			pageNumStart = pagesize <= 3 ? 0 : currentPage-2;//循环页码起始
			
			if(currentPage == pagesize){
				index = $liPages.length-1;//当前页为尾页
			}else if(currentPage < pagesize){
				//中间页
				index = Math.floor($liPages.length/2);
			}
			
			for(var i=0;i<$liPages.length;i++){
				pageNumStart++;
				$liPages[i].text(pageNumStart);

				//重置所有页码样式
				$liPages[i].css({
					'color':'#999',
					'border':'1px solid #999',
					'background':'#FFF'
				});
			}
			//切换预加载页码样式
			$liPages[index].css({
				'color':'#FFF',
				'border':'1px solid #009966',
				'background':'#009966'
			});
		}
		
		//------ 绑定事件 ------
		
		//页码点击事件
		for(var i=0;i<$liPages.length;i++){
			$liPages[i].attr('index', i);
			$liPages[i].click(function(e){
				//调用页码效果
				numPage({
					pageNum:parseInt($(this).text()),
					currentIndex:parseInt($(this).attr('index'))
				});
				if(pageEvent){
					pageEvent({
						current:currentPage
					});
				}
			});
		}

		//上一页点击事件
		$liPrev.click(function(e){
			prevPage();
			if(prevEvent){
				prevEvent({
					current:currentPage
				});
			}
		});
		
		//下一页点击事件
		$liNext.click(function(e){
			nextPage();
			if(nextEvent){
				nextEvent({
					current:currentPage
				});
			}
		});
		
		//------ 组装 ------
		$liPrev.prependTo($ul);
		$liNext.appendTo($ul);
		
		//a 连接
		$ul.find('a').css({
			'text-decoration':'none'
		});
		
		//------ 渲染 ------
		$(selector).append($ul);
		
		//------ 返回句柄 ------
		
	}
}
