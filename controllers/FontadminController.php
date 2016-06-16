<?php

namespace app\controllers;

use yii;
use app\models\Product;
use yii\web\Controller;
use app\models\Dictitem;
use app\models\Message;
use app\models\Submessage;
use app\models\Contact;
use app\common\Common;
use yii\data\Pagination;
use app\models\User;
use app\models\Shopcart;
use app\models\Userinfo;
use app\models\Userorder;
use app\models\Productorder;
use yii\helpers\Json;
use app\models\Ordercharge;

class FontadminController extends Controller
{
    public $layout = false; //设置使用的布局文件
    public $enableCsrfValidation = false;

    /*
    * @function 权限验证
    * @date 2016-4-17
    */
    public function beforeAction($action)
    {
        if(Yii::$app->request->isAjax){
            if(is_null(yii::$app->session['username']) || yii::$app->session['userType'] == '2')
            {
                return false;
            }else{
                return parent::beforeAction($action); // TODO: Change the autogenerated stub
            }
        }else{
            if(is_null(yii::$app->session['username']) || yii::$app->session['userType'] == '2')
            {
                $this->redirect(['home/index']);
                return false;
            }else{
                return parent::beforeAction($action); // TODO: Change the autogenerated stub
            }
        }

    }

    //跳转到后台我的地址页面
    public function actionAddressafter()
    {
        if(Yii::$app->request->isPost){

        }else{
            $userId = Yii::$app->session['userId'];
            $userinfos = Userinfo::find()->where('userId = :userId',[":userId" => $userId])->orderBy('userInfoClicks')->all();
            return $this->render('addressafter',[
                'userinfos' => $userinfos
            ]);
        }

    }

    //跳转到后台购物车
    public function actionShoppingcart()
    {
        $userId = Yii::$app->session['userId'];

        $query = new \yii\db\Query();
        $products = $query->select('b.id as id,b.productTitle as productTitle,b.productPrice as productPrice,b.productDiscount as productDiscount,b.picUrl as picUrl,b.productSize as productSize,b.productType as productType,a.productNum as productNum')
            ->from('zsyj_shopcart a')
            ->where('userId = :userId',[":userId" => $userId])
            ->rightJoin('zsyj_product b','a.productId = b.id')
            ->orderBy('a.datetime DESC')
            ->all();

        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_PRODUCTTYPE'])
            ->all();
        foreach($products as $key=>$data) {
            foreach ($dictItem as $index => $value) {
                if ($data['productType'] == $value->dictItemCode) {
                    $products[$key]['productType'] = $value->dictItemName;
                }
            }
        }
        $productId = Yii::$app->session['productId'];
        $product_array = explode('-',$productId);

        return $this->render('shoppingcart',[
            'products' => $products,
            'product_array' => $product_array
        ]);
    }

