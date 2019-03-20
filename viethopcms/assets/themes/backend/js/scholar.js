$(function () {

    var linkapi = '/dashboard/api';

    // define validate
    
    // 
    $('#page_post')
        .find('[name="post_language"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#page_post').bootstrapValidator('revalidateField', 'post_language');
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
                            message: 'The title require.'
                        }
                    }
                },
                // post_seo_title: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Title scholar is required.'
                //         }
                //     }
                // },
                post_seo_description: {
                    validators: {
                        notEmpty: {
                            message: 'Description scholar is required.'
                        }
                    }
                },
                // post_seo_keywords: {
                //     validators: {
                //         notEmpty: {
                //             message: 'Keywords scholar is required.'
                //         }
                //     }
                // },
                post_article_content: {
                    group: '.group_ckeditor',
                    validators: {
                        callback: {
                            message: 'The scholar content is not less than 1 characters',
                            callback: function(value, validator, $field) {
                                var div = $('<div/>').html(value).get(0),
                                  text = div.textContent || div.innerText;
                                return text.length > 0;
                            }
                        }
                    }
                },
                post_menu: {
                    group: '.post_menu',
                    validators: {
                        notEmpty: {
                            message: 'Select programe for scholar.'
                        },
                        callback: {
                            message: 'Select programe for scholar',
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
                            message: 'Select language for the scholar.'
                        },
                        callback: {
                            message: 'Select language for the scholar.',
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
            $('#page_post').bootstrapValidator('revalidateField', 'post_article_content');
        });

    // disable
    var validator = $('#page_post').data('bootstrapValidator');


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

            let post_menus = [];
            $("select.post_menu").find('option:selected').each(function () {
                post_menus.push($(this).val());
            });
            console.log('post_menu: ' + post_menus);
            console.log(post_menus);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            $.ajax({
                method: "POST",
                url: linkapi + "/addscholar",
                data:
                {
                    'article_date': moment().utc().add(7, 'h').format(), 'article_time_gmt': moment().utc().unix(),
                    'article_title': post_article_title, 'article_content': post_article_content,
                    'thumbnail': post_thumbnail, 'article_password': post_article_password, 'pin' : post_pin,
                    'article_status': article_status, 'metas': post_metas, 'menus': post_menus, 'language': post_language
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
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
                
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_menu').val('');
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
                swal("","Add fail!","error");
                return false;
            }
            let datapost = {};
            let article_status = $(this).attr('status');
            let post_article_title = $('.post_article_title').val();
            console.log('post_article_title: ' + post_article_title);
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

            let post_menus = [];
            $(".post_menu").find('option:selected').each(function () {
                post_menus.push($(this).val());
            });
            console.log('post_menus: ' + post_menus);
            console.log(post_menus);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            $.ajax({
                method: "POST",
                url: linkapi + "/updatescholar",
                data:
                {
                    'article_id' : article_id, 'pin' : post_pin,
                    'article_date': moment().utc().add(7, 'h').format(), 'article_time_gmt': moment().utc().unix(),
                    'article_title': post_article_title, 'article_content': post_article_content,
                    'thumbnail': post_thumbnail, 'article_password': post_article_password,
                    'article_status': article_status, 'metas': post_metas, 'language': post_language,
                    'menus': post_menus
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#submit_publish').prop('disabled', false);
                    $('#submit_draft').prop('disabled', false);
                    swal("","Updated scholar!","success");
                }, 10);
                
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_menu').val('');
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

    // Add menu
    $(document).on('click', '#ajax_add_menu', function () {
        if ($('.ajax_menu').val() == '') {
            $('.menu_alert').html('Enter menu to add.');
            setTimeout(function () {
                $('.menu_alert').html('Choose the menu if you need more submenus.');
            }, 3000);
        }
        else {
            $.ajax({
                method: "POST",
                url: linkapi + "/addmenu",
                data: { 'term_name': $('.ajax_menu').val(), 'parent': $('.ajax_parent_menu option:selected').val() }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                //ajax done
                $('select.ajax_parent_menu').append(new Option($('.ajax_menu').val(), data.result));
                $('select.post_menu').append(new Option($('.ajax_menu').val(), data.result, true)).selectpicker('val', data.result);
                $('.selectpicker').selectpicker('refresh')
                $('.ajax_menu').val('');
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

});