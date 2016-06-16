<?php
/*
 * @author liluoao
 * @date 2016-3-26
 */
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\common\Common;
use yii\data\Pagination;
use app\models\Dictitem;
use app\models\User;
use app\models\Inform;
use app\models\Receive;
use app\models\Department;

class InformController extends Controller
{
    public $layout = false; //设置使用的布局文件
    public $enableCsrfValidation = false;

    /*
    * @function 权限验证
    * @date 2016-4-17
    */
    public function beforeAction($action)
    {
        if(is_null(yii::$app->session['username']) || yii::$app->session['userType'] != '2')
        {
            $this->redirect(['login/index']);

            return false;
        }else{
            return parent::beforeAction($action); // TODO: Change the autogenerated stub

        }
    }


    /*
     * 跳转到通知列表页
     */
    public function actionList(){
        return $this->render('list');
    }

    /*
     * 跳转到增加通知页面
     */
    public function actionAdd()
    {
        if($id = Yii::$app->request->get('id')){
            $id = Yii::$app->request->get('id');
        }else{
            $id = Common::generateID();
        }
        return $this->render('add',[
            'id' => $id
        ]);
    }

    /*
     * 跳转到上传页面
     */
    public function actionUpload(){
        //实现上传

        if (Yii::$app->request->isPost) {

            $fileArg = Common::upload($_FILES,false,false);//当为上传图片是设置为true，当上传为文档时设置为false
            return $this->render('upload',[
                "fileArg" => $fileArg,
                "tag" => $fileArg['tag'],
            ]);
        }
        return $this->render('upload',[
            "tag" => "empty",
            "fileArg" =>[
                "fileName" => "",     //保存到数据库的文件名称
                "fileSaveUrl" =>"",//上传文件保存的路径
                "tag" => "",//当为success表示上传成功，当为error时表示文件过大或是文件类型不对
            ],
        ]);

    }

    /*
     * 通知列表页
     */
    public function actionListall()
    {
        $title = Yii::$app->request->get('title');
        $informType = Yii::$app->request->get('informType');
        $date1 = Yii::$app->request->get('_senderDateTime');
        $data2 = Yii::$app->request->get('senderDateTime_');
        $userId = Yii::$app->session['userId'];
        $para = array();
        $para['title'] = $title;
        $para['informType'] = $informType;
        $para['date1'] = $date1;
        $para['data2'] = $data2;
        $whereStr = 'a.receiverId = "' .$userId.'"';//查询条件
        if ($title != '') {
            $whereStr = $whereStr . " and title like '%" . $title . "%'";
        }
        if ($informType != '') {
            $whereStr = $whereStr . " and informType like '%" . $informType . "%'";
        }
        if ($date1 != '') {
            $whereStr = $whereStr . " and senderDateTime >= '" . $date1 . "%'";
        }
        if ($data2 != '') {
            $whereStr = $whereStr . " and senderDateTime <= '" . $data2 . "%'";
        }
        $query = new \yii\db\Query();
        $listInforms = $query->select('b.id AS id,b.title AS title,b.senderDateTime as senderDateTime,b.informType AS informType,b.isTop as isTop,b.click as click,b.sender as sender')
            ->from('zsyj_receive a')
            ->where($whereStr)
            ->rightJoin('zsyj_inform b','a.informId = b.id');

        $pages = new Pagination(['totalCount' => $listInforms->count(), 'pageSize' => Common::PAGESIZE]);//分页
        $models = $listInforms->offset($pages->offset)->limit($pages->limit)->orderBy('senderDateTime DESC,isTop ASC')->all();
        //字典反转
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_INFORM_TYPE'])
            ->all();
        $dictItems = Dictitem::find()
            ->where(['dictCode' => 'DICT_IS_TOP'])
            ->all();

        foreach ($models as $key => $data) {
            foreach ($dictItem as $index => $value) {
                if ($data['informType'] == $value->dictItemCode) {
                    $models[$key]['informType'] = $value->dictItemName;
                }
            }
            foreach ($dictItems as $index => $value) {
                if ($data['isTop'] == $value->dictItemCode) {
                    $models[$key]['isTop'] = $value->dictItemName;
                }
            }
        }//结束
        return $this->render('listall', [
            'informs' => $models,
            'pages' => $pages,
            'para' => $para,
        ]);
    }

