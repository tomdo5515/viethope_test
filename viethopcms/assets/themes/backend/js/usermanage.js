$(function () {

    var linkapi = '/dashboard/usermanage';

    // define validate
    
    // form add
    $('#user_post')
        .find('[name="post_groups"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#user_post').bootstrapValidator('revalidateField', 'post_groups');
        })
        .end()
        // Main config 
        .bootstrapValidator({
            excluded: [':disabled'],
            // message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                post_user_email: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'email is required and can\'t be empty.'
                        }
                    }
                },
                post_username: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'username is required and can\'t be empty.'
                        }
                    }
                },
                post_password: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'The password is required and can\'t be empty.'
                        }
                    }
                },
                post_confirmpassword: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'The confirm password is required and can\'t be empty.'
                        },
                        identical: {
                            field: 'post_password',
                            message: 'The password and its confirm are not the same.'
                        }
                    }
                },
                post_first_name: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'firstname is required and can\'t be empty.'
                        }
                    }
                },
                post_groups: {
                    group: '.post_groups',
                    validators: {
                        notEmpty: {
                            message: 'Choice group user belong.'
                        },
                        callback: {
                            message: 'Choice group user belong.',
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


    // form edit
    $('#user_put')
        .find('[name="post_groups"]')
        .selectpicker()
        .on('hidden.bs.select', function (e) {
            $('#user_post').bootstrapValidator('revalidateField', 'post_groups');
        })
        .end()
        // Main config 
        .bootstrapValidator({
            excluded: [':disabled'],
            // message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                post_first_name: {
                    // trigger: 'blur keyup',
                    validators: {
                        notEmpty: {
                            message: 'firstname is required and can\'t be empty.'
                        }
                    }
                },
                post_groups: {
                    group: '.post_groups',
                    validators: {
                        notEmpty: {
                            message: 'Choice group user belong.'
                        },
                        callback: {
                            message: 'Choice group user belong.',
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

    var validator = $('#user_post').data('bootstrapValidator');

    $(document).on('click', '.submit_user', function () {

        validator.validate();
        if (validator.isValid()) {

            // Make the ajax call here.
            // variable
            $('#loading_fa').addClass('spinner');
            $('#submit_user').prop('disabled', true);
            let post_user_email = $('.post_user_email').val();
            console.log('post_user_email: ' + post_user_email);
            let post_username = $('.post_username').val();
            console.log('post_username: ' + post_username);
            let post_password = $('.post_password').val();
            console.log('post_password: ' + post_password);
            let post_first_name = $('.post_first_name').val();
            console.log('post_first_name: ' + post_first_name);
            let post_last_name = $('.post_last_name').val();
            console.log('post_last_name: ' + post_last_name);
            let post_phone = $('.post_phone').val();
            console.log('post_phone: ' + post_phone);
            let post_company = $('.post_company').val();
            console.log('post_company: ' + post_company);
            let ci_csrf_token = $("input[name=ci_csrf_token]").val();
            console.log('ci_csrf_token: ' + ci_csrf_token);
            let post_groups = [];
            $(".post_groups").find('option:selected').each(function () {
                post_groups.push($(this).val());
            });
            console.log('post_groups: ' + post_groups);
            console.log(post_groups);

            $.ajax({
                method: "POST",
                url: linkapi + "/postuser",
                data:
                {
                    'ci_csrf_token': ci_csrf_token,
                    'post_user_email': post_user_email, 'post_username': post_username,
                    'post_password': post_password, 'post_first_name': post_first_name,
                    'post_last_name': post_last_name, 'post_phone': post_phone,
                    'post_company': post_company, 'post_groups': post_groups
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                if(data.errorcode==0){
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_user').prop('disabled', false);
                        swal("","Thêm user thành công!","success");
                    }, 10);
                }
                else{
                    setTimeout(function () {
                        $('#loading_fa').removeClass('spinner');
                        $('#submit_user').prop('disabled', false);
                        swal("","Thêm user thất bại!","error");
                    }, 10);
                }
                
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
            }).fail(function (jqXHR, exception) {
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#submit_user').prop('disabled', false);
                }, 10);
            });
        }
        
    });

    var validator_put = $('#user_put').data('bootstrapValidator');

    $(document).on('click', '.update_user', function () {

        validator_put.validate();
        if (validator_put.isValid()) {

            // Make the ajax call here.
            // variable
            $('#loading_fa').addClass('spinner');
            $('#edit_user').prop('disabled', true);
            let post_password = $('.post_password').val();
            console.log('post_password: ' + post_password);
            let post_first_name = $('.post_first_name').val();
            console.log('post_first_name: ' + post_first_name);
            let post_last_name = $('.post_last_name').val();
            console.log('post_last_name: ' + post_last_name);
            let post_phone = $('.post_phone').val();
            console.log('post_phone: ' + post_phone);
            let post_company = $('.post_company').val();
            console.log('post_company: ' + post_company);
            let ci_csrf_token = $("input[name=ci_csrf_token]").val();
            console.log('ci_csrf_token: ' + ci_csrf_token);
            let put_id = $('.put_id').val();
            console.log('put_id: ' + put_id);
            let post_groups = [];
            $(".post_groups").find('option:selected').each(function () {
                post_groups.push($(this).val());
            });
            console.log('post_groups: ' + post_groups);
            console.log(post_groups);

            $.ajax({
                method: "POST",
                url: linkapi + "/updateuser/" + put_id,
                data:
                {
                    'ci_csrf_token': ci_csrf_token,'put_id':put_id,
                    'post_password': post_password, 'post_first_name': post_first_name,
                    'post_last_name': post_last_name, 'post_phone': post_phone,
                    'post_company': post_company, 'post_groups': post_groups
                }
            }).done(function (data) {
                console.log("Data Saved: " + data);
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#edit_user').prop('disabled', false);
                    swal("","Updated!","success");
                }, 10);
                
                //ajax done
                
                $('.selectpicker').selectpicker('refresh')
            }).fail(function (jqXHR, exception) {
                setTimeout(function () {
                    $('#loading_fa').removeClass('spinner');
                    $('#edit_user').prop('disabled', false);
                }, 10);
            });
        }
        
    });
    

});