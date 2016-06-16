<?php
/**
 * Created by PhpStorm.
 * User: liluoao
 * Date: 2016/4/3
 * Time: 12:10
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>紫薯原浆</title>
    <script type="text/javascript" src="js/home/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/home/ordercomplete.css"/>
    <link rel="stylesheet" type="text/css" href="css/home/base.css"/>
    <script>
        var detailUrl = "<?=yii::$app->urlManager->createUrl('fontadmin/orderdetail')?>";
    </script>
</head>

<body>
<!--header-->
<?=$this->render('..\home\header',['column'=>'shop'])?>

<div class="content">
    <div class="finished">
        <img src="images/home/images_Purchase/shop_LOGO.png" class="shop_LOGO" />
        <img src="images/home/images_Purchase/process_3_2.png" class="process" />

    </div>

    <div class="succeed">
        <img src="images/home/images_Purchase/icon_tick.png" class="icon_tick" />
        <div class="succeed_1">
            <span class="xiadan">您已下单成功！</span>
            <span class="song">货物寄送至：</span>
            <ul class="di2">
                <li><?=$userinfo->areaAddress?></li>
                <li><?=$userinfo->detailAddress?></li>
                <li><?=$userinfo->recipientName?></li>
                <li>收</li>
                <li><?=$userinfo->mobilephone?></li>
            </ul>
        </div>
        <a href="<?=yii::$app->urlManager->createUrl('fontadmin/orderafter')?>" class="list">订单列表</a>
        <span class="xian">|</span>
        <a href="javascript:detail('<?=$userorderid?>');" class="detial">订单详情</a>
    </div>
</div>
<script>
    function detail(id){
        var userStr = 'id='+id;
        $.ajax({
            url: detailUrl,
            type: "post",
            dataType: "text",
            data: userStr,
            async: false,
            success: function (text){
                if(text == ''){
                    $("#full").show();
                }else{
                    window.location.href = detailUrl+'&id='+text;
                }
            },
            error: function(text){
                alert(text);
            }
        })
    }
</script>

<!--下面是footer部分-->
<?=$this->render('..\home\footer',['color' => ''])?>
<!--分享和侧悬浮-->
<?=$this->render('..\home\share')?>
<!--右悬浮-->
<?=$this->render('..\home\sidebox')?>
</body>
</html>