    //跳转到后台结算
    public function actionPurchase()
    {
        if(Yii::$app->request->isAjax){//是否ajax请求
            $id = Yii::$app->request->post('id');
            /*$recipientName = Yii::$app->request->post('recipientName');
            $city1 = Yii::$app->request->post('city1');
            $detailAddress = Yii::$app->request->post('detailAddress');
            $mobilephone = Yii::$app->request->post('mobilephone');
            $postcode = Yii::$app->request->post('postcode');*/
            $userinfo = Userinfo::findOne($id);
            return Json::encode($userinfo);//Yii 的方法将数组处理成json数据


        } else {
            if (Yii::$app->request->isPost) {
                $ids = Yii::$app->request->post('ids');
                return true;
            } else {
                $userId = Yii::$app->session['userId'];
                $ids = Yii::$app->request->get('ids');
                $counts = Yii::$app->request->get('counts');
                $ids_array = explode('-', $ids);
                $query = new \yii\db\Query();
                $products = $query->select('b.id as id,b.productTitle as productTitle,b.productPrice as productPrice,b.productDiscount as productDiscount,b.picUrl as picUrl,b.productSize as productSize,b.productType as productType,a.productNum as productNum')
                    ->from('zsyj_shopcart a')
                    ->where(['and', 'a.userId ="' . $userId . '"',
                        ['in', 'b.id', $ids_array],])
                    ->rightJoin('zsyj_product b', 'a.productId = b.id')
                    ->orderBy('a.datetime DESC')
                    ->all();

                $dictItem = Dictitem::find()
                    ->where(['dictCode' => 'DICT_PRODUCTTYPE'])
                    ->all();


                $count_array = explode('-', $counts);
                foreach ($products as $key => $data) {
                    foreach ($dictItem as $index => $value) {
                        if ($data['productType'] == $value->dictItemCode) {
                            $products[$key]['productType'] = $value->dictItemName;
                        }
                    }
                    foreach ($count_array as $a => $val) {
                        if ($key == $a) {
                            $products[$key]['productNum'] = $val;
                        }
                    }
                }
                $shopcarts = Shopcart::find()->where('userId = :userId', [":userId" => $userId])->all();
                foreach ($shopcarts as $key => $data) {
                    foreach ($ids_array as $index => $value) {
                        if ($data['productId'] == $value) {
                            foreach ($count_array as $a => $val) {
                                if ($index == $a) {
                                    $data['productNum'] = $val;
                                    $data->save();
                                }
                            }
                        }
                    }
                }
                $userinfos = Userinfo::find()->where('userId = :userId', [":userId" => $userId])->orderBy('userInfoClicks')->all();
                return $this->render('purchase', [
                    'products' => $products,
                    'userinfos' => $userinfos,
                ]);
            }
        }
    }


    //跳转到完成订单
    public function actionOrdercomplete()
    {
        $userId = Yii::$app->session['userId'];
        //使用默认地址
        $userinfo = Userinfo::find()->where('userId = :userId and userInfoState = 1',[":userId" => $userId])->one();
        if(Yii::$app->request->isPost)
        {
            //根据省份查找负责人
            $province = $userinfo->hcity;
            $ordercharge = Ordercharge::find()->where('areaAddress = :province',[":province" => $province])->one();
            $chargerId = $ordercharge->userId;
            //新建userorder记录
            $userorder = new Userorder;
            $userorder->id = Common::generateID();
            $userorder->userId = Yii::$app->session['userId'];
            $userorder->chargerId = $chargerId;//负责人ID
            $userorder->recipientName = $userinfo->recipientName;
            $userorder->mobilephone = $userinfo->mobilephone;
            $userorder->areaAddress = $userinfo->areaAddress;
            $userorder->detailAddress = $userinfo->detailAddress;
            $userorder->postcode = $userinfo->postcode;
            $userorder->truename = $userinfo->truename;
            $userorder->orderdateTime = date("Y-m-d H:i:s");
            $userorder->totalCost = Yii::$app->request->post('totalCost');
            $userorder->orderState = '0';
            $userorder->orderType = '1';
            $userorder->orderCode = Common::generateOrderCode();
            $userorder->save();

            $ids = Yii::$app->request->post('ids');
            $shopcarts = Shopcart::find()->where('userId = :userId', [":userId" => $userId])->all();

            $ids_array = explode('-',$ids);
            foreach($ids_array as $key => $data) {
                $product = Product::findOne($data);
                $productorder = new Productorder();
                $productorder->id = Common::generateID();
                $productorder->orderId = $userorder->id;
                $productorder->productId = $data;
                $productorder->productName = $product->productTitle;
                $productorder->productPrice = $product->productPrice;
                $productorder->productDiscount = $product->productDiscount;
                foreach($shopcarts as $index => $value){
                    if($data == $value['productId']){
                        $productorder->productNum = $value['productNum'];
                    }
                }
                $productorder->save();
                Shopcart::deleteAll('userId = :userId and productId = :productId',[":userId" => $userId,":productId" => $data]);
                Yii::$app->session['productNums'] -= 1;
            }

            return $userorder->id;

        }else {
            $id = Yii::$app->request->get('id');
            return $this->render('ordercomplete',[
                'userinfo' => $userinfo,
                'userorderid' => $id
            ]);
        }

    }

