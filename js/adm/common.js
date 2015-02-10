;(function($){$.browserTest=function(a,z){var u='unknown',x='X',m=function(r,h){for(var i=0;i<h.length;i=i+1){r=r.replace(h[i][0],h[i][1]);}return r;},c=function(i,a,b,c){var r={name:m((a.exec(i)||[u,u])[1],b)};r[r.name]=true;r.version=(c.exec(i)||[x,x,x,x])[3];if(r.name.match(/safari/)&&r.version>400){r.version='2.0';}if(r.name==='presto'){r.version=($.browser.version>9.27)?'futhark':'linear_b';}r.versionNumber=parseFloat(r.version,10)||0;r.versionX=(r.version!==x)?(r.version+'').substr(0,1):x;r.className=r.name+r.versionX;return r;};a=(a.match(/Opera|Navigator|Minefield|KHTML|Chrome/)?m(a,[[/(Firefox|MSIE|KHTML,\slike\sGecko|Konqueror)/,''],['Chrome Safari','Chrome'],['KHTML','Konqueror'],['Minefield','Firefox'],['Navigator','Netscape']]):a).toLowerCase();$.browser=$.extend((!z)?$.browser:{},c(a,/(camino|chrome|firefox|netscape|konqueror|lynx|msie|opera|safari)/,[],/(camino|chrome|firefox|netscape|netscape6|opera|version|konqueror|lynx|msie|safari)(\/|\s)([a-z0-9\.\+]*?)(\;|dev|rel|\s|$)/));$.layout=c(a,/(gecko|konqueror|msie|opera|webkit)/,[['konqueror','khtml'],['msie','trident'],['opera','presto']],/(applewebkit|rv|konqueror|msie)(\:|\/|\s)([a-z0-9\.]*?)(\;|\)|\s)/);$.os={name:(/(win|mac|linux|sunos|solaris|iphone)/.exec(navigator.platform.toLowerCase())||[u])[0].replace('sunos','solaris')};if(!z){$('html').addClass([$.os.name,$.browser.name,$.browser.className,$.layout.name,$.layout.className].join(' '));}};$.browserTest(navigator.userAgent);})(jQuery);//http://jquery.thewikies.com/browser/

/***********************************************
* 온로드 실행 함수
************************************************/
;(function($){
	$(function() {

		/* body addClass */
		if($.browser.name == 'msie'){
			$('body').addClass($.browser.className);
		} else {
			$('body').addClass($.browser.name);
		}

		/* 네비게이션 */
		var $navSnb = $(".nav-snb>ul");
		$navSnb.find("ul").hide();
		$navSnb.find(".on").addClass("default").find(">ul").show();

		$navSnb.find("a").bind("click",function(){
			if($(this).attr("href")!="#none"){
				return true;
			}else{

				var this_deps = $(this).parents(".nav-snb ul").length;
				var chk_el = $(this);

				$navSnb.find("li").removeClass("active");

				for(i=this_deps;i>=1;i--){
					if(this_deps==i) chk_el = chk_el.parent();
					else chk_el = chk_el.parent().parent();
					chk_el.addClass("active");
				}

				$navSnb.find("li:not(.default)").each(function(){
					if(!$(this).hasClass("active")){
						$(this).removeClass("on").find(">ul").stop(true).slideUp();
					}
				});

				if(!$(this).parent().hasClass("default")){
					if($(this).parent().hasClass("on")){
						$(this).siblings("ul").slideUp(function(){
							$(this).parent().removeClass("on");
						});
					}else{
						$(this)
							.parent().addClass("on")
							.end()
							.siblings("ul").slideDown();
					}
				}
				return false;
			}
		});

		/* 폼객체 */
		$(".input-txt")
			.bind("blur",function(){
				if($(this).val()=="") $(this).removeClass("blank");
			})
			.bind("focus",function(){
				$(this).addClass("blank");
			});
	});
})(jQuery);

function dataProcessing(data) {
	data = data.replace(/<script.*>.*<\/script>/ig,""); // Remove script tags
	data = data.replace(/<\/?meta.*>/ig,""); //Remove link tags
	data = data.replace(/<\/?link.*>/ig,""); //Remove link tags
	data = data.replace(/<\/?html.*>/ig,""); //Remove html tag
	data = data.replace(/<\/?body.*>/ig,""); //Remove body tag
	data = data.replace(/<\/?head.*>/ig,""); //Remove head tag
	data = data.replace(/<\/?!doctype.*>/ig,""); //Remove doctype
	data = data.replace(/<title.*>.*<\/title>/ig,""); // Remove title tags
	return data;
}

/*****************************************
	동적 ajax호출
*****************************************/
var ajaxCall = function(url, result, soption, callback) {
	$.ajax({
		url : url,
		success: function(data) {
			$(result).empty();

			if(soption) data = data;
			else data = dataProcessing(unescape(data));

			$(data).appendTo(result);	// 동적 콘텐츠 생성시 반드시 append, appendTo를 사용하여 DOM을 추가한다.
			if (typeof callback === 'function') {
				callback();
			};
		},
		error : function(event, jqXHR, ajaxSettings, thrownError) {
			// event + ', ' +  jqXHR + ', ' + ajaxSettings + ', ' + thrownError
			alert(thrownError);
		}

	});
};

