var waitTime = 30;
var timmer = null;
var register = {
    init: function() {
        register.popup();
        /*点击获取验证码*/
        $(".btnGetValid").click(function() {
            var form = $(this).parents("form");
            $(".txtPhone").val(form.find(".txtPhone").val());
            register.getValid(form);
        });
        /*点击提交表单*/
        $(".btnSubmit2").click(function() {
            var form = $(this).parents("form");
			
			console.log(form);
            register.verifyForm(form);
            return false;
        });
    },
    /*点击立即诊断弹窗*/
    popup: function() {
        $(".btnSubmit1").click(function() {
            var form = $(this).parents("form");
            if (register.verifyName(form) == false) {
                register.showNameErr();
                return false;
            } else {
                $(".popup,.pop_mask").show();
                return false;
            }
        });
        $(".shares_btn").click(function() {
			 
			 // if ($('.stockCode2').val() == '') {
                 // register.showMsg('提示', '请输入股票代码！', function() {
				// $('.stockCode2').focus();
			// });
                // return false;
			// var reg = /\d{6}/;
			// return reg.test($('.stockCode2').val());
			
			 var reg = /\d{6}/;
        if ($('.stockCode2').val() == '' || $('.stockCode2').val() == '请输入股票代码') {
			   register.showMsg('提示', '请输入股票代码！', function() {
				 $('.stockCode2').focus();
				 });
            return false;
        }
		else if(reg.test($('.stockCode2').val())==false){
			
			    register.showMsg('提示', '请输入正确股票代码！', function() {
				    $('.stockCode2').focus();
				 });
				   return false;
   
            } else {
                $(".popup,.pop_mask").show();
                return false;
            }
			
			
            //register.clearTimeout();
          //  $(".popup,.pop_mask").show();
          //  return false;
        });
        $(".close").click(function() {
            //register.clearTimeout();
            $(".popup,.pop_mask").hide();
            return false;
        });
    },
    /*倒计时*/
    refresh: function() {
        //var waitTime = 30;
        console.log(waitTime);
        waitTime--;
        if (waitTime > 0) {
            $('.btnGetValid').val(waitTime + 's后重新获取');
            timmer = setTimeout(function() {
                register.refresh();
            }, 1000);
        } else {
            waitTime = 30;
            $('.btnGetValid').val('获取验证码');
        }
    },
    /*终止倒计时*/
    clearTimeout: function() {
        clearTimeout(timmer);
        waitTime = 30;
        $('.btnGetValid').val('获取验证码');
    },
    /*获取验证码*/
    getValid: function(form) {
        if (register.verifyPhone(form) == true) {
            //clearTimeout(timmer);
            timmer = setTimeout(function() {
                register.refresh()
            }, 1000);
            $.post("/ts/index/send_sms", {
                appid: form.find('.appid').val(),
                mobile: form.find('.txtPhone').val()
            }, function(data) {
                eval('var json = ' + data);
                if (json.success) {
                    register.showMsg('提示', '验证码已发送！');
                } else {
                    register.showMsg('提示', json.errmsg);
                }
                if (!json.success && timmer) {
                    register.clearTimeout(timmer);
                }
            });
        } else {
            register.showPhoneErr();
            return false;
        }
    },
    /*表单验证*/
    verifyForm: function(form) {
        if (register.verifyPhone(form) == true) {
          
			 register.submitt(form);
        }else {
            register.showPhoneErr();
            return false;
        }
        /*if (register.verifyPhone(form) == true) {
            if (register.verifyCode(form) == false) {
                register.showNumErr();
                return false;
            } else { //手机，验证码都对时
                if (form.find(".txtTrueName").length > 0) { //如果有股票代码
                    if (register.verifyName(form) == false) {
                        register.showNameErr();
                        return false;
                    } else {
                        //return true;
                        register.submitt(form);
                    }
                } else { //没有股票代码
                    // return true;
                    register.submitt(form);
                }
            }
        } else {
            register.showPhoneErr();
            return false;
        }*/
    },
    /*表单验证提交*/
    submitt: function(form) {
		var scode = $('.stockCode').val()?$('.stockCode').val():$('.stockCode2').val();
		var parmstr = {
           phone:$('.txtPhone').val(),
           code:scode
		};
		
		console.log(parmstr,'aaaaaaaa');
	
		$.post("submit.php",parmstr,function(msg){
			console.log(msg,'msg');
				  // 	如果提交成功
				   if(msg.status=='1'){
					   register.showMsg('提示', msg.tips);
						$('.txtValidNum').val('');
                      $('.txtTrueName').val('');
                      $('.txtPhone').val('');

				   }else{
					    register.showMsg('提示', msg.tips);
				   }

			},'json');
			
			return false;	
        //var ajaxUrl=form.attr("action");
        // $.ajax({
            // type: "POST",
            // url: 'submit.php',
            // data: $(form).serialize(), // 你的formid
            // dataType: "json",
            // async: false,
            // error: function(request) {
                // try {
                    // console.log(json.errmsg);
                    // register.showMsg('提示', '提交失败！');
                // } catch (e) {}
            // },
            // success: function(data) {
				// console.log(data,'daa');
                // if (data.success != false) {
                    // register.showMsg('提示', '提交成功！');
                    // $('.txtValidNum').val('');
                    // $('.txtTrueName').val('');
                    // $('.txtPhone').val('');
                // } else {
                    // console.log(data);
                    // register.showMsg('提示', data.errmsg);
                // }
            // }
        // });
    },
    /*验证手机*/
    verifyPhone: function(form) {
        var reg = new RegExp("^(13|15|18|17)[0-9]{9}$");
        return reg.test(form.find('.txtPhone').val());
    },
    /*验证手机验证码*/
    // verifyCode: function(form) {
        // return form.find('.txtValidNum').val() != '' && form.find('.txtValidNum').val() != '请输入验证码';
    // },
    /*验证股票代码*/
    verifyName: function(form) {
        var reg = /\d{6}/;
        if (form.find('.txtTrueName').val() == '' || form.find('.txtTrueName').val() == '请输入股票代码') {
            return false;
        }
        return reg.test(form.find('.txtTrueName').val());
    },
    /*显示手机错误信息*/
    showPhoneErr: function() {
        register.showMsg('提示', '请输入正确的手机号！', function() {
            $('.txtPhone').focus();
        });
    },
    /*显示手机验证码错误信息*/
    // showNumErr: function() {
        // register.showMsg('提示', '请输入验证码！', function() {
            // $('.txtValidNum').focus();
        // });
    // },
    /*显示股票代码错误信息*/
    showNameErr: function() {
        register.showMsg('提示', '请输入股票代码！', function() {
            $('.txtTrueName').focus();
        });
    },
    /*信息显示*/
    showMsg: function(header, msg, callback) {
        easyDialog.open({
            container: {
                header: header,
                content: msg,
                noText: '确定',
                noFn: callback || function() {}
            }
        });
    }
}
register.init();