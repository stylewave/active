/**
 * Created by Administrator on 2016/7/11.
 */
var Domain = "";

function InitConfig(domain) {
    Domain=domain;
}
var qqClickEvent=function () {
    var url = Domain + "/promotion/page_event.jsonp";
    var postData = {
        "sign_id": $.cookie("qqUuid"),
        "event_type": '2',
        "event_value": '',
        "search_word": searchWord,
        "search_url": searchUrl,
        "action": action
    };
    $.ajax({
        url: url,
        type: "GET",
        data: postData,
        dataType: 'jsonp',
        success: function (data) {
            if (data.errorCode === 1) {
                console.info("success!");
            }
        }
    });
 };


var wechatCopy=function () {
    var url = Domain + "/promotion/page_event.jsonp";
    // var url = "http://10.0.27.75:8090/promotion/page_event.jsonp";
    var postData = {
        "sign_id": $.cookie("qqUuid"),
        "event_type": '4',
        "event_value": '',
        "search_word": searchWord,
        "search_url": searchUrl,
        "action": action
    };
    $.ajax({
        url: url,
        type: "GET",
        data: postData,
        dataType: 'jsonp',
        success: function (data) {
            if (data.errorCode === 1) {
                console.info("wechatCopy success!");
            }
        }
    });
};


$(function () {
    var timerLongTouch;
    $('.wechat-event')
        .on("touchstart", function (event) {
            // Timer for long touch detection
            timerLongTouch = setTimeout(function () {
                // Test long touch detection (remove previous alert to test it correctly)
                wechatCopy();
            }, 500);
        })
        .on("touchmove", function () {
            clearTimeout(timerLongTouch);
        })
        .on("touchend", function () {
            clearTimeout(timerLongTouch);
        });
});