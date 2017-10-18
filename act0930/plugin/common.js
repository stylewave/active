/*****jquery 通用函数******/

function myajaxform(myform,myurl,mycfun,mysfun,myefun,mydatatype,mytype){
    if(!myform){
	alert("表单对象不能为空!");
	return false;
    }
    $(myform).submit(function(){
	if(!myurl){
	    alert("请输入url和回调函数!");
	    return false;
	}
	if(!mycfun){															//提交前函数
	    mycfun="";
	}
	if(!myefun){
	    //myefun="alert('get error!')";
		myefun="console.log('error')";
	}
	if(!mydatatype){
	    mydatatype='json';
	}
	if(!mytype){
	    mytype='POST';
	}
	var options = {                                                  		//ajaxform表单提交设置
	    //target:   '#divid2',                                        	//结果显示目标
	    url:        myurl,     											//action目标
	    type:  		mytype,
	    dataType:	mydatatype,											//默认值
	    beforeSubmit: function(){										//提交前
		return eval(mycfun);
	    },
	    success: function(msg){											//表单提交后
		eval(mysfun);
	    },
	    error : function(){
		eval(myefun);
	    }
	};
	$(this).ajaxSubmit(options);
	return false;
    });
};

function newajax(myurl,mysfun,myefun,mydatatype,mytype){
    if(!myurl){
	alert("请输入相关参数!");
	return false;
    }
        
    if(myefun == '' || myefun == null){
		//myefun="alert('get error!')";
		myefun="console.log('error')";
    }

    if(!mydatatype){
	mydatatype='json';		//html json
    }
    if(!mytype){
	mytype='GET';
    }
    var options = {                                                  	//ajaxform表单提交设置
	//target:   '#divid2',                                      //结果显示目标
	url:        myurl,     										//action目标
	type:  		mytype,
	dataType:	mydatatype,									    //默认值
	success: mysfun,
	error : myefun
    };
    $(this).ajaxSubmit(options);
    return false;
};


function myajax(myurl,mysfun,myefun,mydatatype,mytype){
    if(!myurl){
	alert("请输入相关参数!");
	return false;
    }
        
    if(myefun == '' || myefun == null){
		//myefun="alert('get error!')";
		myefun="console.log('error')";
    }

    if(!mydatatype){
	mydatatype='json'; 	//html json
    }
    if(!mytype){
	mytype='GET';
    }
    var options = {                                                  	//ajaxform表单提交设置
	//target:   '#divid2',                                      //结果显示目标
	url:        myurl,     										//action目标
	type:  		mytype,
	dataType:	mydatatype,									    //默认值
	success: function(msg){										//表单提交后
	    eval(mysfun);
	},
	error : function(){
	    eval(myefun);
	}
    };
    $(this).ajaxSubmit(options);
    return false;
};

//使用url传参数时.先加密处理
function ajax_encode (str) {
    str=encodeURIComponent(str);
    if (navigator.product == 'Gecko') str=str.replace("/%0A/g", "%0D%0A");
    //In IE, a new line is encoded as rn, while in Mozilla it's n
    return str;
}

function random_num(start, end){
    return Math.floor(Math.random() * (end - start) + start);
}



//打印js 对象值
function dump_obj(obj){
	var msg=""; //设置一个空的变量
	for(var i in obj) //变量I 在obj对象中循环，这行不能加引号
	msg +=i+" => "+obj[i]+"\n" //将i读到的值叫给msg变量
	alert(msg);
}

