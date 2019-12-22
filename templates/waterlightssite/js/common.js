var issended=0;
function showPop(popName, title) {
        if(title) {
			$('.'+popName).find('.formtitle h2').text(title);
			$('.'+popName).find('.sendformback').attr('data-title', title);
		}
		$('.overlayer').animate({height: 100+'%'}, function(){
			$('.'+popName).fadeIn();
		})
	}
//
function showerror(text) {
	$('#erroralert').html(text);
	setTimeout("hideerror()",4000);
	$('#erroralert').css('display','block');
}

function hideerror() {

	$('#erroralert').css('display','none');
}

function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function validatephone(phone) { 
	var re = /^[+][7][\d\ -]{4,14}$/;
	var re2 = /^[8][\d\ -]{4,14}$/;
	var valid = false;
	if(re.test(phone) || re2.test(phone)) valid = true;
	return valid;
}


$(document).ready(function() {
	
	var issended=0, thisButton='';
	//$("html").niceScroll();
	
	
	function scrollToElement(target,topoffset) {
		var speed = 800;
		var destination = jQuery(target).offset().top - topoffset;
		jQuery('html:not(:animated),body:not(:animated)').animate({
			scrollTop: destination
		}, speed, function() {});
		return false;
	}
	
	
	
	
	$('.gettourshow').click(function(e){
        e.preventDefault();
		$('.gettour').css('top',($(window).scrollTop()+35)+'px');
        showPop('gettour');
    });
	$('.showpop').click(function(e){
        e.preventDefault();
        showPop($(this).attr('data-popup'), $(this).attr('data-title'));
    });
	
	$('.showvideo').click(function(e){
        e.preventDefault();
		if(!$('.popup-video iframe').length) $('.popup-video').append('<iframe src="'+$(this).attr('href')+'" allow="encrypted-media" allowfullscreen></iframe> ');
        showPop('popup-video');
    });
	
	$('.popup .close, .close-bottom, .overlayer').click(function(){
		$('.popup').fadeOut();
		$('.overlayer').css('height', 0);
		thisButton='';
		if($('.popup-video iframe').length) $('.popup-video iframe').remove();
	});
	
	//РћС‚РїСЂР°РІРєР° С„РѕСЂРј
	$('.sendformback').on('click',function(e){
        e.preventDefault();
		if(issended==0)
		{
			var data = {};
			var f = $(this).closest('.formdata');
			data.subject = $(this).attr('data-title');
			if(f.find('[name=name]')) data.name = f.find('[name=name]').val();
			if(f.find('[name=mailorphone]')) data.mailorphone = f.find('[name=mailorphone]').val();
			if(f.find('[name=quest]')) data.quest = f.find('[name=quest]').val();
			
			if(!validatephone(data.mailorphone) && !validateEmail(data.mailorphone)){
				showerror('не заполнен телефон или e-mail');
				f.find('[name=mailorphone]').focus();
				return;
			}
			if(f.find('[name=mailorphone]').length) f.find('[name=mailorphone]').val('');
			$.post('/status.php?status=back', data, function(data){
				$('.popup').fadeOut();
				showPop('popup2');
				issended=1;
			});
		}
		else showerror('заявка уже была отправлена,<br> с вами свяжутся в ближайшее время');
	});
	
	//mobmenu
	
	$('.mobmenu').click(function(e){
		e.preventDefault();
		if($('.mobmenuinner').hasClass('opened')) $('.mobmenuinner').removeClass('opened');
		else $('.mobmenuinner').addClass('opened');
	});
	$('li.menu-havechild').prepend('<div class="menu-divsub"></div>');
	$('.menu-divsub').click(function(){
		if($(this).parent().hasClass('menu-opened')) $(this).parent().removeClass('menu-opened');
		else $(this).parent().addClass('menu-opened');
	});
	$('ul.menu-level-2 .menu-light').parent().parent().addClass('menu-opened');
	$('.topmenu li a').hover(function(){
		if($(this).parent().find('ul.menu-level-2').length) $('.subtopmenu').html($(this).parent().find('ul.menu-level-2').html());
		else $('.subtopmenu').html('');
	});
	if($('.topmenu .menu-opened ul.menu-level-2').length) $('.subtopmenu').html($('.topmenu .menu-opened ul.menu-level-2').html());
	
	//slider
	$('.slider').slick({
		infinite: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		dots: true
	});
	
	$('.test').click(function(){$('.test').css('display','none');});
	//111
});


