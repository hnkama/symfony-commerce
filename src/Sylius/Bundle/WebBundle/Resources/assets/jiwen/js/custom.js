
$(document).ready(function(){
	//箭头显示
	$('.t-ads-wrap').hover(function(){
		$('.ta-pre, .ta-next').fadeIn(500);
	},function(){
		$('.ta-pre, .ta-next').fadeOut(500);
	});

	//向上，向下
	$('.ta-drug').toggle(function(){
		$('.top-ads').animate({'margin-top':'-58px'},600);
			$(this).removeClass('ta-drug-open');
			$(this).addClass('ta-drug-close');
	},function(){
		$('.top-ads').animate({'margin-top':'0px'},600);
			$(this).addClass('ta-drug-open');
			$(this).removeClass('ta-drug-close');
	});

//图片切换-横向
	var page = 1;
    var i = 3; //每版放3个图片

    //向后 按钮
    $("span.ta-next").click(function(){    //绑定click事件
	     var $parent = $(this).parents("div.t-ads-wrap");//根据当前点击元素获取到父元素
		 var $v_show = $parent.find("div.t-ads-wrap-inner"); //寻找到"视频内容展示区域"
		 var $v_content = $parent.find("div.t-ads-out"); //寻找到"视频内容展示区域"外围的DIV元素
		 var v_width_a = $v_show.find("a").width() ; //单个图片的宽度
		 var len = $v_show.find("a").length;
		 var page_count = Math.ceil(len / i) ;   //只要不是整数，就往大的方向取最小的整数
		 if( !$v_show.is(":animated") ){    //判断"视频内容展示区域"是否正在处于动画
			  if( page == page_count ){  //已经到最后一个版面了,如果再向后，必须跳转到第一个版面。
				$v_show.animate({ left : '0px'}, "slow"); //通过改变left值，跳转到第一个版面
				page = 1;
			}else{
				$v_show.animate({ left : '-='+v_width_a * i }, "slow");  //通过改变left值，达到每次换一个版面
				page++;
			 }
		 }
		 $parent.find("span.icon_ch").eq((page-1)).addClass("current").siblings().removeClass("current");
   });
    //往前 按钮
    $("span.ta-pre").click(function(){
	     var $parent = $(this).parents("div.t-ads-wrap");//根据当前点击元素获取到父元素
		 var $v_show = $parent.find("div.t-ads-wrap-inner"); //寻找到"视频内容展示区域"
		 var $v_content = $parent.find("div.t-ads-out"); //寻找到"视频内容展示区域"外围的DIV元素
		 var v_width = $v_content.width();
		 var v_width_a = $v_show.find("a").width() ; //单个图片的宽度
		 var len = $v_show.find("a").length;
		 var page_count = Math.ceil(len / i) ;   //只要不是整数，就往大的方向取最小的整数
		 if( !$v_show.is(":animated") ){    //判断"视频内容展示区域"是否正在处于动画
		 	 if( page == 1 ){  //已经到第一个版面了,如果再向前，必须跳转到最后一个版面。
				$v_show.animate({ left : '-='+v_width*(page_count-1) }, "slow");
				page = page_count;
			}else{
				$v_show.animate({ left : '+='+v_width_a * i }, "slow");
				page--;
			}
		}
		$parent.find("span.icon_ch").eq((page-1)).addClass("current").siblings().removeClass("current");
    });
//图片切换-纵向
	var page = 1;
    var k = 2; //每版放3个图片

    //向后 按钮
    $("span.next").click(function(){    //绑定click事件
	     var $parent = $(this).parents("div.side-inner");//根据当前点击元素获取到父元素
		 var $v_show = $parent.find("ul.si-rotete"); //寻找到"视频内容展示区域"
		 var $v_content = $parent.find("div.si-rotete-out"); //寻找到"视频内容展示区域"外围的DIV元素
		 var v_height_li = $v_show.find("li").height() ; //单个图片的宽度
		 var len = $v_show.find("li").length;
		 var page_count = Math.ceil(len / k) ;   //只要不是整数，就往大的方向取最小的整数
		 if( !$v_show.is(":animated") ){    //判断"视频内容展示区域"是否正在处于动画
			  if( page == page_count ){  //已经到最后一个版面了,如果再向后，必须跳转到第一个版面。
				$v_show.animate({ top : '0px'}, "slow"); //通过改变left值，跳转到第一个版面
				page = 1;
			}else{
				$v_show.animate({ top : '-='+v_height_li * k }, "slow");  //通过改变left值，达到每次换一个版面
				page++;
			 }
		 }
		 $parent.find("span.icon_ch").eq((page-1)).addClass("current").siblings().removeClass("current");
   });
    //往前 按钮
    $("span.prev").click(function(){
	     var $parent = $(this).parents("div.side-inner");//根据当前点击元素获取到父元素
		 var $v_show = $parent.find("ul.si-rotete"); //寻找到"视频内容展示区域"
		 var $v_content = $parent.find("div.si-rotete-out"); //寻找到"视频内容展示区域"外围的DIV元素
		 var v_height = $v_content.height();
		 var v_height_li = $v_show.find("li").height() ; //单个图片的宽度
		 var len = $v_show.find("li").length;
		 var page_count = Math.ceil(len / i) ;   //只要不是整数，就往大的方向取最小的整数
		 if( !$v_show.is(":animated") ){    //判断"视频内容展示区域"是否正在处于动画
		 	 if( page == 1 ){  //已经到第一个版面了,如果再向前，必须跳转到最后一个版面。
				$v_show.animate({ top : '-='+v_height*(page_count-1) }, "slow");
				page = page_count;
			}else{
				$v_show.animate({ top : '+='+v_height_li * k }, "slow");
				page--;
			}
		}
		$parent.find("span.icon_ch").eq((page-1)).addClass("current").siblings().removeClass("current");
    });
	
	//nav
	$('ul.nav-list > li').hover(function(){
		$('ul.nav-list > li #submenu-wrap').hide();
		$(this).find('#submenu-wrap').show();
		$(this).addClass('current')
			.siblings().removeClass('current');
	},function(){
		$(this).find('#submenu-wrap').hide();
		$(this).removeClass('current');
	});	
	//顶部sub nav
	$('.subnav-top .sn-drop').hover(function(){
		$('.subnav-top .sn-drop .subnav-drop').hide();
		$(this).find('.subnav-drop').show();
	},function(){
		$(this).find('.subnav-drop').hide();
	});	
	
	//index-subtitle-drop
	$('.sec-title h3').hover(function(){
		$('.sec-title h3 .st-drop').hide();
		$(this).find('.st-drop').show();
	},function(){
		$(this).find('.st-drop').hide();
	});	
	
	//列表 hover
	$('.list-index li').hover(function(){
		$(this).addClass('hover')
			.siblings().removeClass('hover');
		$(this).find('.inlist-view').show().end()
			.siblings().find('.inlist-view').hide();
	},function(){
		$(this).removeClass('hover')
			.find('.inlist-view').hide();
	});
	//两侧浮动
	$(window).scroll(function() {
		$('.flue')
		.stop()
		.animate({top: $(document).scrollTop() + 5},'slow','easeOutBack');
	});
	//返回顶部
	$(".to-top").click(function(){
                $('body,html').animate({scrollTop:0},600);
                return false;
            });
   //图片轮播
   $('.bn-cycle-inner').cycle({ 
    fx:     'fade', 
    speed:   300, 
    timeout: 3000,
    next:   '.arrow-next', 
    prev:   '.arrow-prev', 
	pause:   1 
			});
   
   //tab轮播
   $(".tab-switcher li").hover(function() {
	var that = $(this).closest(".tab-switcher");
	var current =  that.find("li").index(this);
	that.find("a").removeClass("tabactive");
	$(this).children('a').addClass("tabactive");
	var target = that.next();
	target.children("div").hide();
	target.children("div").eq(current).show();
	});
	
	//shopping cart
	jQuery.easing.sin = function(p, n, firstNum, diff) {
    return Math.sin(p * Math.PI / 2) * diff + firstNum;
	};
	jQuery.easing.cos = function(p, n, firstNum, diff) {
		return firstNum + diff - Math.cos(p * Math.PI / 2) * diff;
	};
	
	//shoppingcart curve in,
	$(".cart-index").click(function(e) {
		e.preventDefault();
		var img = $(this).parents('.inner').find(".list-img img");
		var img2 = img.clone();
		//console.log(img);
		img2.attr('width',50);
		img2.attr('height',50);
		img2.addClass('cart-img');
		$(this).after(img2);
		var start_y = $(this).offset().top - 270;
		var start_x = $(this).offset().left;
		var end_y = $('.cart-side').offset().top;
		var end_x = $('.cart-side').offset().left;
		img2.animate({
			top: [end_y - start_y, 'easeInCubic'],
			left: [end_x - start_x, 'sin'],
			opacity: 0
		}, 1000);
	});
	
})

