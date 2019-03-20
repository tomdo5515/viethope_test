$(function () {

    var linkapi = '/dashboard/apiterm';

    var parent = 0;

    var tree = $('#tree_term').tree({
                primaryKey: 'id',
                uiLibrary: 'bootstrap',
                dataSource: linkapi + '/getmenus',
                checkboxes: false
            });

    tree.on('select', function (e, node, id) { 
        if(typeof(disable_all) != undefined && disable_all)
        {
            if(typeof(parent_selected) != undefined && id*1 != parent_selected*1)
            {
                var sel = tree.getNodeById(parent_selected);
                if(typeof(sel) != undefined){
                    tree.select(sel);
                    return false;
                }
            }
        }else{
            parent = id;
        }
    });

    tree.on('dataBound', function (e) {
        if(typeof(parent_selected) != undefined)
        {
            var sel = tree.getNodeById(parent_selected);
            if(typeof(sel) != undefined){
                tree.expandAll();
                tree.select(sel);
                parent = parent_selected;
            }
        }
    });

    // define validate
    $('#terms_post')
        .find('[name="post_language"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#terms_post').bootstrapValidator('revalidateField', 'post_language');
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
                post_term_title: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'Title is required.'
                        }
                    }
                },
                post_language: {
                    group: '.post_language',
                    validators: {
                        notEmpty: {
                            message: 'choose a language.'
                        },
                        callback: {
                            message: 'choose a language',
                            callback: function(value, validator, $field) {
                                var div = $('<div/>').html(value).get(0),
                                  text = div.textContent || div.innerText;
                                return text.length <= 20;
                            }
                        }
                    }
                }
            }
        });

    // disable
    var validator = $('#terms_post').data('bootstrapValidator');
    validator.enableFieldValidators('post_seo_title', false);
    validator.enableFieldValidators('post_seo_description', false);
    validator.enableFieldValidators('post_seo_keywords', false);

    $(document).on('click', '.submit_term', function () {

        validator.validate();
        if (validator.isValid()) {
            // Make the ajax call here.
            // variable
            $('#loading_fa').addClass('spinner');
            $('#submit_publish').prop('disabled', true);
            $('#submit_draft').prop('disabled', true);
            let datapost = {};
            let term_status = $(this).attr('status');
            let post_term_name = $('.post_term_name').val();
            let post_term_hyperlink = $('.post_term_hyperlink').val();
            let post_term_acronym = $('.post_term_acronym').val();
            let post_term_slug = $('.post_term_slug').val();
            let post_term_order = $('.post_term_order').val();
            let post_seo_title = $('.post_seo_title').val();
            let post_seo_description = $('.post_seo_description').val();
            let post_seo_keywords = $('.post_seo_keywords').val();
            let post_metas = [];
            post_metas.push(['title', post_seo_title]);
            post_metas.push(['description', post_seo_description]);
            post_metas.push(['keywords', post_seo_keywords]);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            $.ajax({
                method: "POST",
                url: linkapi + "/addmenu",
                data:
                {
                    'parent': parent, 'term_date': moment().utc().add(7, 'h').format(), 'term_time_gmt': moment().utc().unix(),
                    'term_name': post_term_name,'term_slug': post_term_slug,'term_order': post_term_order, 'term_hyperlink': post_term_hyperlink,
                    'term_status': term_status, 'metas': post_metas, 'language': post_language, 'acronym' : post_term_acronym
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
                          text: 'Add menu success!',
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
                        swal("","Add menu fail!","error");
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

    $(document).on('click', '.update_term', function () {

        validator.validate();
        if (validator.isValid()) {

            $('#loading_fa').addClass('spinner');
            $('#submit_publish').prop('disabled', true);
            $('#submit_draft').prop('disabled', true);

            let term_id = $('.post_term_id').val();
            if(term_id=='' || term_id <= 0)
            {
                swal("","Please go back to the homepage and go back!","error");
                return false;
            }

            let datapost = {};
            let term_status = $(this).attr('status');
            let post_term_name = $('.post_term_name').val();
            let post_term_hyperlink = $('.post_term_hyperlink').val();
            let post_term_acronym = $('.post_term_acronym').val();
            let post_term_slug = $('.post_term_slug').val();
            let post_term_order = $('.post_term_order').val();
            let post_seo_title = $('.post_seo_title').val();
            let post_seo_description = $('.post_seo_description').val();
            let post_seo_keywords = $('.post_seo_keywords').val();
            let post_metas = [];
            post_metas.push(['title', post_seo_title]);
            post_metas.push(['description', post_seo_description]);
            post_metas.push(['keywords', post_seo_keywords]);

            let post_language;
            $("select.post_language").find('option:selected').each(function () {
                post_language = $(this).val();
            });

            $.ajax({
                method: "POST",
                url: linkapi + "/updatemenu",
                data:
                {
                    'term_id': term_id, 'parent': parent, 'term_date': moment().utc().add(7, 'h').format(), 'term_time_gmt': moment().utc().unix(),
                    'term_name': post_term_name,'term_slug': post_term_slug,'term_order': post_term_order, 'term_hyperlink': post_term_hyperlink,
                    'term_status': term_status, 'metas': post_metas, 'language': post_language, 'acronym': post_term_acronym
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
               
                if(data.errorcode==0){
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal("","Update menu success!","success");
                    }, 10);
                }
                else{
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_publish').prop('disabled', false);
                        $('#submit_draft').prop('disabled', false);
                        swal("","Update menu fail!","error");
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

    // load term language
    $(document).on('hidden.bs.select', 'select.post_language', function () {

        let term_id = $('.post_term_id').val();
        console.log('post_term_id: ' + term_id);
        if(term_id && term_id > 0)
        {
            $.ajax({
                method: "POST",
                url: linkapi + "/gettermlang",
                data: { 'language': $(this).val(), 'term_id': term_id }
            }).done(function (data) {
                console.log("Data Saved: " + data);
               
                if(data.errorcode==0){
                    $('.post_term_name').val(data.result.term_name);
                    $('.post_term_slug').val(data.result.slug);
                    // $('.post_term_order').val(data.result.term_order);
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