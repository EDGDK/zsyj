<?php
/**
 * Created by PhpStorm.
 * User: liluoao
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
        <!--文本编辑器 -->
        <script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="js/ckeditor/genEditor.js"></script>
        <!--公共函数-->
        <script type="text/javascript" src="js/common.js"></script>
        <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
        <!--日历-->
        <link rel="stylesheet" type="text/css" href="js/calendar/win2k.css"/>
        <link rel="stylesheet" type="text/css" href="js/calendar/jscal2.css"/>
        <link rel="stylesheet" type="text/css" href="js/calendar/border-radius.css"/>
        <link rel="stylesheet" type="text/css" href="js/calendar/calendar-blue.css"/>
        <script type="text/javascript" src="js/calendar/calendar.js"></script>
        <script type="text/javascript" src="js/calendar/en.js"></script>
        <script type="text/javascript">
            var saveUrl = "<?=yii::$app->urlManager->createUrl('article/save')?>";
            var listallUrl = "<?=yii::$app->urlManager->createUrl('article/listall')?>";
        </script>
        <script type="text/javascript" src="js/admin/article/add.js"></script>
    </head>
    <body>
        <div class="pad-lr-10">
            <form name="myform" action="" method="post" id="myform" target="iframeId">
                <div class="pad_10">
                    <div style='overflow-y:auto;overflow-x:hidden' class='scrolltable'>
                        <table width="100%" cellspacing="0" class="table_form contentWrap">
                            <tr>
                                <th width="100">类型：</th>
                                <td><select name="articleType" id="articleType" style="width:180px;"></td>
                            </tr>
                            <tr>
                                <th width="100">标题：</th>
                                <td><input type="text" style="width:400px;" name="title" id="title"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th width="100">作者：</th>
                                <td><input type="text" style="width:200px;" name="author" id="author"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th width="100">来源：</th>
                                <td><input type="text" style="width:200px;" name="source" id="source"  class="input-text"/></td>
                            </tr>
                            <tr>
                                <th>正&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文：</th>
                                <td><textarea style="width:500px;height:200px;" name="content" id="content" ></textarea></td>
                            </tr>
                            <tr>
                                <th width="100">发布日期：</th>
                                <td><input id="senderDateTime" name="senderDateTime" type="text" value="" class="date"/></td>
                                <script type="text/javascript">
                                    Calendar.setup({
                                        weekNumbers: true,
                                        inputField : "senderDateTime",
                                        trigger    : "senderDateTime",
                                        dateFormat: "%Y-%m-%d %k:%M:%S",
                                        showTime: true,
                                        minuteStep: 1,
                                        onSelect   : function() {this.hide();}
                                    });
                                </script>
                            </tr>
                            <tr>
                                <th>附&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件：</th>
                                <td>
                                    <input type="text" style="display:none;" name="attachUrls" id="attachUrls" class="input-text"/>
                                    <input type="text" style="display:none;" name="attachNames" id="attachNames" class="input-text"/>
                                    <iframe frameborder=0 width="100%" height=20px scrolling=no src="<?=yii::$app->urlManager->createUrl('article/upload')?>"></iframe>
                                </td>
                            </tr>
                            <tr>
                                <th width="100">是否置顶：</th>
                                <td><select name="isTop" id="isTop" style="width:180px;"></td>
                            </tr>

                        </table>
                    </div>
                    <div class="bk10"></div>
                </div>
            </form>
            <div class="table-list">
                <div class="rightbtn">
                    <input type="button" class="buttonsave" value="保存" name="dosubmit" onclick="add()" />
                    <input type="button" class="buttonclose" value="关闭" name="dosubmit"  onclick="window.top.$.dialog.get('article_add').close();"/>
                </div>
            </div>
        </div>

    </body>
</html>
    <script type="text/javascript">
        var contentEditor=genEditor('','content','');
    </script>
<?php $this->endPage() ?>