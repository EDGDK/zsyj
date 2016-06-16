<?php

namespace app\controllers;

use Yii;
use app\models\Userorder;
use app\models\Dictitem;
use yii\data\Pagination;
use app\common\Common;
class UserOrderController extends \yii\web\Controller
{
    public $layout = false;
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

    public function actionIndex()
    {
        return $this->render('index');
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 跳转到订单列表
    */
    public function actionList(){
        return $this->render('list');
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 获取订单列表
     */
    public function actionListall(){
        $principal = Yii::$app->request->get('principal');
        $recipientName = Yii::$app->request->get('recipientName');
        $mobilephone = Yii::$app->request->get('mobilephone');
        $areaAddress = Yii::$app->request->get('areaAddress');
        $totalCost = Yii::$app->request->get('totalCost');
        $orderState = Yii::$app->request->get('orderState');
        $orderType = Yii::$app->request->get('orderType');
        $orderdateTime_1 = Yii::$app->request->get('orderdateTime_1');
        $orderdateTime_2 = Yii::$app->request->get('orderdateTime_2');
        $para=array();
        $para['principal'] = $principal;
        $para['recipientName'] = $recipientName;
        $para['mobilephone'] = $mobilephone;
        $para['areaAddress'] = $areaAddress;
        $para['totalCost'] = $totalCost;
        $para['orderState'] = $orderState;
        $para['orderType'] = $orderType;
        $para['orderdateTime_1'] = $orderdateTime_1;
        $para['orderdateTime_2'] = $orderdateTime_2;
        $whereStr = '1=1';
        if($principal != ''){
            $whereStr = $whereStr." and principal like '%".$principal."%'";
        }
        if($recipientName != ''){
            $whereStr = $whereStr." and recipientName like '%".$recipientName."%'";
        }
        if($mobilephone != ''){
            $whereStr = $whereStr." and mobilephone like '%".$mobilephone."%'";
        }
        if($areaAddress != ''){
            $whereStr = $whereStr." and areaAddress like '%".$areaAddress."%'";
        }
        if($totalCost != ''){
            $whereStr = $whereStr." and totalCost like '%".$totalCost."%'";
        }
        if($orderState != ''){
            $whereStr = $whereStr." and orderState=".$orderState;
        }
        if($orderType != ''){
            $whereStr = $whereStr." and orderType like '%".$orderType."%'";
        }
        if($orderdateTime_1 != ''){
            $whereStr = $whereStr." and orderdateTime >= '".$orderdateTime_1."%'";
        }
        if($orderdateTime_2 != ''){
            $whereStr = $whereStr." and orderdateTime <= '".$orderdateTime_2."%'";
        }
        $userorders = Userorder::find()->where($whereStr);
        $pages = new Pagination(['totalCount' =>$userorders->count(), 'pageSize' => Common::PAGESIZE]);
        $models = $userorders->offset($pages->offset)->orderBy('orderdateTime DESC')->limit($pages->limit)->all();
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_ORDERSTATE'])
            ->all();
        $dictItemS = Dictitem::find()
            ->where(['dictCode' => 'DICT_ORDERTYPE'])
            ->all();
        foreach($models as $key=>$data) {
            foreach ($dictItem as $index => $value) {
                if ($data->orderState == $value->dictItemCode) {
                    $models[$key]->orderState = $value->dictItemName;
                }
            }
            foreach($dictItemS as $index=>$value){
                if($data->orderType == $value->dictItemCode){
                    $models[$key]->orderType = $value->dictItemName;
                }
            }
        }
        return $this->render('listall',[
            'userorders' => $models,
            'pages' => $pages,
            'para' => $para,
            'principal' => $principal,
            'recipientName' => $recipientName,
            'mobilephone' => $mobilephone,
            'areaAddress' => $areaAddress,
            'totalCost' => $totalCost,
            'orderState' => $orderState,
            'orderType' => $orderType,
            'orderdateTime_1' => $orderdateTime_1,
            'orderdateTime_2' => $orderdateTime_2,
        ]);
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 跳转到增加订单页面
     */
    public function actionAdd(){
        return $this->render('add');
    }
    public function actionInsert(){
        $Userorder = new Userorder();
        $Userorder->id = Common::generateID();
        $Userorder->principal = Yii::$app->request->post('principal');
        $Userorder->recipientName = Yii::$app->request->post('recipientName');
        $Userorder->mobilephone = Yii::$app->request->post('mobilephone');
        $Userorder->areaAddress = Yii::$app->request->post('areaAddress');
        $Userorder->detailAddress = Yii::$app->request->post('detailAddress');
        $Userorder->postcode = Yii::$app->request->post('postcode');
        $Userorder->totalCost = Yii::$app->request->post('totalCost');
        $Userorder->orderCode = Yii::$app->request->post('orderCode');
        $Userorder->orderType = Yii::$app->request->post('orderType');
        $Userorder->orderState = Yii::$app->request->post('orderState');
        $Userorder->orderdateTime = Yii::$app->request->post('orderdateTime');
        if($Userorder->save()){
            return "success";
        }else{
            return "fail";
        }
    }

    /*
     * @author xfk
     * @date 2016-3-28
     * @function 获取详情页
     */
    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $userorder = Userorder::findOne($id);

        /*
         * 字典反转
         */
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_ORDERSTATE'])
            ->all();
        $dictItemS = Dictitem::find()
            ->where(['dictCode' => 'DICT_ORDERTYPE'])
            ->all();

        foreach ($dictItem as $index => $value) {
            if ($userorder->orderState == $value->dictItemCode) {
                $userorder->orderState = $value->dictItemName;
            }
        }
        foreach($dictItemS as $index=>$value){
            if($userorder->orderType == $value->dictItemCode){
                $userorder->orderType = $value->dictItemName;
            }
        }

        return $this->render('detail',[
            'userorder'=>$userorder,
        ]);
    }

    /*
     * @author xfk
     * @date 2016-3-28
     * @function 打开修改页面
     */
    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $userorder = Userorder::findOne($id);
        $orderState = Dictitem::find()->where(['dictCode' => 'DICT_ORDERSTATE'])->all();
        $orderType = Dictitem::find()->where(['dictCode' => 'DICT_ORDERTYPE'])->all();
        return $this->render('edit',[
            'userorder' => $userorder,
            'orderState' => $orderState,
            'orderType' => $orderType,
        ]);
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 修改
     */
    public function actionUpdate(){
        $id = Yii::$app->request->post('id');
        $userorder = Userorder::findOne($id);
        $userorder->principal = Yii::$app->request->post('principal');
        $userorder->recipientName = Yii::$app->request->post('recipientName');
        $userorder->mobilephone = Yii::$app->request->post('mobilephone');
        $userorder->areaAddress = Yii::$app->request->post('areaAddress');
        $userorder->detailAddress = Yii::$app->request->post('detailAddress');
        $userorder->postcode = Yii::$app->request->post('postcode');
        $userorder->totalCost = Yii::$app->request->post('totalCost');
        $userorder->orderCode = Yii::$app->request->post('orderCode');
        $userorder->orderState = Yii::$app->request->post('orderState');
        $userorder->orderType = Yii::$app->request->post('orderType');
        $userorder->orderdateTime = Yii::$app->request->post('orderdateTime');
        if ($userorder->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 删除
     */
    public function actionDelete(){
        $id = Yii::$app->request->post("id");
        $userorder = Userorder::findOne($id);
        if($userorder->delete()){
            return "success";
        }else{
            return "fail";
        }
    }
    /*
     * @author xfk
     * @date 2016-3-28
     * @function 全选删除
     */
    public function actionDeleteall(){
        $ids = Yii::$app->request->post("ids");
        $ids_array = explode('-',$ids);
        foreach($ids_array as $key => $data){
            Userorder::deleteAll('id = :id',[':id'=>$data]);
        }
        return 'success';
    }

    /*
     * @author xfk
     * @date 2016-4-1
     * @function 订单处理
     */
    public function actionDeal()
    {
        $userOrderIds = Yii::$app->request->post('ids');
        $ids_array = explode('-', $userOrderIds);
        foreach ($ids_array as $key => $data) {
            $userOrder = Userorder::findOne($data);
            $userOrder->orderState = '1' ;
            $userOrder->save();
        }
        return "";
    }

    /*
     * @author xfk
     * @date 2016-4-1
     * @function 订单发货
     */
    public function actionDeliver(){
        $userOrderIds = Yii::$app->request->post('ids');
        $ids_array = explode('-', $userOrderIds);
        foreach ($ids_array as $key => $data) {
            $userOrder = Userorder::findOne($data);
            $userOrder->orderState = '2' ;
            $userOrder->save();
        }
        return "";
    }
}

