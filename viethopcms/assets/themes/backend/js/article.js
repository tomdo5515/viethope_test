$(function () {

    var linkapi = '/dashboard/api';

    // define validate
    
    // 
    $('#article_post')
        .find('[name="post_categories"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#article_post').bootstrapValidator('revalidateField', 'post_categories');
        })
        .end()
        .find('[name="post_language"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#article_post').bootstrapValidator('revalidateField', 'post_language');
        })
        .end()
        // Main config 
        .bootstrapValidator({
            // live: 'disabled',
            // framework: 'bootstrap',
            // submitButtons: [type="submit"],
            excluded: [':disabled'],
            // message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                post_article_title: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'The article title require.'
                        }
                    }
                },
                post_article_content: {
                    group: '.group_ckeditor',
                    validators: {
                        callback: {
                            message: 'The article content is not less than 1 characters',
                            callback: function(value, validator, $field) {
                                var div = $('<div/>').html(value).get(0),
                                  text = div.textContent || div.innerText;
                                return text.length > 0;
                            }
                        }
                    }
                },
                post_categories: {
                    group: '.post_categories',
                    validators: {
                        notEmpty: {
                            message: 'Select category for article.'
                        },
                        callback: {
                            message: 'Select category for article',
                            callback: function(value, validator, $field) {
                                var div = $('<div/>').html(value).get(0),
                                  text = div.textContent || div.innerText;
                                return text.length <= 20;
                            }
                        }
                    }
                },
                post_language: {
                    group: '.post_language',
                    validators: {
                        notEmpty: {
                            message: 'Select language for the article.'
                        },
                        callback: {
                            message: 'Select language for the article',
                            callback: function(value, validator, $field) {
                                var div = $('<div/>').html(value).get(0),
                                  text = div.textContent || div.innerText;
                                return text.length <= 20;
                            }
                        }
                    }
                }
            }
        })
        .find('[name="post_article_content"]')
        .ckeditor()
        .editor
            // To use the 'change' event, use CKEditor 4.2 or later
        .on('change', function() {
            // Revalidate the post_article_content field`
            $('#article_post').bootstrapValidator('revalidateField', 'post_article_content');
        });

    // disable
    var validator = $('#article_post').data('bootstrapValidator');
    validator.enableFieldValidators('post_seo_title', false);
    validator.enableFieldValidators('post_seo_description', false);
    validator.enableFieldValidators('post_seo_keywords', false);


    $(document).on('click', '.submit_article', function () {

        validator.validate();
        if (validator.isValid()) {

            // Make the ajax call here.
            // variable
            $('#loading_fa').addClass('spinner');
            $('#submit_publish').prop('disabled', true);
            $('#submit_draft').prop('disabled', true);
            let datapost = {};
            let article_status = $(this).attr('status');
            let post_article_title = $('.post_article_title').val();
            console.log('post_article_title: ' + post_article_title);
            let post_date = $('.post_date').val();
            console.log('post_date: ' + post_date);
            let post_event_date = $('.post_event_date').val();
            console.log('post_event_date: ' + post_event_date);
            let post_article_content = $('.post_article_content').val();
            console.log('post_article_content: ' + post_article_content);
            let post_seo_title = $('.post_seo_title').val();
            console.log('post_seo_title: ' + post_seo_title);
            let post_seo_description = $('.post_seo_description').val();
            console.log('post_seo_description: ' + post_seo_description);
            let post_seo_keywords = $('.post_seo_keywords').val();
            console.log('post_seo_keywords: ' + post_seo_keywords);
            let post_article_password = $('.post_article_password').val();
            console.log('post_article_password: ' + post_article_password);
            let post_metas = [];
            post_metas.push(['title', post_seo_title]);
            post_metas.push(['description', post_seo_description]);
            post_metas.push(['keywords', post_seo_keywords]);

            let post_thumbnail = $('.post_thumbnail').val();
            console.log('post_thumbnail: ' + post_thumbnail);
            let post_pin = $('.post_pin').is(':checked');
            console.log('post_pin: ' + post_pin);

            let post_categories = [];
            $("select.post_categories").find('option:selected').each(function () {
                post_categories.push($(this).val());
            });

            console.log('post_categories: ' + post_categories);
            console.log(post_categories);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            console.log('post_language: ' + post_language);

            let post_focus;
            $("select.post_focus").find('option:selected').each(function () {
                post_focus = $(this).val();
            });

            console.log('post_language: ' + post_language);

            let post_tags = [];
            $("#post_tags").find('span.post_tags').each(function () {
                post_tags.push([$(this).attr('tagid'), $(this).attr('tagval')]);
            });
            console.log('post_tags: ' + post_tags);
            console.log(post_tags);

            $.ajax({
                method: "POST",
                url: linkapi + "/addarticle",
                data:
                {
                    'article_date': moment(post_date, "MM-DD-YYYY hh:mm:ss").format(), 'article_time_gmt': moment().utc().unix(),
                    'article_title': post_article_title, 'article_content': post_article_content,
                    'thumbnail': post_thumbnail, 'article_password': post_article_password, 'focus': post_focus,
                    'article_status': article_status, 'metas': post_metas, 'event_date': moment(post_event_date, "MM-DD-YYYY hh:mm:ss").format(),
                    'event_time_gmt': moment(post_event_date, "MM-DD-YYYY hh:mm:ss").unix(),
                    'categories': post_categories, 'tags': post_tags, 'pin': post_pin, 'language': post_language
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                
                if(data.errorcode==0){
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal({
                          title: 'Success!',
                          text: 'Added!',
                          type: 'success',
                          confirmButtonText: 'OK'
                        },
                        function() {
                            window.location.href = data.result;
                        });
                    }, 10);

                }
                else{
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal("","Add fail!","error");
                    }, 10);
                }
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_category').val('');
            }).fail(function (jqXHR, exception) {
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#submit_publish').prop('disabled', false);
                    $('#submit_draft').prop('disabled', false);
                }, 10);
            });
        }
        
    });

    $(document).on('click', '.update_article', function () {

        validator.validate();
        if (validator.isValid()) {

            // Make the ajax call here.
            // variable
            $('#loading_fa').addClass('spinner');
            $('#submit_publish').prop('disabled', true);
            $('#submit_draft').prop('disabled', true);
            let article_id = $('.post_article_id').val();
            console.log('post_article_id: ' + article_id);
            if(article_id=='' || article_id <= 0)
            {
                swal("","Something wrong!","error");
                return false;
            }
            let datapost = {};
            let article_status = $(this).attr('status');
            let post_article_title = $('.post_article_title').val();
            console.log('post_article_title: ' + post_article_title);
            let post_date = $('.post_date').val();
            console.log('post_date: ' + post_date);
            let post_event_date = $('.post_event_date').val();
            console.log('post_event_date: ' + post_event_date);
            let post_article_content = $('.post_article_content').val();
            console.log('post_article_content: ' + post_article_content);
            let post_seo_title = $('.post_seo_title').val();
            console.log('post_seo_title: ' + post_seo_title);
            let post_seo_description = $('.post_seo_description').val();
            console.log('post_seo_description: ' + post_seo_description);
            let post_seo_keywords = $('.post_seo_keywords').val();
            console.log('post_seo_keywords: ' + post_seo_keywords);
            let post_article_password = $('.post_article_password').val();
            console.log('post_article_password: ' + post_article_password);
            let post_metas = [];
            post_metas.push(['title', post_seo_title]);
            post_metas.push(['description', post_seo_description]);
            post_metas.push(['keywords', post_seo_keywords]);

            let post_thumbnail = $('.post_thumbnail').val();
            console.log('post_thumbnail: ' + post_thumbnail);
            let post_pin = $('.post_pin').is(':checked');
            console.log('post_pin: ' + post_pin);

            let post_categories = [];
            $(".post_categories").find('option:selected').each(function () {
                post_categories.push($(this).val());
            });
            console.log('post_categories: ' + post_categories);
            console.log(post_categories);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            console.log('post_language: ' + post_language);

            let post_focus;
            $("select.post_focus").find('option:selected').each(function () {
                post_focus = $(this).val();
            });
            
            let post_tags = [];
            $("#post_tags").find('span.post_tags').each(function () {
                post_tags.push([$(this).attr('tagid'), $(this).attr('tagval')]);
            });
            console.log('post_tags: ' + post_tags);
            console.log(post_tags);

            $.ajax({
                method: "POST",
                url: linkapi + "/updatearticle",
                data:
                {
                    'article_id' : article_id, 'pin' : post_pin,
                    'article_date': moment(post_date, "MM-DD-YYYY hh:mm:ss").format(), 'article_time_gmt': moment().utc().unix(),
                    'article_title': post_article_title, 'article_content': post_article_content,
                    'thumbnail': post_thumbnail, 'article_password': post_article_password, 'focus': post_focus,
                    'article_status': article_status, 'metas': post_metas, 'event_date': moment(post_event_date, "MM-DD-YYYY hh:mm:ss").format(),
                    'event_time_gmt': moment(post_event_date, "MM-DD-YYYY hh:mm:ss").unix(),
                    'categories': post_categories, 'tags': post_tags, 'language': post_language
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
               
                if(data.errorcode==0){
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal("","Updated article!","success");
                    }, 10);
                }
                else{
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal("","Update article fail!","error");
                    }, 10);
                }
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_category').val('');
            }).fail(function (jqXHR, exception) {
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#submit_publish').prop('disabled', false);
                    $('#submit_draft').prop('disabled', false);
                }, 10);
            });
        }
        
    });

    Pace.start();

    // Add category
    $(document).on('click', '#ajax_add_category', function () {
        if ($('.ajax_category').val() == '') {
            $('.category_alert').html('Enter category to add.');
            setTimeout(function () {
                $('.category_alert').html('Choose the category if you need more subcategories.');
            }, 3000);
        }
        else {
            $.ajax({
                method: "POST",
                url: linkapi + "/addcategory",
                data: { 'term_name': $('.ajax_category').val(), 'parent': $('.ajax_parent_category option:selected').val() }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                //ajax done
                $('select.ajax_parent_category').append(new Option($('.ajax_category').val(), data.result));
                $('select.post_categories').append(new Option($('.ajax_category').val(), data.result, true)).selectpicker('val', data.result);
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_category').val('');
            });
        }
    });

    // load article language
    $(document).on('hidden.bs.select', 'select.post_language', function () {

        let article_id = $('.post_article_id').val();
        console.log('post_article_id: ' + article_id);
        if(article_id && article_id > 0)
        {
            $.ajax({
                method: "POST",
                url: linkapi + "/getarticlelang",
                data: { 'language': $(this).val(), 'article_id': article_id }
            }).done(function (data) {
                console.log("Data Saved: " + data);
               
                if(data.errorcode==0){
                    $('.post_article_title').val(data.result.article_title);
                    $('.post_article_content').val(data.result.article_content);
                    if(data.metas.length > 0) {
                        for (var i = 0; i < data.metas.length; i++) {
                            if(data.metas[i].meta_key == 'title'){
                                $('.post_seo_title').val(data.metas[i].meta_value);
                            }
                            if(data.metas[i].meta_key == 'description'){
                                $('.post_seo_description').val(data.metas[i].meta_value);
                            }
                            if(data.metas[i].meta_key == 'keywords'){
                                $('.post_seo_keywords').val(data.metas[i].meta_value);
                            }
                        }
                    }
                    else{
                        $('.post_seo_title').val('');
                        $('.post_seo_description').val('');
                        $('.post_seo_keywords').val('');
                    }
                    
                }
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_category').val('');
            });
        }
    });

    // Add tags
    $(document).on('click', '#ajax_add_tags', function () {
        if ($('.ajax_tags').val() == '') {
            $('.tag_alert').html('Enter the tag.');
            setTimeout(function () {
                $('.tag_alert').html('Separate tags with commas (,).');
            }, 3000);
        }
        else {
            // $.ajax({
            //     method: "POST",
            //     url: linkapi+"/addtag",
            //     data: {'term_name': $('.ajax_tags').val()}
            // }).done(function( data ) {
            //     console.log( "Data Saved: " + data );
            //     //ajax done
            //     $('#post_tags').append( '<span tagid="'+data.result+'" tagval="'+$('.ajax_tags').val()+'" class="post_tags tags label label-primary"><a class="remove_tag" href="javascript:void(0);"><i class="fa fa-trash"></i></a> '+$('.ajax_tags').val()+'</span>' );
            //     $('.ajax_tags').val('');
            // });

            $('#post_tags').append('<span tagid="0" tagval="' + $('.ajax_tags').val() + '" class="post_tags tags label label-primary"><a class="remove_tag" href="javascript:void(0);"><i class="fa fa-trash"></i></a> ' + $('.ajax_tags').val() + '</span>');
            $('.ajax_tags').val('');
        }
    });

    // Remove tags

    $(document).on('click', '.remove_tag', function () {
        $(this).parent().remove();
    });

    $('input.ajax_tags').typeahead({
        ajax: linkapi + '/gettags',
        onSelect: function (item) {
            console.log(item);
            $('#post_tags').append('<span tagid="' + item.value + '" tagval="' + item.text + '" class="post_tags tags label label-primary"><a class="remove_tag" href="javascript:void(0);"><i class="fa fa-trash"></i></a> ' + item.text + '</span>');
            $('.ajax_tags').val('');
        },
        displayField: 'term_name',
        valueField: 'term_id',
        scrollBar: true
    });

});