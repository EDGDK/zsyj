/**
 * Created by Administrator on 2016/4/2.
 */
$(document).ready(function() {
    $("#btn").click(function () {

        $("#full").show();
    });
    $(".close").click(function () {
        $("#full").hide();
    });


    <!--下拉-->
    $('.gywm').hover(function(){
        $('.xiala').css('display','block');
    },function(){
        $('.xiala').css('display','none');
    });
});
