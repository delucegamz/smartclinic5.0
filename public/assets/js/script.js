$(document).ready(function(){
	var $body_color = Cookies.get('body-color');

	if($body_color != ''){
		$('body').addClass($body_color);
	}

	$(window).load(function(){
		set_sidebar_height();
	}).resize(function(){
		set_sidebar_height();
	});

	$('.login-colorpicker a').click(function(){
		$class = $(this).attr('class');

		$('.login-colorpicker a').each(function(){
			$elm_class = $(this).attr('class');		
			
			$('body').removeClass($elm_class);	
		});

		$('body').addClass($class);

		Cookies.set('body-color', $class, { expires: 365 });

		return false;
	});

	$("#clock").clock();

	$('.collapse').on('hidden.bs.collapse', function () {	
		set_sidebar_height(); 
	}).on('shown.bs.collapse', function () {	
		set_sidebar_height(); 
	});

	$('#content').bind('heightChange', function(){
        set_sidebar_height(); 
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  	set_sidebar_height(); 
	});
});

var set_sidebar_height = function(){
	$('#menu').css('height','auto');

	var sidebar_h = $('#menu').outerHeight();
	var content_h = $('#content').outerHeight();

	if(content_h > sidebar_h){
		$('#menu').height(content_h);
	}
};

var regenerate_column_no = function(){
	var first_no = 0;

	$('#list-items tbody tr.item').each(function(){
		first_no++;

		$(this).find('.column-no').html(first_no);
	})
};

