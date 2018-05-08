$(document).ready(function() {
    // Generate a simple captcha
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    };
    $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

    $('#defaultForm').bootstrapValidator({
//        live: 'disabled',
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {

            uname: {
                message: '用户名无效',
                validators: {
                    notEmpty: {
                        message: '用户名是必需的，不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 20,
                        message: '用户名必须大于6和小于20个字符长'
                    },
                    regexp: {
                        regexp: /^[\w!@#$%\^&*()-=_+]/,
                        message: '用户名只能由字母、数字、点和下划线组成'
                    },
                    // remote: {
                    //     url: 'remote.php',
                    //     message: '用户名不可用'
                    // },
                    different: {
                        field: 'pwd',
                        message: '用户名和密码不能彼此相同'
                    }
                }
            },
            mail: {
                validators: {
                    notEmpty: {
                        message: '邮箱不能为空'
                    },
                    regexp: {
                        regexp: /[\w!#$%&\'*+\/=?^_`{|}~-]+(?:\.[\w!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?/,
                        message: '邮箱不可用'
                    }
                }
            },
            pwd: {
                validators: {
                    notEmpty: {
                        message: '密码是必需的，不能为空'
                    },

                    regexp: {
                        regexp: /^[\w!@#$%\^&*()-=_+]/,
                        message: '用户名只能由字母、数字、点和下划线组成，8到20位'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '用户名必须大于8和小于20个字符长'
                    },
                    different: {
                        field: 'uname',
                        message: '密码不能与用户名相同'
                    }
                }
            },
            confirmPassword: {
                validators: {
                    notEmpty: {
                        message: '确认密码是必需的，不能为空'
                    },
                    identical: {
                        field: 'pwd',
                        message: '密码及其确认不一样'
                    },
                    different: {
                        field: 'uname',
                        message: '密码不能与用户名相同'
                    }
                }
            },
            birth: {
                validators: {
                    regexp: {
                        regexp: /^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/,
                        message: '生日失效'
                    }
                }
            },
            sex: {
                validators: {
                    notEmpty: {
                        message: '性别不能为空'
                    }
                }
            }

        }
    });
    $('#LoginForm').bootstrapValidator({
//        live: 'disabled',
        message: '不能为空',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {

            uname: {
                message: '用户名无效',
                validators: {
                    notEmpty: {
                        message: '用户名是必需的，不能为空'
                    },
                    stringLength: {
                        min: 6,
                        max: 20,
                        message: '用户名必须大于6和小于20个字符长'
                    },
                    regexp: {
                        regexp: /^[\w!@#$%\^&*()-=_+]/,
                        message: '用户名只能由字母、数字、点和下划线组成'
                    },
                    // remote: {
                    //     url: 'remote.php',
                    //     message: '用户名不可用'
                    // },
                    different: {
                        field: 'pwd',
                        message: '用户名和密码不能彼此相同'
                    }
                }
            },

            pwd: {
                validators: {
                    notEmpty: {
                        message: '密码是必需的，不能为空'
                    },

                    regexp: {
                        regexp: /^[\w!@#$%\^&*()-=_+]/,
                        message: '用户名只能由字母、数字、点和下划线组成，8到20位'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '用户名必须大于8和小于20个字符长'
                    },
                    different: {
                        field: 'uname',
                        message: '密码不能与用户名相同'
                    }
                }
            }


        }
    });

    // Validate the form manually


    $('#resetBtn').click(function() {
        $('#defaultForm').data('bootstrapValidator').resetForm(true);
    });
    $('#btnL').on('click', function () {
        $('#myModalL').modal('show');
    });

    $('#btnR').on('click', function () {
        $('#myModalR').modal('show');
    });
    $('#login').on('click', function () {
        $('#myModalL').modal('show');
    });
    $('#reg').on('click', function () {
        $('#myModalR').modal('show');
    });
    $('#reg_login').on('click', function () {
        $("#myModalR").modal('hide');
        $('#myModalL').modal('show');
    });
    $('#login_reg').on('click', function () {
        $('#myModalL').modal('hide');
        $('#myModalR').modal('show');
    });

});