    //跳转到后台订单列表
    public function actionOrderafter()
    {
        if(Yii::$app->request->isPost){

        }else{
            $id = Yii::$app->session['userId'];
            $userorder = Userorder::find()->where(['userId' => $id]);
            $pages = new Pagination(['totalCount' => $userorder->count(), 'pageSize' => Common::PAGESIZE]);//分页
            $models = $userorder->offset($pages->offset)->limit($pages->limit)->orderBy('orderdateTime DESC')->all();
            return $this->render('orderafter', [
                'userorder' => $models,
                'pages' => $pages,
            ]);
        }
    }

    //跳转到订单详情
    public function actionOrderdetail()
    {
        if(Yii::$app->request->isPost)
        {
            $orderId = Yii::$app->request->post('id');
            return $orderId;
        }else{
            $orderId = Yii::$app->request->get('id');
            $userorder = Userorder::findOne($orderId);

            $productorders = Productorder::find()->where('orderId = :orderId',[":orderId" => $orderId])->all();

            $query = new \yii\db\Query();
            $productorders = $query->select('b.id as id,a.productNum as productNum,b.productTitle as productTitle,b.productPrice as productPrice,b.productDiscount as productDiscount,b.picUrl as picUrl')
                ->from('zsyj_productorder a')
                ->where('orderId = :orderId',[":orderId" => $orderId])
                ->rightJoin('zsyj_product b','a.productId = b.id')
                ->all();

            $dictItem = Dictitem::find()
                ->where(['dictCode' => 'DICT_ORDERSTATE'])
                ->all();
            return $this->render('orderdetail',[
                'userorder' => $userorder,
                'productorders' => $productorders,
                'userorderId' => $orderId,
                'dictItem' => $dictItem,
            ]);
        }

    }

    //跳转到个人资料
    public function actionPersonaldata()
    {
        if(Yii::$app->request->isPost){

        }else{
            $id = Yii::$app->session['userId'];
            $user = User::findOne($id);
            $dictItem = Dictitem::find()
                ->where(['dictCode' => 'DICT_SEX'])
                ->all();
            $dictItemS = Dictitem::find()
                ->where(['dictCode' => 'DICT_MEMBER'])
                ->all();
            return $this->render('personaldata',[
                'user' => $user,
                'dictItem' => $dictItem,
                'dictItemS' => $dictItemS
            ]);
        }

    }

    //跳转到账户安全
    public function actionPassword()
    {
        if(Yii::$app->request->isPost){
            $verifyCode = Yii::$app->request->post('verifyCode');
            if(strtolower(Yii::$app->session['userCode']) == strtolower($verifyCode)){
                $fontUser = User::findOne(Yii::$app->session['userId']);
                $fontUser->password = Common::hashMD5(Yii::$app->request->post('password'));
                if($fontUser->save()){
                    return 'success';
                }else{
                    return 'fail';
                }
                Yii::$app->session->remove('userCode');
            }else{
                return 'verifyError';
            }
        }else{
            return $this->render('password');
        }

    }

    //跳转到我的消息
    public function actionMymessages()
    {
        if(Yii::$app->request->isPost){

        }else{
            $userId = Yii::$app->session['userId'];
            $messages = Message::find()->where('userId = :userId',[":userId" => $userId]);
            $pages = new Pagination(['totalCount' => $messages->count(), 'pageSize' => Common::PAGESIZE]);//分页
            $models = $messages->offset($pages->offset)->limit($pages->limit)->orderBy('createDateTime DESC')->all();
            return $this->render('mymessages',[
                'messages' => $models,
                'pages' => $pages,
            ]);
        }

    }

