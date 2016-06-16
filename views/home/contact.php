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
    <title>联系我们</title>
    <script src="js/home/jquery-1.8.3.min.js"></script>
    <link href="css/home/contact.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/home/base.css"/>
    <script type="text/javascript">
        var saveUrl = "<?=yii::$app->urlManager->createUrl('home/savecontact')?>";
        var boardUrl = "<?=yii::$app->urlManager->createUrl('home/contact')?>";
    </script>
    <script src="js/home/popup.js"></script>
    <script src="js/home/contact.js"></script>
</head>

<body>
    <?=$this->render('login')?>
    <?=$this->render('header',['column'=>'aboutus'])?>
	<!--content部分-->
    <div class="center">
        <div class="title"><a href="<?=yii::$app->urlManager->createUrl('home/aboutus')?>">关于我们</a>&nbsp;>&nbsp;<a href="<?=yii::$app->urlManager->createUrl('home/contact')?>">联系我们</a>
        </div>
        <div class="content">           
            <div class="introduce">
                <a class="p_1">联系我们</a>
                <p>武汉凯丽金生物科技有限公司<br />教育部轻工清洁生产研究生创新中心</p>
                <ul>
                    <li><img src="images/home/images_contact/address.png" /><a style="letter-spacing:30px;margin-left:20px;">地址</a><a style="margin-left:-23px;">:</a><a style="margin-left:3px;">湖北工业大学校内北区</a></li>
                    <li><img src="images/home/images_contact/phone.png" /><a style="letter-spacing:30px;margin-left:20px;">电话</a><a style="margin-left:-23px;">:</a><a style="margin-left:3px;">027-59750629</a></li>
                    <li><a style="margin-left:130px;">027-59750630</a></li> 
                    <li><img src="images/home/images_contact/fax.png" /><a style="margin-left:20px;">企业传真</a><a style="margin-left:3px;">:</a><a style="margin-left:3px;">027-59750628</a></li>
                    <li><img src="images/home/images_contact/email.png" /><a style="margin-left:20px;">企业邮箱</a><a style="margin-left:3px;">:</a><a style="margin-left:3px;">hgdmjs@163.com</a></li>
                    <li style="margin-top:3px"><img src="images/home/images_contact/uri.png" /><a style="margin-left:20px;">企业网址</a><a style="margin-left:3px;">:</a><a style="margin-left:3px;">www.hgdm.com</a></li>
                </ul>
            </div>
            <div class="introduce_center"></div>
            <img class="balloon" src="images/home/images_contact/balloon.png" />
            <div class="write">
                <img class="weixin_1" src="images/home/images_contact/weixinerweima.png" /><br />
                <img class="map" src="images/home/images_contact/map.png" />
                <div class="write_1">
                <form>
                    <ul>
                        <li><a class="write_2">留言</a></li>
                        <li class="name_tell"><a>姓名:</a><input type="text" size="18px" name="name" id="name"  class="input-text"/></li>
                        <li class="name_tell"><a>手机:</a><input type="text" size="18px" name="mobilephone" id="mobilephone"  class="input-text"/></li>
                        <li><textarea type="text" name="content" id="content"  class="input-text"style="border:none;" placeholder="让我们了解您的需求" /></textarea></li>
                    </ul>
                </form>
                    <a class="submit" href="javascript:send();void(0);">提交</a>
            	</div>
            </div>
            <div class="underline"></div>
        </div>
    </div>
    <!--下面是footer部分-->
    <?=$this->render('footer',['color' => '#e5dde6'])?>
    <!--分享和侧悬浮-->
    <?=$this->render('share')?>
    <!--右悬浮-->
    <?=$this->render('sidebox')?>
</body>
</html>