/***********************************************
* 레이어팝업 닫기
************************************************/
function modalClose(){
	var modelEventEl = $('a[href="#'+$('.wrap-layer-on').attr('id')+'"]').eq(0);
	$(".wrap-layer-on").remove();
	$(".modal-overlay").fadeOut();
	modelEventEl.focus();
}

/***********************************************
* 레이어 팝업(접근성개선용)
************************************************/
;(function($) {
	$.fn.modalCon = function(options){
		return this.each(function(n) {
			options = options || {};
			var opts = $.extend({}, $.fn.modalCon.defaults, options || {});
			var that = this;
			var $cont = $(this);		//이벤트호출객체 a
			var $contWrap;			//레이어컨텐츠
			var $contCon;			//레이어컨텐츠내부 컨텐츠영역
			var contWrapID;			//레이어아이디

			$cont.bind('click', function(ev) {
				ev.preventDefault();
				init();
			});

			var init = function() {

				modalClose();

				if (opts.callbackBefore) {
					if (typeof opts.callbackBefore === 'function') {
						var chk_process = false;
						if(opts.url){
							chk_process = opts.callbackBefore.call($cont);
						}else{
							opts.data = opts.callbackBefore.call($cont);
							if(opts.data) chk_process = true;
						}
						if (!chk_process) return;
					}
				}

				contWrapID = $cont.attr("href").split('#')[1];

				/* opts.appendNext 이 true일경우 이벤트가 일어난 객체 다음 형제노드로 레이어추가 */
				if(opts.appendNext){
					$contWrap = $('<div>')
						.addClass(opts.onClass);
					$cont.after($contWrap);
				}else{
					$contWrap = $('<div>')
						.addClass(opts.onClass)
						.appendTo('body');
				}

				$contWrap.attr('id', contWrapID);

				/* 레이어팝업 컨텐츠 세팅 */
				if (opts.url) {	// url trim 검사 필요(추후 trim 범용 함수 제작)
					ajaxCall(opts.url, $contWrap, opts.sCheck, function() {
						setModalCon();
					});
				}
				if (opts.data) {
					$contWrap.html(opts.data);
					setModalCon();
				}

				/* 레이어오픈 : 이벤트 및 애니메이션 설정 */
				function setModalCon(){

					$contCon = $contWrap.find(opts.layerWrap);

					var browser_width = $(window).width();
					var browser_height = $(window).height();
					var layer_width = $contCon.outerWidth();
					var layer_height = $contCon.outerHeight();
					var margin_top = Math.floor(layer_height /2) * (-1) + 'px';
					var margin_left = Math.floor(layer_width /2) * (-1) + 'px';
					var position_left = "50%";
					var position_top = $(window).scrollTop() + ((browser_height-layer_height)/2);

					//모달출력
					if (opts.modal){
						if(opts.modalEffect) $contWrap.before($("<div>").addClass(opts.modalClass).css("opacity",0).animate({"opacity":0.3}));
						else $contWrap.before($("<div>").addClass(opts.modalClass));
						$("."+opts.modalClass).css({'width' : browser_width, 'height' : browser_height});
					}

					//레이어팝업형태로 추가될경우 위치재설정
					if(!opts.appendNext){
						if(browser_height<=layer_height) position_top = 0;
						var margin_left = (-1)*(layer_width/2);

						if(opts.positionTop) position_top  = opts.positionTop + $(window).scrollTop();

						$contCon.css({
							"left" : position_left,
							"top" : position_top,
							"marginLeft" : margin_left+"px"
						});
					}

					// 센스리더 구동시에는 타켓 지정된 첫 번째 요소로 이동하지만 포커스는 닫기 버튼으로 이동시킨다.
					$contWrap.find(".btn-close").eq(0).focus();

					//로드 콜백함수 실행
					if (typeof opts.callbackLoad === 'function') {
						opts.callbackLoad.call($cont);
					};

					//닫기버튼 이벤트설정
					$contWrap.find(opts.close_trigger).bind("click",function(){
						callModalClose();
						$cont.focus();
						return false;
					});

					// 이벤트 바인딩 된 요소, 레이어 요소외 다른 요소를 클릭하였을 경우 모달 닫기
					$(document).unbind('click.modalHIde');
					$(document).on('click.modalHIde', function(e){
						if (!$(e.target).closest('#' + contWrapID).length && !$(e.target).closest(that).length) {
							callModalClose();
							$(document).unbind('click.modalHIde');
							return;
						}
					});
				}

				/* 04. 레이어닫기 */
				function callModalClose(){
					$contWrap.remove();
					$("."+opts.modalClass).fadeOut(function(){
						$(this).remove();
					});
					if (typeof opts.callbackAfter === 'function') {
						opts.callbackAfter.call($cont);
					};

					$(document).unbind('click.modalCon');
				}
			};
		});
	};

	$.fn.modalCon.defaults = {
		modal : true,
		modalClass : "modal-overlay",
		modalEffect : true,
		onClass : "wrap-layer-on",
		layerWrap : ".layer-type1",
		layerContent : ".layer-content",
		close_trigger : '.btn-close',
		appendNext : false,
		url: false,
		data : false,
		sCheck : false,
		positionTop : false,
		callbackBefore: null,//function() {},
		callbackLoad : null,//function() {},
		callbackAfter: null//function() {},
	}
})(jQuery);