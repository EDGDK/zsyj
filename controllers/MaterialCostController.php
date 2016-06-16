<?php
/**
 * Created by PhpStorm.
 * User: liluoao
 * Date: 2016/3/21
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
use app\models\Material;
use app\models\Materialcost;
use app\models\Inventory;

class MaterialCostController extends Controller
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

    public function actionList()
    {
        return $this->render('list');
    }

    public function actionAdd(){
        return $this->render('add');
    }

    /*
     * 消耗列表页
     */
    public function actionListall(){
        $materialName = Yii::$app->request->get('materialName');
        $para=array();
        $para['materialName'] = $materialName;
        $whereStr = '1=1';//查询条件
        if($materialName != ''){
            $whereStr = $whereStr." and materialName like '%".$materialName."%'";
        }

        $materialcosts = Materialcost::find()->where($whereStr);
        $pages = new Pagination(['totalCount' =>$materialcosts->count(), 'pageSize' => Common::PAGESIZE]);//分页
        $models = $materialcosts->offset($pages->offset)->limit($pages->limit)->all();
        //字典反转
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALNAME'])
            ->all();
        $dictItemS = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALUNIT'])
            ->all();
        foreach($models as $key=>$data) {
            foreach ($dictItem as $index => $value) {
                if ($data->materialName == $value->dictItemCode) {
                    $models[$key]->materialName = $value->dictItemName;
                }
            }
            foreach($dictItemS as $index=>$value){
                if($data->materialUnit == $value->dictItemCode){
                    $models[$key]->materialUnit = $value->dictItemName;
                }
            }
        }//结束
        return $this->render('listall',[
            'materialcosts' => $models,
            'pages' => $pages,
            'para' => $para,
        ]);
    }

    /*
     * 新增
     */
    public function actionInsert(){
        $materialcost = new Materialcost();
        $materialcost->id = Common::generateID();
        $materialName = Yii::$app->request->post('materialName');
        $materialcost->materialName = $materialName;
        $materialcost->costNum = Yii::$app->request->post('costNum');
        $materialcost->materialUnit = Yii::$app->request->post('materialUnit');
        $material = Material::find()->where("materialname = :name",[":name" => $materialName])->one();
        $materialcost->materialId = $material->id;
        if($materialcost->save()){//新增时加入库存
            $inventory = Inventory::find()->where(('materialName = :materialName and materialUnit = :materialUnit')
                ,[':materialName' => $materialName , ':materialUnit' => $materialcost->materialUnit])
                ->one();
            if(is_null($inventory)){
                return "fail";
            }else{
                $invnum = $inventory->inventory;
                $inventory->inventory = $invnum-$materialcost->costNum;
            }
            $inventory->save();
            return "success";
        }else{
            return "fail";
        }
    }

    /*
     * 修改
     */
    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $materialcost = Materialcost::findOne($id);
        $materialName = Yii::$app->request->get('materialName');
        //发送2个字典项到视图里供选
        $namedict = Dictitem::find()->where(['dictCode' => 'DICT_MATERIALNAME'])->all();
        $unitdict = Dictitem::find()->where(['dictCode' => 'DICT_MATERIALUNIT'])->all();
        return $this->render('edit',[
            'materialcost' => $materialcost,
            'namedict' => $namedict,
            'unitdict' => $unitdict,
        ]);
    }

    /*
    *更新
    */
    public function actionUpdate(){
        $id = Yii::$app->request->post('id');
        $materialcost = Materialcost::findOne($id);
        $materialcost->materialName = Yii::$app->request->post('materialName');
        $materialcost->materialUnit = Yii::$app->request->post('materialUnit');
        $materialcost->costNum = Yii::$app->request->post('costNum');
        if ($materialcost->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    /*
     * 单个删除
     */
    public function actionDelete(){
        $id = Yii::$app->request->post("id");
        $materialcost = Materialcost::findOne($id);
        if($materialcost->delete()){
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
            Materialcost::deleteAll('id = :id',[':id'=>$data]);
        }
        return 'success';
    }

    /*
     * 消耗详情
     */
    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $materialcost = Materialcost::findOne($id);
        //字典反转
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALNAME'])
            ->all();
        $dictItemS = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALUNIT'])
            ->all();
        foreach ($dictItem as $index => $value) {
            if ($materialcost->materialName == $value->dictItemCode) {
                $materialcost->materialName = $value->dictItemName;
            }
        }
        foreach($dictItemS as $index=>$value){
            if($materialcost->materialUnit == $value->dictItemCode){
                $materialcost->materialUnit = $value->dictItemName;
            }
        }//结束
        return $this->render('detail',[
            'materialcost' => $materialcost,
        ]);
    }

    /*
     * 库存功能
     */
    public function actionInventory(){
        return $this->render('inventory');
    }

    /*
     * 库存列表页
     */
    public function actionListallinventory()
    {
        $materialName = Yii::$app->request->get('materialName');
        $para=array();
        $para['materialName'] = $materialName;
        $whereStr = '1=1';//查询条件
        if($materialName != ''){
            $whereStr = $whereStr." and materialName like '%".$materialName."%'";
        }
        $inventory = Inventory::find()->where($whereStr);
        //分页
        $pages = new Pagination(['totalCount' =>$inventory->count(), 'pageSize' => Common::PAGESIZE]);
        $models = $inventory->offset($pages->offset)->limit($pages->limit)->all();
        //字典反转
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALNAME'])
            ->all();
        $dictItemS = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALUNIT'])
            ->all();
        foreach($models as $key=>$data) {
            foreach ($dictItem as $index => $value) {
                if ($data->materialName == $value->dictItemCode) {
                    $models[$key]->materialName = $value->dictItemName;
                }
            }
            foreach($dictItemS as $index=>$value){
                if($data->materialUnit == $value->dictItemCode){
                    $models[$key]->materialUnit = $value->dictItemName;
                }
            }
        }//结束
        return $this->render('listallinventory',[
            'pages' => $pages,
            'inventory' => $models,
            'para' => $para,
        ]);
    }

    /*
     * 选择原材料功能
     */
    public function actionChoose(){
        $dictItem = Dictitem::find()
            ->where(['dictCode' => 'DICT_MATERIALNAME'])
            ->all();
        return $this->render('choose',[
            'dictItem' => $dictItem,
        ]);
    }

}
