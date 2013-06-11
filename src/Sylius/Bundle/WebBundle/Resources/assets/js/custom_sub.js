
$(document).ready(function(){

   //子页banner循环		
   $.featureList($("#tabs li a"),$("#output li"), {start_item:0});
   
   //全部分类鼠标滑过时显示 
	$('#nav-sub').hover(function(){
		$(this).addClass('nav-sub-bb')
			.siblings().removeClass('nav-sub-bb');
		$('#nav-sub ul.nav-list').hide();
		$(this).find('ul.nav-list').show();
	},function(){
		$(this).removeClass('nav-sub-bb')
		$(this).find('ul.nav-list').hide();
	});
	//列表 hover
	$('.list-sub li').hover(function(){
		$(this).addClass('hover')
			.siblings().removeClass('hover');
		$(this).find('.sublist-view').show().end()
			.siblings().find('.sublist-view').hide();
	},function(){
		$(this).removeClass('hover')
			.find('.sublist-view').hide();
	});
	
	//最终页 配送至
	$('div.sendto').hover(function(){
		$(this).parent().addClass('hover')
		.siblings().removeClass('hover');
		});	
	$('.close').click(function(e){
		e.preventDefault();
		$('#select-area').removeClass('hover');
	});
	
	//最终页tab轮播
   $(".tab h3").click(function() {
	var that = $(this).closest(".tab");
	var current =  that.find("h3").index(this);
	that.find("h3").removeClass("current");
	$(this).addClass("current");
	var target = that.next();
	target.children("div").hide();
	target.children("div").eq(current).show();
	});
   
   
   $(function(){			
	   $(".jqzoom").jqueryzoom({
			xzoom:400,
			yzoom:400,
			position:"right",
			preload:1,
			lens:1
		});
		
		$("#spec-list img").bind("mouseover",function(){
			var src=$(this).attr("mimg");
			var src_b=$(this).attr("jqimg");
			$("#spec-n1 img").eq(0).attr({
				src:src,
				jqimg:src_b
			});
			
		});			
	});
	//shoppingcart sublist2,
	$(".sub-cart").click(function(e) {
		e.preventDefault();
		var img = $(this).parents('li').find("p.left img");
		var img2 = img.clone();
		//console.log(img);
		img2.attr('width',50);
		img2.attr('height',50);
		img2.addClass('cart-img');
		$(this).after(img2);
		var start_y = $(this).offset().top;
		var start_x = $(this).offset().left - 800;
		var end_y = $('.cart-side').offset().top;
		var end_x = $('.cart-side').offset().left;
		img2.animate({
			top: [end_y - start_y, 'easeInCubic'],
			left: [end_x - start_x, 'sin'],
			opacity: 0
		}, 1000);
	});
	//shoppingcart detail,
	$(".bt-cart").click(function(e) {
		e.preventDefault();
		var img = $(this).parents('.detail-text').prev().find("#spec-list img").eq(0);
		var img2 = img.clone();
		img2.attr('width',50);
		img2.attr('height',50);
		img2.addClass('cart-img-detail');
		//console.log(img2);
		$(this).after(img2);
		var start_y = $(this).offset().top;
		var start_x = $(this).offset().left;
		var end_y = $('.cart-side').offset().top;
		var end_x = $('.cart-side').offset().left;
		img2.animate({
			top: [end_y - start_y, 'easeInCubic'],
			left: [end_x - start_x, 'sin'],
			opacity: 0
		}, 800);
	});
})

	
