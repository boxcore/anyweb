$(function() {
    $("#KinSlideshow").KinSlideshow({
        moveStyle: "right",
        titleBar: {titleBar_height: 30, titleBar_bgColor: "#08355c", titleBar_alpha: 0.7},
        titleFont: {TitleFont_size: 14, TitleFont_color: "#FFFFFF", TitleFont_weight: "normal"},
        btn: {btn_bgColor: "#FFFFFF", btn_bgHoverColor: "#1072aa", btn_fontColor: "#000000",
            btn_fontHoverColor: "#FFFFFF", btn_borderColor: "#cccccc",
            btn_borderHoverColor: "#1188c0", btn_borderWidth: 1}
    });
    $(".KinSlideshow_titleBar > h2").css("line-height", "25px");

    $(document).bind('mousemove scroll', function() {
        tankuang();
    });
    function tankuang() {
        var y = document.body.clientWidth - 170;
        var x = $(window).scrollTop() + 200;
        $("#consult_chat_box").css({"z-index": "9999", left: y, top: x, clear: "both", position: "absolute"});
    }
    tankuang();


    $(".lazy").scrollLoading();

});
