$(document).ready(function() {
	
	$('.ajax_refresh_and_loading').click(function(){
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	});

	$('.crud_search').click(function(){
		$(this).closest('.bootstraptheme').find('.crud_page').val('1');
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	});

	$('.clear-filtering').click(function(){
		$(this).closest('.bootstraptheme').find('.crud_page').val('1');
		$(this).closest('.bootstraptheme').find('.search_text').val('');
		$(this).closest('.bootstraptheme').find('.per_page').val('10');
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	});

	$('.grocerycrud-container').on('change','.per_page', function(){
		$(this).closest('.bootstraptheme').find('.crud_page').val('1');
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	})

	$('.ajax_refresh_and_loading').click(function(){
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	});

	$('.paging-first').click(function(){
		if( $(this).closest('.bootstraptheme').find('.crud_page').val() != "1")
		{
			$(this).closest('.bootstraptheme').find('.crud_page').val('1');
			$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
		}
	});

	$('.paging-previous').click(function(){
		if( $(this).closest('.bootstraptheme').find('.crud_page').val() != "1")
		{
			$(this).closest('.bootstraptheme').find('.crud_page').val( parseInt($(this).closest('.bootstraptheme').find('.crud_page').val(),10) - 1 );
			$(this).closest('.bootstraptheme').find('.crud_page').trigger('change');
		}
	});

	$('.paging-last').click(function(){
		if( $(this).closest('.bootstraptheme').find('.crud_page').val() != $(this).closest('.bootstraptheme').find('.last-page-number').html())
		{
			$(this).closest('.bootstraptheme').find('.crud_page').val( $(this).closest('.bootstraptheme').find('.last-page-number').html());
			$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
		}
	});

	$('.paging-next').click(function(){
		if( $(this).closest('.bootstraptheme').find('.crud_page').val() != $(this).closest('.bootstraptheme').find('.last-page-number').html())
		{
			$(this).closest('.bootstraptheme').find('.crud_page').val( parseInt($(this).closest('.bootstraptheme').find('.crud_page').val()) + 1 );
			$(this).closest('.bootstraptheme').find('.crud_page').trigger('change');
		}
	});

	$('.crud_page').change(function(){
		$(this).closest('.bootstraptheme').find('.filtering_form').trigger('submit');
	});

	$('.filtering_form').submit(function(){
		var crud_page =  parseInt($(this).closest('.bootstraptheme').find('.crud_page').val(), 10);
		var last_page = parseInt($(this).closest('.bootstraptheme').find('.last-page-number').html(), 10);

		if (crud_page > last_page) {
			$(this).closest('.bootstraptheme').find('.crud_page').val(last_page);
		}
		if (crud_page <= 0) {
			$(this).closest('.bootstraptheme').find('.crud_page').val('1');
		}

		var this_form = $(this);

		var ajax_list_info_url = $(this).attr('data-ajax-list-info-url');

		// Pace.start();
		$(this).ajaxSubmit({
			 url: ajax_list_info_url,
			 dataType: 'json',
			 beforeSend: function(){
				this_form.closest('.bootstraptheme').find('.ajax_refresh_and_loading').addClass('loading');
			},
			complete: function(){
				this_form.closest('.bootstraptheme').find('.ajax_refresh_and_loading').removeClass('loading');
			},
			success:    function(data){
				this_form.closest('.bootstraptheme').find('.total_items').html( data.total_results);
				displaying_and_pages(this_form.closest('.bootstraptheme'));

				this_form.ajaxSubmit({
					success:    function(data){
						this_form.closest('.bootstraptheme').find('.ajax_list').html(data);
						// call_fancybox();
						// add_edit_button_listener();
					}
				});
			}
		});

		if ($('.bootstraptheme').length == 1) { //disable cookie storing for multiple grids in one page
			createCookie('crud_page_'+unique_hash,crud_page,1);
			createCookie('per_page_'+unique_hash,$('#per_page').val(),1);
			createCookie('hidden_ordering_'+unique_hash,$('#hidden-ordering').val(),1);
			createCookie('hidden_sorting_'+unique_hash,$('#hidden-sorting').val(),1);
			createCookie('search_text_'+unique_hash,$(this).closest('.bootstraptheme').find('.search_text').val(),1);
			createCookie('search_field_'+unique_hash,$('#search_field').val(),1);
		}
		// Pace.restart();
		return false;
	});

	function displaying_and_pages(this_container)
	{
		if (this_container.find('.crud_page').val() == 0) {
			this_container.find('.crud_page').val('1');
		}

		var crud_page 		= parseInt( this_container.find('.crud_page').val(), 10) ;
		var per_page	 	= parseInt( this_container.find('.per_page').val(), 10 );
		var total_items 	= parseInt( this_container.find('.total_items').html(), 10 );

		this_container.find('.last-page-number').html( Math.ceil( total_items / per_page) );

		if (total_items == 0) {
			this_container.find('.page-starts-from').html( '0');
		} else {
			this_container.find('.page-starts-from').html( (crud_page - 1)*per_page + 1 );
		}

		if (crud_page*per_page > total_items) {
			this_container.find('.page-ends-to').html( total_items );
		} else {
			this_container.find('.page-ends-to').html( crud_page*per_page );
		}
	}

	$('.ajax_list').on('click','.delete-row', function(){
		var delete_url = $(this).attr('href');

		var this_container = $(this).closest('.bootstraptheme');

		if( confirm( message_alert_delete ) )
		{
			$.ajax({
				url: delete_url,
				dataType: 'json',
				success: function(data)
				{
					if(data.success)
					{
						this_container.find('.ajax_refresh_and_loading').trigger('click');

						success_message(data.success_message);
					}
					else
					{
						error_message(data.error_message);

					}
				}
			});
		}

		return false;
	});


	$('.export-anchor').click(function(){
		var export_url = $(this).attr('data-url');
		let ci_csrf_token = $("input[name=ci_csrf_token]");
		var form_input_html = '';

		let search_text = $('#search_text');

		if(search_text.val() != ''){
			$('.bootstraptheme').find('#search_field option').each(function( index ) {
				console.log( index + ": " + $( this ).val() );
			  	if(index > 0 && this.value != ''){
			  		form_input_html = form_input_html + '<input type="hidden" name="'+this.value+'" value="'+search_text.val()+'">';
			  	}

			  	if(index > 0 && this.value != '' && this.selected){
			  		console.log(this.selected);
			  		form_input_html = '<input type="hidden" name="'+this.value+'" value="'+search_text.val()+'">';
			  		return false;
			  	}
			});
		}
		
		if(ci_csrf_token){
			form_input_html = form_input_html + '<input type="hidden" name=ci_csrf_token value="'+ci_csrf_token.val()+'">';
		}

		var form_on_demand = $("<form/>").attr("id","export_form").attr("method","post").attr("target","_blank")
								.attr("action",export_url).html(form_input_html);

		$('.grocerycrud-container').find('.hidden-operations').html(form_on_demand);
		
		form_on_demand.submit();
	});

	$('.print-anchor').click(function(){
		var print_url = $(this).attr('data-url');
		let ci_csrf_token = $("input[name=ci_csrf_token]");
		var form_input_html = '';

		let search_text = $('#search_text');

		if(search_text.val() != ''){
			$('.bootstraptheme').find('#search_field option').each(function( index ) {
				console.log( index + ": " + $( this ).val() );
			  	if(index > 0 && this.value != ''){
			  		form_input_html = form_input_html + '<input type="hidden" name="'+this.value+'" value="'+search_text.val()+'">';
			  	}

			  	if(index > 0 && this.value != '' && this.selected){
			  		console.log(this.selected);
			  		form_input_html = '<input type="hidden" name="'+this.value+'" value="'+search_text.val()+'">';
			  		return false;
			  	}
			});
		}
		
		if(ci_csrf_token){
			form_input_html = form_input_html + '<input type="hidden" name=ci_csrf_token value="'+ci_csrf_token.val()+'">';
		}

		var form_on_demand = $("<form/>").attr("id","print_form").attr("method","post").attr("action",print_url).html(form_input_html);

		$('.grocerycrud-container').find('.hidden-operations').html(form_on_demand);

		var _this_button = $(this);

		form_on_demand.ajaxSubmit({
			beforeSend: function(){
				_this_button.find('.fbutton').addClass('loading');
				_this_button.find('.fbutton>div').css('opacity','0.4');
			},
			complete: function(){
				_this_button.find('.fbutton').removeClass('loading');
				_this_button.find('.fbutton>div').css('opacity','1');
			},
			success: function(html_data){
				$("<div/>").html(html_data).printElement();
			}
		});
	});
} );
