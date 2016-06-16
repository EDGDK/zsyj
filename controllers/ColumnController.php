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
use app\models\Column;

class ColumnController extends Controller
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

    /*
     * 添加功能
     */
    public function actionAdd()
    {
        return $this->render('add');
    }

    /*
     * 保存
     */
    public function actionSave(){
        $column = new Column();
        $column->columnId = Common::generateID();
        $column->title = Yii::$app->request->post('title');
        $column->discri = Yii::$app->request->post('discri');
        $column->url = Yii::$app->request->post('url');
        $column->ordeyBy = Yii::$app->request->post('ordeyBy');

        if($column->save()){
            return 'success';
        }else{
            return 'fail';
        };
    }

    /*
     * 检查标题是否重复
     */
    public function actionCheckname(){
        $title = Yii::$app->request->get('title');
        $column = Column::find()->where("title = :title",[':title' => $title])->all();
        if(sizeof($column) == 0){
            return "success";
        }else{
            return "exist";
        }
    }

    /*
     * Listall
     */
    public function actionListall()
    {
        $title = Yii::$app->request->get('title');
        $para=array();
        $para['title'] = $title;
        $whereStr = '1=1';//查询条件
        if($title != ''){
            $whereStr = $whereStr." and title like '%".$title."%'";
        }
        $whereStr = $whereStr;
        $column = Column::find()->where($whereStr);
        $pages = new Pagination(['totalCount' =>$column->count(), 'pageSize' => Common::PAGESIZE]);//分页
        $model = $column->offset($pages->offset)->limit($pages->limit)->orderBy('ordeyBy')->all();
        return $this->render('listall',[
            'column' => $model,
            'pages' => $pages,
            'para' => $para,
        ]);
    }

    /*
     * 修改
     */
    public function actionEdit(){
        $columnId = Yii::$app->request->get('columnId');
        $column = Column::findOne($columnId);

        return $this->render('edit',[
            'column' => $column,
        ]);
    }

    /*
     * 更新
     */
    public function actionUpdate(){
        $columnId = Yii::$app->request->post('columnId');
        $column = Column::findOne($columnId);
        $column->title = Yii::$app->request->post('title');
        $column->discri = Yii::$app->request->post('discri');
        $column->url = Yii::$app->request->post('url');
        $column->ordeyBy = Yii::$app->request->post('ordeyBy');
        $column->clicks = Yii::$app->request->post('clicks');
        if ($column->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    /*
     * 详情
     */
    public function actionDetail()
    {
        $columnId = Yii::$app->request->get('columnId');
        $column = Column::findOne($columnId);
        return $this->render('detail',[
            'column' => $column,
        ]);
    }

    /*
     * 删除单个栏目
     */
    public function actionDelete(){
        $columnId = Yii::$app->request->post("columnId");
        $column = Column::findOne($columnId);
        if($column->delete())
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
            Column::deleteAll('columnId = :columnId',[':columnId'=>$data]);
        }
        return 'success';
    }

    /*
     * 多选保存
     */
    public function actionSaveall()
    {
        $ids = Yii::$app->request->post("ids");
        $ids_array = explode('-',$ids);
        $orders = Yii::$app->request->post("orders");
        $orders_array = explode('-',$orders);
        foreach($ids_array as $key => $data){
            $column = Column::findOne($data);
            $column->ordeyBy = $orders_array[$key];
            $column->save();
        }
        return 'success';
    }

}
