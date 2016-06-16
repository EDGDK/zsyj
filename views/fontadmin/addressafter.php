<?php
/**
 * Created by PhpStorm.
 * User: xfk
 * Date: 2016/4/18
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
	<title>收货地址</title>
	<link rel="stylesheet" type="text/css" href="css/home/order.css" />
	<link rel="stylesheet" type="text/css" href="css/home/aside_base.css" />
	<link rel="stylesheet" type="text/css" href="css/home/base.css">
	<link rel="stylesheet" type="text/css" href="css/home/new_address.css">

	<script src="js/home/jquery-1.8.3.min.js"></script>
	<script src="js/home/plus_add.js"></script>
	<script src="js/home/cityJson.js" type="text/javascript"></script>
	<script src="js/home/Popt.js"></script>
	<script src="js/home/citySet.js"></script>
	<script>
		var addqUrl = "<?=yii::$app->urlManager->createUrl('fontadmin/addaddress2')?>";
		var deladdUrl = "<?=yii::$app->urlManager->createUrl('fontadmin/deladdress')?>";
		var updateUrl = "<?=yii::$app->urlManager->createUrl('fontadmin/updateaddress')?>";
	</script>
</head>
<body>
<!--新增收货地址弹框-->
<div class="popupall">
	<div class="Popup">
		<div class="Popup_top">
			<a>新增收货地址</a>
			<a class="shouhuodz"><img src="images/home/images_14_address/Shut down.png" ></a>
		</div>
		<ul>
			<li style="color:#ff3f3f;line-height:30px;">&nbsp;&nbsp;&nbsp;*</li>
			<li><a>收货人&nbsp;:</a></li>
			<li><input type="text" name="" id='username_1' style="border:#ccc 1px solid;width:210px;height:28px;" /></li>
		</ul>
		<ul>
			<li style="color:#ff3f3f;line-height:30px;">*</li>
			<li><a>所在地区&nbsp;:</a></li>
			<li><input type="text"  name="city" id='city'  value="请选择地区" name="" style="border:#ccc 1px solid;width:210px;height:28px;" /></li>
		</ul>
		<div class='header_hide'>
			<ul >
				<li style="color:#ff3f3f;line-height:30px;">*</li>
				<li><a>详细地址&nbsp;:</a></li>
				<li><input type="text" name="" id='add_address' style="border:#ccc 1px solid;width:318px;height:28px;" /></li>
			</ul>
			<ul >
				<li style="color:#ff3f3f;line-height:30px;">*</li>
				<li><a>手机号码&nbsp;:</a></li>
				<li><input type="text" name="" id="add_tel" style="border:#ccc 1px solid;width:210px;height:28px;" /></li>
			</ul>
			<ul >
				<li><a>&nbsp;&nbsp;邮政编码&nbsp;:</a></li>
				<li><input type="text" name="" id="add_code" style="border:#ccc 1px solid;width:210px;height:28px;" /></li>
			</ul>
			<div class="preservation">
				<a href="javascript:addAddr();">保存新地址</a>
			</div>
		</div>
	</div>
</div>

<!--header部分开始-->
<?=$this->render('..\home\header',['column'=>'shop'])?>
<div class="content">
	<div class="content_top">
		<div class="content_nav">
			<a href="<?=yii::$app->urlManager->createUrl('fontadmin/personaldata')?>">我的紫薯</a><span>></span><a href="<?=yii::$app->urlManager->createUrl('fontadmin/addressafter')?>">收货地址</a></div>
		<div class="content_line"></div>
	</div>
	<div class="big">
		<div class="aside">
			<div class="aside_title">我的紫薯</div>
			<ul>
				<li><a href="<?=yii::$app->urlManager->createUrl('fontadmin/personaldata')?>">个人资料</a></li>
				<li><a href="<?=yii::$app->urlManager->createUrl('fontadmin/password')?>">修改密码</a></li>
				<li><a href="<?=yii::$app->urlManager->createUrl('fontadmin/addressafter')?>" class="now">收货地址</a></li>
				<li><a href="<?=yii::$app->urlManager->createUrl('fontadmin/mymessages')?>">我的留言</a></li>
				<li><a href="<?=yii::$app->urlManager->createUrl('fontadmin/orderafter')?>">我的订单</a></li>
			</ul>
		</div>
		<div class="nav_right">
			<div class="one"><span>收货地址</span></div>
			<div class="two">
				<a ><div class="news">新增收货地址</div></a>
			</div>
			<?if(!is_null($userinfos)){?>
				<?foreach ($userinfos as $index => $val){?>
					<div class="three">
						<div class="list_one"><span class="spacing_one">收货人</span><span class="address_one">:
								<input name="recipientName" id="<?=htmlspecialchars($val->id)?>recipientName" class="view" type="text" disabled  value="<?=htmlspecialchars($val->recipientName)?>"/></span></div>
						<div class="list_two"><span class="spacing_two">所在地区</span><span class="address_two">:<input id="<?=htmlspecialchars($val->id)?>areaAddress"  type="text" disabled  value="<?=htmlspecialchars($val->areaAddress)?>" class="view" name="city" /></span></div>
						<div class="list_five"><span class="spacing_five">详细地址</span><span class="address_two">:<input id="<?=htmlspecialchars($val->id)?>detailAddress" class="view"  type="text" disabled value="<?=htmlspecialchars($val->detailAddress)?>" /></span></div>
						<div class="list_three"><span class="spacing_two">电话</span><span class="address_two">:<input id="<?=htmlspecialchars($val->id)?>mobilephone" class="view" type="text" disabled value="<?=htmlspecialchars($val->mobilephone)?>" /></span></div>
						<div class="list_four"><span>邮政编码</span><span class="address_four">:<input id="<?=htmlspecialchars($val->id)?>postcode" class="view" type="text" disabled value="<?=htmlspecialchars($val->postcode)?>" /></span></div>
						<div class="form">
							<button class="bord" valid="<?=$val->id?>" id="bianji">编辑</button>
							<button class="bord" val="<?=$val->id?>" id="hide" type="button">删除</button>
						</div>
					</div>
				<?}?>
			<?}?>
		</div>
	</div>
	<!--下面是footer部分-->
	<?=$this->render('..\home\footer',['color' => '#f7f7f7'])?>
	<!--分享和侧悬浮-->
	<?=$this->render('..\home\share')?>
	<!--右悬浮-->
	<?=$this->render('..\home\sidebox')?>
</div>
<script>
	function addAddr(){
		username = $('#username_1').val();
		citys = $('#city').val();
		add_address = $('#add_address').val();
		add_tel = $('#add_tel').val();
		add_code= $('#add_code').val();
		hcity = $('input[name="hcity"]').attr('data-id');
		hcityname = $('input[name="hcity"]').attr('value');
		var ulArray = new Array();
		ulArray.push('<div class="three">');
		ulArray.push('<div class="list_one"><span class="spacing_one">收货人</span><span class="address_one">:<input class="view" type="text" disabled  value='+username+'></span></div>');
		ulArray.push('<div class="list_two"><span class="spacing_two">所在地区</span><span class="address_two">:<input class="view" name="city"  type="text" disabled value='+citys+' ></span></div>');
		ulArray.push('<div class="list_five"><span class="spacing_five">详细地址</span><span class="address_two">:<input class="view"  type="text" disabled value='+add_address+' ></span></div>');
		ulArray.push('<div class="list_three"><span class="spacing_two">电话</span><span class="address_two">:<input class="view" type="text" disabled value='+add_tel+' ></span></div>');
		ulArray.push('<div class="list_four"><span>邮政编码</span><span class="address_four">:<input class="view" type="text" disabled value='+add_code+' ></span></div>');
		ulArray.push('<div class="form">');
		ulArray.push('<button class="bord" id="bianji">编辑</button>');
		ulArray.push('<button class="bord" id="hide" type="button"><a>删除</a></button>');
		ulArray.push('</div>');
		ulArray.push('</div>');

		$('div.nav_right').append(ulArray.join(''));
		var paraStr = '';
		paraStr = "username=" + username + "&city=" + citys + "&address=" + add_address + "&tel=" + add_tel + "&postcode=" + add_code + "&hcity=" + hcity + "&hcityname=" + hcityname;
		$.ajax({
			url: addqUrl,
			type: "post",
			dataType: "text",
			data: paraStr,
			async: false,
		})
		$('.popupall').css('display','none');
		loadJs();
	}
</script>

<!--地址选择插件-->
</body>
</html>
