<?php
/**
 * Created by PhpStorm.
 * User: xfk
 * Date: 2016/3/9
 * Time: 12:10
 */
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;


?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?><!--生成一个替换字符，表示css和js的引用代码在这里显示-->

        <link href="css/reset.css" rel="stylesheet" type="text/css">
        <link href="css/system.css" rel="stylesheet" type="text/css">
        <link href="css/public.css" rel="stylesheet" type="text/css">
        <link href="css/table_form.css" rel="stylesheet" type="text/css">
        <!--TAB样式-->
        <link href="css/tabpanel/core.css" rel="stylesheet" type="text/css">
        <link href="css/tabpanel/TabPanel.css" rel="stylesheet" type="text/css">
        <link href="css/tabpanel/Toolbar.css" rel="stylesheet" type="text/css">
        <link href="css/tabpanel/WindowPanel.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <!--弹窗-->
        <script type="text/javascript" src="js/dialog/dialog.js"></script>
        <script type="text/javascript" src="js/styleswitch.js"></script>
        <script type="text/javascript" src="js/hotkeys.js"></script>
        <script type="text/javascript" src="js/jquery.sGallery.js"></script>
        <!--表单验证-->
        <script language="javascript" type="text/javascript" src="js/formvalidatorregex.js" charset="utf-8"></script>
        <script language="javascript" type="text/javascript" src="js/formvalidator.js" charset="utf-8"></script>
        <!--TAB JS-->
        <script type="text/javascript" src="js/tabpanel/Fader.js"></script>
        <script type="text/javascript" src="js/tabpanel/TabPanel.js"></script>
        <script type="text/javascript" src="js/tabpanel/Math.uuid.js"></script>
        <script type="text/javascript" src="js/tabpanel/Toolbar.js"></script>
        <script type="text/javascript" src="js/tabpanel/WindowPanel.js"></script>
        <script type="text/javascript" src="js/tabpanel/Drag.js"></script>
        <!--弹出图片-->
        <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <!--日历-->
        <link rel="stylesheet" type="text/css" href="js/calendar/win2k.css">
        <link rel="stylesheet" type="text/css" href="js/calendar/jscal2.css">
        <link rel="stylesheet" type="text/css" href="js/calendar/border-radius.css">
        <link rel="stylesheet" type="text/css" href="js/calendar/calendar-blue.css">
        <script type="text/javascript" src="js/calendar/calendar.js"></script>
        <script type="text/javascript" src="js/calendar/en.js"></script>
        <!--公共函数-->
        <script type="text/javascript" src="js/common.js"></script>
        <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
        <script type="text/javascript">
            var listdictUrl = "<?=yii::$app->urlManager->createUrl('dict/listdict')?>";
            var insertUrl = "<?=yii::$app->urlManager->createUrl('user-order/insert')?>";
            var listUrl  = "<?=yii::$app->urlManager->createUrl('user-order/list')?>";
        </script>
        <script type="text/javascript" src="js/admin/userorder/add.js"></script>
    </head>
    <body>
        <div class="pad-lr-10">
            <form name="myform" action="" method="post" id="myform" target="iframeId">
                <div class="pad_10">
                    <div style='overflow-y:auto;overflow-x:hidden' class='scrolltable'>
                        <table width="100%" cellspacing="0" class="table_form contentWrap">
                            <tr>
                                <th>负责人：</th>
                                <td><input type="text" style="width:250px;" name="principal" id="principal"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>收件人：</th>
                                <td><input type="text" style="width:250px;" name="recipientName" id="recipientName"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>电话号码：</th>
                                <td><input type="text" style="width:250px;" name="mobilephone" id="mobilephone"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>区域地址：</th>
                                <td><input type="text" style="width:250px;" name="areaAddress" id="areaAddress"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>详细地址：</th>
                                <td><input type="text" style="width:250px;" name="detailAddress" id="detailAddress"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>邮政编码：</th>
                                <td><input type="text" style="width:250px;" name="postcode" id="postcode"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>订单总金额：</th>
                                <td><input type="text" style="width:250px;" name="totalCost" id="totalCost"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>订单号：</th>
                                <td><input type="text" style="width:250px;" name="orderCode" id="orderCode"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>订单类别：</th>
                                <td><select name="orderType" id="orderType" style=""></td>
                            </tr>
                            <tr>
                                <th>订单状态：</th>
                                <td><select name="orderState" id="orderState" style=""></td>
                            </tr>
                            <tr>
                                <th>下单时间：</th>
                                <td>
                                    <input id="orderdateTime" name="orderdateTime" type="text" value="" class="date">
                                    <script type="text/javascript">
                                        Calendar.setup({
                                            weekNumbers: true,
                                            inputField : "orderdateTime",
                                            trigger    : "orderdateTime",
                                            dateFormat: "%Y-%m-%d %k:%M:%S",
                                            showTime: true,
                                            minuteStep: 1,
                                            onSelect   : function() {this.hide();}
                                        });
                                    </script>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="bk10"></div>
                </div>
            </form>
            <div class="table-list">
                <div class="rightbtn">
                    <input type="button" class="buttonsave" value="增加" name="dosubmit" onclick="add()" />
                    <input type="button" class="buttonclose" value="关闭" name="dosubmit"  onclick="window.top.$.dialog.get('userorder_add').close();"/>
                </div>
            </div>
        </div>

    </body>
</html>
<?php $this->endPage() ?>