//ajax 成功后提示专用
/* alert-info warning danger success */
/*    fa-info warning ban    check*/
function show_tips(text,type,time,callback){
	var type=(typeof(type)=="undefined") ? 'info' : type;
	var time=(typeof(time)=="undefined") ? 3000 : time*1000;
	var icon;
	switch(type){
		case "info":
			icon='info';
		break;
		case "warning":
			icon='warning';
		break;
		case "danger":
			icon='ban';
		break;
		case "success":
			icon='check';
		break;	
		
	}
	
	
	 if ($(".modal-body").length > 0) {
          var mydiv = '<div id="modal_tips_plugin" class="navbar navbar-fixed-bottom alert alert-'+type+' alert-dismissable alert-fixed alert-modal-fixed fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><span id="modal_tips_plugin_text"><i class="icon fa fa-'+icon+'"></i>'+text+'</span></div>';
          if ($("#modal_tips_plugin").length > 0) {
			  $("#modal_tips_plugin").attr("class",'alert alert-'+type+' alert-dismissable alert-fixed alert-modal-fixed fade in');
            $("#modal_tips_plugin").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><span id="modal_tips_plugin_text"><i class="icon fa fa-'+icon+'"></i>'+text+'</span>');
          } else {
            $(".modal-body").append(mydiv);
          }
          $("#modal_tips_plugin").fadeIn('fast'); //下拉
          setTimeout(function() { //设置指定时间后的动作
            	$("#modal_tips_plugin").fadeOut('slow'); //上拉
				if(callback != '' || callback != null){
					eval(callback);
    			}
          }, time); //时间
        } else {
          var mydiv = '<div id="body_tips_plugin" class="navbar navbar-fixed-bottom alert alert-'+type+' alert-dismissable alert-fixed"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><span id="body_tips_plugin_text"><i class="icon fa fa-'+icon+'"></i>'+text+'</span></div>';
          /* alert-info warning danger success */
          /*    fa-info warning ban    check*/
          if ($("#body_tips_plugin").length > 0) {
			  $("#body_tips_plugin").attr("class",'navbar navbar-fixed-bottom alert alert-'+type+' alert-dismissable alert-fixed');
            $("#body_tips_plugin").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><span id="body_tips_plugin_text"><i class="icon fa fa-'+icon+'"></i>'+text+'</span>');
          } else {
            $("body").append(mydiv);
          }

          $("#body_tips_plugin").fadeIn('fast'); //下拉
          setTimeout(function() { //设置指定时间后的动作
            $("#body_tips_plugin").fadeOut('slow'); //上拉
			  	if(callback != '' || callback != null){
				eval(callback);
    			}
			
          }, time); //时间
        }
	
	
}


function check_string(value,type){
    switch(type){
	case "user":
	    var re=/^[a-zA-Z0-9_-]{4,16}$/i;
	    return re.test(value);
	    break;
	case "ename":
	    var re=/^[0-9a-z-_]+$/i;
	    return re.test(value);
	    break;
	case "name":
	    var longs=value.length;
	    if(longs>=2 && longs<20){
			return true;
		}else{
			return false;
		}
		
	    break;
	case "mail":
	    var re=/^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i;
	    return re.test(value);
	    break;
	case "qq":
	    var re=/^[0-9]{5,14}$/i;
	    return re.test(value);
	    break;	
	case "phone":		
		re=/^[0-9\-]{10,18}$/;		
	    return re.test(value);
	    break;	
	case "mobile":		
		re=/^1[3|4|5|6|7|8|9][0-9]{9}$/;		
	    return re.test(value);
	    break;	
	case "letter":
	    var re=/^[a-z\s]+$/i;
	    return re.test(value);
	    break;
	case "number":
	    var re=/^[0-9]+$/;
	    return re.test(value);
	    break;
	case "zip_code":
	    var re=/^[0-9]{6}$/;
	    return re.test(value);
	    break;	
	case "money":
	    var re=/^[0-9\.]+$/;
	    return re.test(value);
	    break;
	case "code":
	    var re=/^\w{4}$/;
	    return re.test(value);
	    break;
	case "chinese":
	    var re=/^[\u4e00-\u9fa5\w\-\(\)]{4,20}$/;
	    return re.test(value);
		break;
	case "title":
	    var re=/^.{4,20}$/;
	    return re.test(value);
		break;				
	case "max100":
		if(value>=100){
			return true; 	
		}else{
			return false;	
		}
	break;
	case "decimal":
		if(value>0 && value<=1){
			return true; 	
		}else{
			return false;	
		}
	break;
	
	case "length100":
		var longs=value.length;
		if(longs>=100){
			return false; 	
		}else{
			return true;
		}
	break;
	case "length20":
		var longs=value.length;
		if(longs>20){
			return false; 	
		}else{
			return true;
		}
	break;
	case "length30":
		var longs=value.length;
		if(longs>30){
			return false; 	
		}else{
			return true;
		}
	break;	
	default:
	break;
    }
	
}

function countdown(time,id){
	var day_elem = $(id).find('.day');
	var hour_elem = $(id).find('.hour');
	var minute_elem = $(id).find('.minute');
	var second_elem = $(id).find('.second');
	var end_time = new Date(time).getTime(),//月份是实际月份-1
	sys_second = (end_time-new Date().getTime())/1000;
	var timer = setInterval(function(){
		if (sys_second > 1) {
			sys_second -= 1;
			var day = Math.floor((sys_second / 3600) / 24);
			var hour = Math.floor((sys_second / 3600) % 24);
			var minute = Math.floor((sys_second / 60) % 60);
			var second = Math.floor(sys_second % 60);
			day_elem && $(day_elem).text(day);//计算天
			$(hour_elem).text(hour<10?"0"+hour:hour);//计算小时
			$(minute_elem).text(minute<10?"0"+minute:minute);//计算分钟
			$(second_elem).text(second<10?"0"+second:second);//计算秒杀
		} else { 
			clearInterval(timer);
		}
	}, 1000);
}