(function($) {
      $(window).bind('scroll', function () {
          if ($(window).scrollTop() > 200) {
            $('.mn-fixed').addClass('stick-scroll');
             
          } else {
              $('.mn-fixed').removeClass('stick-scroll');
          }
      });
  
    $(".sb-click").on("click", function(e) {
        $(".bg-fl-search").addClass("fl-search-active");
        e.stopPropagation();
        $(".fl-search").addClass("sb-search-open");
        e.stopPropagation()
        $
      });
      $(document).on("click", function(e) {
        if ($(e.target).is(".bg-fl-search,input") === false) {
          $(".bg-fl-search").removeClass("fl-search-active");
          $(".fl-search").removeClass("sb-search-open");
          $(".fl-close").removeClass("fl-search-active");
          $("this").hide();
        }
    });
    
    if ($(window).width() > 1025) {
       $('.toggle-nav').css({display:'none'})
       $('#primary_nav_wrap').css({margin:'35px 0 0 0 '})
    }
    jQuery(document).ready(function() {
        jQuery('.toggle-nav').click(function(e) {
            $('.active-menu-default').addClass('active-pro');
            $('.menu-olay').css({display:'block'});
            $('.close-menu').css({display:'block', right:'230px'});
        });
        jQuery('.menu-olay').click(function(e) {
            $('.active-menu-default').removeClass('active-pro');
            $(this).css({display:'none'});
            $('.close-menu').css({display:'none', right:'-100%'});
        });
        jQuery('.close-menu').click(function(e) {
            $('.active-menu-default').removeClass('active-pro');
            $(this).css({display:'none', right:'-100%'});
            $('.menu-olay').css({display:'none'});
        });

    });
//menu humber ger
    var trigger = $('.hamburger'),
      overlay = $('.overlay'),
     isClosed = false;

    trigger.click(function () {
      hamburger_cross();
    });

    function hamburger_cross() {

      if (isClosed == true) {
        overlay.hide();
        trigger.removeClass('is-open');
        trigger.addClass('is-closed');
        isClosed = false;
      } else {
        overlay.show();
        trigger.removeClass('is-closed');
        trigger.addClass('is-open');
        isClosed = true;
      }
  }
  $('.header-flim .overlay').click(function(event) {
        overlay.hide();
       $('#wrapper').removeClass('toggled');
       trigger.removeClass('is-open');
       trigger.addClass('is-closed');
    isClosed = false;
  });
  $('[data-toggle="offcanvas"]').click(function () {
        $('#wrapper').toggleClass('toggled');
  });
  $('.sidebar-nav').find('li:has(>ul)').append("<i class='fa fa-angle-down'></i>");
    $('.sidebar-nav ').find('li i').click(function(e){
         $(this).parent().find('ul.sub-menu').toggle(500);
        return false;
    });

    if ($(window).width() > 1025) {
       $('.toggle-nav').css({display:'none'});
    }
    if ($(window).width() < 1025) {
        $('.active-menu-type2').find('li:has(>ul)').append("<i class='fa fa-angle-down'></i>");
        $('.active-menu-type2 ').find('li i').click(function(e){
             $(this).parent().find('ul.sub-menu').toggle(500);
            return false;
        });
    }
    jQuery(document).ready(function() {
        jQuery('.toggle-nav').click(function(e) {
            $('.menu-style2').addClass('active-pro');
            $('.menu-olay').css({display:'block'});
            $('.close-menu').css({display:'block', right:'230px', animation:'fadeOutLeft 0.5s linear'});
        });
        jQuery('.menu-olay').click(function(e) {
            $('.menu-style2').removeClass('active-pro');
            $(this).css({display:'none'});
            $('.close-menu').css({display:'none', right:'-100%'});
        });
        jQuery('.close-menu').click(function(e) {
            $('.menu-style2').removeClass('active-pro');
            $(this).css({display:'none', right:'-100%'});
            $('.menu-olay').css({display:'none'});
        });
    });
    new WOW().init();

$(document).ready(function(){
  $('li img').on('click',function(){
    var src = $(this).attr('src');
    var img = '<img src="' + src + '" class="img-responsive"/>';
    var iframe = $(this).siblings('div');	
    //start of new code new code
    var index = $(this).parent('li').index();       
    var html = '';	
	
	if(iframe.attr('class')) {
		html += iframe.html();		
	} else {	
		html += img;    
	}	   	
    html += '<div style="clear:both;display:block;" class="pop_np">';
    html += '<a class="controls next" href="'+ (index+2) + '"><i class="fa fa-angle-right"></i></a>';
    html += '<a class="controls previous" href="' + (index) + '"><i class="fa fa-angle-left"></i></a>';
    html += '<span class="close_all">x</span>';
    html += '</div>';
    
    $('#myModal').modal();
    $('#myModal').on('shown.bs.modal', function(){
      $('#myModal .modal-body').html(html);
      // tam xoa vi khong biet trigger lam gi
      //$('a.controls').trigger('click');
    })
    $('#myModal').on('hidden.bs.modal', function(){
      $('#myModal .modal-body').html('');
    }); 
   });  
})    
$( "body" ).on('click','.close_all',function() {
  $('#myModal').modal('hide');
});
$(document).on('click', 'a.controls', function(){	
  var index = $(this).attr('href');
  var src = $('ul.row li:nth-child('+ index +') img').attr('src');             
  
  $('.modal-body img').attr('src', src);
  
  var newPrevIndex = parseInt(index) - 1; 
  var newNextIndex = parseInt(newPrevIndex) + 2; 
  
  if($(this).hasClass('previous')){               
    $(this).attr('href', newPrevIndex); 
    $('a.next').attr('href', newNextIndex);
  }else{
    $(this).attr('href', newNextIndex); 
    $('a.previous').attr('href', newPrevIndex);
  }
  
  var total = $('ul.row li').length + 1; 
  //hide next button
  if(total === newNextIndex){
    $('a.next').hide();
  }else{
    $('a.next').show()
  }            
  //hide previous button
  if(newPrevIndex === 0){
    $('a.previous').hide();
  }else{
    $('a.previous').show()
  }
  return false;
  });
})(jQuery)