    //跳转到我的留言
    public function actionMynews()
    {
        if(Yii::$app->request->isPost){

        }else{
            return $this->render('mynews');
        }

    }


    //保存用户信息
    public function actionUpdate(){
        $id = Yii::$app->request->post('id');
        $user = User::findOne($id);
        $user->mobilephone = Yii::$app->request->post('mobilephone');
        $user->sex = Yii::$app->request->post('sex');
        $user->email = Yii::$app->request->post('email');
        $user->postcode = Yii::$app->request->post('postcode');
        if ($user->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //验证密码
    public function actionCheckpassword(){
        $password = Yii::$app->request->post("password");
        $userId = Yii::$app->session['userId'];
        $user = User::findOne($userId);
        if($user->password == Common::hashMD5($password)){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //加入购物车
    public function actionAddshopcart(){
        if(Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post("id");
            $productNum = Yii::$app->request->post("num");
            if (is_null(Yii::$app->session['productId'])) {
                Yii::$app->session['productId'] = $productId;
            } else {
                Yii::$app->session['productId'] .= '-' . $productId;
            }//把此商品ID存入session

            $userId = Yii::$app->session['userId'];
            if (Shopcart::find()
                ->where('userId = :userId and productId = :productId', [":userId" => $userId, ":productId" => $productId])
                ->one()
            ) {

                $shopcart = Shopcart::find()
                    ->where('userId = :userId and productId = :productId', [":userId" => $userId, ":productId" => $productId])
                    ->one();
                $shopcart->productNum += $productNum;
                $shopcart->save();
            } else {
                $product = Product::findOne($productId);
                Yii::$app->session['productNums'] += 1;
                $shopcart = new Shopcart();
                $shopcart->id = Common::generateID();
                $shopcart->productId = $productId;
                $shopcart->productName = $product->productTitle;
                $shopcart->productPrice = $product->productPrice;
                $shopcart->productDiscount = $product->productDiscount;
                $shopcart->userId = $userId;
                $shopcart->productNum = $productNum;
                $shopcart->datetime = date("Y-m-d H:i:s");
                $shopcart->save();
            }
            $productNums = Yii::$app->session['productNums'];
            return $productNums;
        }else{

        }

    }

    //从购物车删除
    public function actionDeleteshopcart()
    {
        if(Yii::$app->request->isPost) {
            Yii::$app->session['productNums'] -= 1;
            $productNums = Yii::$app->session['productNums'];
            $productId = Yii::$app->request->post("id");
            $userId = Yii::$app->session['userId'];
            Shopcart::deleteAll('userId = :userId and productId = :productId',[":userId" => $userId,":productId" => $productId]);
            return $productNums;
        }else{

        }
    }

    //从购物车删除多个商品
    public function actionDelallshopcart(){
        if(Yii::$app->request->isPost) {
            $ids = Yii::$app->request->post("ids");
            $len = Yii::$app->request->post("len");
            Yii::$app->session['productNums'] -= $len;
            $productNums = Yii::$app->session['productNums'];
            $userId = Yii::$app->session['userId'];
            $ids_array = explode('-',$ids);
            foreach($ids_array as $key => $data){
                Shopcart::deleteAll('userId = :userId and productId = :productId',[":userId" => $userId,":productId" => $data]);
            }
            return $productNums;
        }else{

        }
    }

    //结算页新增收货地址
    public function actionAddaddress()
    {
        if(Yii::$app->request->isPost)
        {
            $userId = Yii::$app->session['userId'];
            $userinfo = New Userinfo();//新建地址
            $userinfo->id = Common::generateID();
            $userinfo->userId = $userId;
            $userinfo->recipientName = Yii::$app->request->post("username");
            $userinfo->mobilephone = Yii::$app->request->post("tel");
            $userinfo->areaAddress = Yii::$app->request->post("city");
            $userinfo->detailAddress = Yii::$app->request->post("address");
            $userinfo->postcode = Yii::$app->request->post('postcode');
            $userinfo->hcity = Yii::$app->request->post('hcity');
            $userinfo->userInfoState = '0';
            $userinfo->save();

            $query = new \yii\db\Query();
            $products = $query->select('b.id as id,b.productTitle as productTitle,b.productPrice as productPrice,b.productDiscount as productDiscount,b.picUrl as picUrl,b.productSize as productSize')
                ->from('zsyj_shopcart a')
                ->where('userId = :userId',[":userId" => $userId])
                ->rightJoin('zsyj_product b','a.productId = b.id')
                ->all();

            $userinfos = Userinfo::find()->where('userId = :userId',[":userId" => $userId])->orderBy('userInfoClicks')->all();
            return $this->render('purchase',[
                'products' => $products,
                'userinfos' => $userinfos
            ]);
        }else{

        }

    }

    //我的地址页新增收货地址
    public function actionAddaddress2()
    {
        if(Yii::$app->request->isPost)
        {
            $userId = Yii::$app->session['userId'];
            $userinfo = New Userinfo();
            $userinfo->id = Common::generateID();
            $userinfo->userId =$userId;
            $userinfo->recipientName = Yii::$app->request->post("username");
            $userinfo->mobilephone = Yii::$app->request->post("tel");
            $userinfo->areaAddress = Yii::$app->request->post("city");
            $userinfo->detailAddress = Yii::$app->request->post("address");
            $userinfo->postcode = Yii::$app->request->post('postcode');
            $userinfo->hcity = Yii::$app->request->post('hcity');
            $userinfo->userInfoState = '0';
            $userinfo->save();

            $userinfos = Userinfo::find()->where('userId = :userId',[":userId" => $userId])->orderBy('userInfoClicks')->all();
            return $this->render('addressafter',[
                'userinfos' => $userinfos
            ]);
        }else{

        }

    }

    //删除用户地址信息
    public function actionDeladdress(){
        $infoId = Yii::$app->request->post("id");
        $userinfo = Userinfo::findOne($infoId);
        $userinfo->delete();
        $userId = Yii::$app->session['userId'];
        $userinfos = Userinfo::find()->where('userId = :userId',[":userId" => $userId])->orderBy('userInfoClicks')->all();
        return $this->render('addressafter',[
            'userinfos' => $userinfos
        ]);
    }

    //删除订单
    public function actionDelorder(){
        $id = Yii::$app->request->post("id");
        $userorder = Userorder::findOne($id);
        if($userorder->orderState == '0' ||$userorder->orderState == '3'){
            if($userorder->delete()){
                return 'success';
            }else{
                return 'fail';
            }
        }else{
            return 'fail';
        }
    }

    //修改用户地址信息
    public function actionUpdateaddress(){
        $id = Yii::$app->request->post('id');
        $userinfo = Userinfo::findOne($id);
        $userinfo->recipientName = Yii::$app->request->post('recipientName');
        $userinfo->areaAddress = Yii::$app->request->post('areaAddress');
        $userinfo->detailAddress = Yii::$app->request->post('detailAddress');
        $userinfo->mobilephone = Yii::$app->request->post('mobilephone');
        $userinfo->postcode = Yii::$app->request->post('postcode');
        $userinfo->hcity = Yii::$app->request->post('hcity');
        if ($userinfo->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    //设为默认地址
    public function actionBaseaddress()
    {
        $id = Yii::$app->request->post('id');
        $userinfo = Userinfo::find()->where('userInfoState = 1')->one();
        $userinfo->userInfoState = '0';
        $userinfo->save();
        $model = Userinfo::findOne($id);
        $model->userInfoState = '1';
        $model->save();
        return ;
    }

    //个人删除帖子
    public function actionDeletemessage()
    {
        $id = Yii::$app->request->post("id");
        Message::findOne($id)->delete();
        return 'success';
    }
}
