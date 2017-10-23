// JavaScript Document

function Effect(){
	
	//组件自适应窗口
	this.comAuto = function(){
		$('#com').css({
			width: $(window).width() +'px',
			height: $(window).height() +'px'
		});
	}
	
	//导航栏阴影
	this.navShadow = function(){
		if($('body').scrollTop() > 0){
			$('#nav').stop();
			$('#nav').animate({
				'box-shadow':'0 0 10px 0 #000'
			},{
				duration: 30
			});
		}else{
			$('#nav').stop();
			$('#nav').animate({
				'box-shadow':'0 0 0 0'
			},{
				duration: 30
			});
		}
	}
	
	//认证组件
	this.authCom = function(){
		
		//登录注册切换
		function authSwitch(index){
			var displayFlag1 = "none";
			var displayFlag2 = "none";
			var selectFlag1 = "";
			var selectFlag2 = "";
			if(index == 0){
				//登录选项卡
				displayFlag1 = "block";
				displayFlag2 = "none";
				selectFlag1 = "#F60";
				selectFlag2 = "#009966";
			}else if(index == 1){
				//注册选项卡
				displayFlag1 = "none";
				displayFlag2 = "block";
				selectFlag1 = "#009966";
				selectFlag2 = "#F60";
			}
			
			//登录容器
			$('#login-container').css('display', displayFlag1);
			
			//注册容器
			$('#reg-container').css('display', displayFlag2);
						
			//切换选项卡状态
			$('#a-login-selection').css('border-bottom-color', selectFlag1);
			$('#a-reg-selection').css('border-bottom-color', selectFlag2);
		}
		
		//登录菜单单击事件
		$('#a-login-menu').click(function(e) {
			//重置所有内容
			$('#login-hint').text('');
			$('#login-txt-username').val('');
			$('#login-txt-password').val('');
			$('#reg-hint').text('');
			$('#reg-txt-username').val('');
			$('#reg-txt-nickname').val('');
			$('#reg-txt-password').val('');
			$('#reg-txt-re-password').val('');
			
			$('#com').css('display', 'block');
            $('#com').animate({
				'opacity': 1
			},{
				duration: 100
			});
			authSwitch(0);
			
			return false;
        });
		
		//注册菜单单击事件
		$('#a-reg-menu').click(function(e) {
			//重置所有输入框内容
			$('#login-hint').text('');
			$('#login-txt-username').val('');
			$('#login-txt-password').val('');
			$('#reg-hint').text('');
			$('#reg-txt-username').val('');
			$('#reg-txt-nickname').val('');
			$('#reg-txt-password').val('');
			$('#reg-txt-re-password').val('');
			
            $('#com').css('display', 'block');
            $('#com').animate({
				'opacity': 1
			},{
				duration: 100
			});
			authSwitch(1);
			
			return false;
        });
		
		//登录选项卡单击事件
		$('#a-login-selection').click(function(e) {
            authSwitch(0);
			
			return false;
        });
		
		//注册选项卡单击事件
		$('#a-reg-selection').click(function(e) {
            authSwitch(1);
			
			return false;
        });
		
		//隔离层单击事件
		$('#com').click(function(e) {
			$(this).stop();
            $(this).animate({
				'opacity': 0,
			},{
				duration: 100,
				complete: function(){
					$(this).css('display', 'none');
				}
			});
        });
		
		$('#com .login').click(function(e) {
			//阻止单击认证面板关闭组件
			return false;
        });
	}
	
	//搜索页选项卡切换
	this.searchPageSelector = function(){
		var $selectors = $('#search-main .search-container .title-bar .selector');
		
		for(var i=0;i<$selectors.length;i++){
			$selectors.eq(i).attr('index', i);//设置索引
			$selectors.eq(i).click(function(e){
				//将所有选项卡样式清除
				$selectors.css({
					'color':'#000',
					'background':'#FFF'
				});
				
				//赋予选中选项卡样式
				$(this).css({
					'color':'#FFF',
					'background':'#009966'
				});
			});
		}
	}
	
	//内容页回答编辑器
	this.articlePageEdit = function(){
		ue = UE.getEditor('my-answer-edit',{
			initialFrameWidth:780,
			initialFrameHeight:300,
			autoHeightEnabled:false,//长文本使用滚动条
			enableAutoSave:false,//本地自动保存功能
			toolbars: [
				[
					'undo', //撤销
					'redo', //重做
					'bold', //加粗
					'indent', //首行缩进
					'italic', //斜体
					'underline', //下划线
					'strikethrough', //删除线
					'subscript', //下标
					'fontborder', //字符边框
					'superscript', //上标
					'formatmatch', //格式刷
					'source', //源代码
					'selectall', //全选
					'horizontal', //分隔线
					'removeformat', //清除格式
					'deletetable', //删除表格
					'cleardoc', //清空文档
					'insertparagraphbeforetable', //"表格前插入行"
					'searchreplace', //查询替换
					'justifyleft', //居左对齐
					'justifyright', //居右对齐
					'justifycenter', //居中对齐
					'justifyjustify', //两端对齐
					'forecolor', //字体颜色
					'backcolor', //背景色
					'insertorderedlist', //有序列表
					'insertunorderedlist', //无序列表
					'fullscreen', //全屏
					'directionalityltr', //从左向右输入
					'directionalityrtl', //从右向左输入
					'rowspacingtop', //段前距
					'rowspacingbottom', //段后距
					'imagenone', //默认
					'imageleft', //左浮动
					'imageright', //右浮动
					'imagecenter', //居中
					'lineheight', //行间距
					'inserttable', //插入表格
					'insertcode', //代码语言
					'fontfamily', //字体
					'fontsize', //字号
					'paragraph' //段落格式
				]
			]
		});
	}
	
	//个人中心选项卡切换
	this.personSelector = function(){
		$as = $('#ul-person-selector li a');
		
		for(var i=0;i<$as.length;i++){
			$as.eq(i).attr('index', i);
			$as.eq(i).click(function(e){
				$as.css({
					'color':'#000',
					'background':'none'
				});
				
				$(this).css({
					'color':'#009966',
					'background':'#F1F1F1'
				});
				
				return false;
			});
		}
	}
}