    /*
     * 新增
     */
    public function actionInsert(){
        $inform = new Inform();
        $inform->id = Yii::$app->request->post('id');
        $inform->title = Yii::$app->request->post('title');
        $inform->senderId = Yii::$app->session['userId'];
        $inform->sender = Yii::$app->session['truename'];
        $inform->informType = Yii::$app->request->post('informType');
        $inform->content = Yii::$app->request->post('content');
        $inform->senderDateTime = Yii::$app->request->post('senderDateTime');
        $inform->attachUrls = Yii::$app->request->post('attachUrls');
        $inform->attachNames = Yii::$app->request->post('attachNames');
        $inform->isTop = Yii::$app->request->post('isTop');
        if($inform->save()){
            return 'success';
        }else{
            return 'fail';
        };
    }

    /*
     * 详情
     */
    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $inform = Inform::findOne($id);
        //字典反转
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_INFORM_TYPE'])
            ->all();
        $dictItems = Dictitem::find()
            ->where(['dictCode' => 'DICT_IS_TOP'])
            ->all();
        foreach ($dictItem as $index => $value) {
            if ($inform->informType == $value->dictItemCode) {
                $inform->informType = $value->dictItemName;
            }
        }
        foreach ($dictItems as $index => $value) {
            if ($inform->isTop == $value->dictItemCode) {
                $inform->isTop = $value->dictItemName;
            }
        }//结束
        return $this->render('detail',[
            'inform' => $inform,
        ]);
    }

    /*
     * 修改
     */
    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $inform = Inform::findOne($id);
        $title = Yii::$app->request->get('title');
        $informType = Yii::$app->request->get('informType');
        //发送2个字典项到视图里供选
        $typedict = Dictitem::find()->where(['dictCode' => 'DICT_INFORM_TYPE'])->all();
        $topdict = Dictitem::find()->where(['dictCode' => 'DICT_IS_TOP'])->all();
        return $this->render('edit',[
            'inform' => $inform,
            'typedict' => $typedict,
            'topdict' => $topdict,
        ]);
    }

    /*
     * 更新
     */
    public function actionUpdate(){
        $id = Yii::$app->request->post('id');
        $inform = Inform::findOne($id);
        $inform->title = Yii::$app->request->post('title');
        $inform->informType = Yii::$app->request->post('informType');
        $inform->content = Yii::$app->request->post('content');
        $inform->senderDateTime = Yii::$app->request->post('senderDateTime');
        $inform->attachUrls = Yii::$app->request->post('attachUrls');
        $inform->attachNames = Yii::$app->request->post('attachNames');
        $inform->isTop = Yii::$app->request->post('isTop');
        $inform->click = Yii::$app->request->post('click');

        if ($inform->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    /*
     * 删除单
     */
    public function actionDelete(){
        $id = Yii::$app->request->post("id");
        $inform = Inform::findOne($id);
        if($inform->delete())
        {
            return "success";
        }else{
            return "fail";
        }
    }

    /*
     * 多选删除
     */
    public function actionDeleteall()
    {
        $ids = Yii::$app->request->post("ids");
        $ids_array = explode('-',$ids);
        foreach($ids_array as $key => $data){
            Inform::deleteAll('id = :id',[':id'=>$data]);
        }
        return 'success';
    }

    /*
     * 选择接受通知的部门和员工
     */
    public function actionChoose()
    {
        $id = Yii::$app->request->get('id');
        $department = Department::find()->all();
        $user = User::find()->where('userType = 2')->all();
        return $this->render('choose',[
            'id' => $id,
            'department' => $department,
            'user' => $user,
        ]);
    }

    /*
     * 保存选择结果
     */
    public function actionSavechoose()
    {
        $informId = Yii::$app->request->post('id');
        $ids = Yii::$app->request->post('ids');
        $id = Yii::$app->session['userId'];
        //选出的user加入读该消息权
        $ids_array = explode('-',$ids);
        foreach($ids_array as $key => $data){
            $receive = new Receive();
            $receive->id = Common::generateID();
            $receive->informId = $informId;
            $receive->receiverId = $data;
            $receive->save();
        }
        //发布者有读取消息权
        $receive = new Receive();
        $receive->id = Common::generateID();
        $receive->informId = $informId;
        $receive->receiverId = $id;
        $receive->save();
        return 'success';
    }
}
