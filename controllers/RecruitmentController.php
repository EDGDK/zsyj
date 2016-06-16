<?php

namespace app\controllers;
use yii;
use yii\data\Pagination;
use app\common\Common;
use app\models\Recruitment;

class RecruitmentController extends \yii\web\Controller
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
     * @date 2016-4-8
     * @function 跳转到添加原材料列表
    */
    public function actionList(){
        return $this->render('list');
    }

    /*
     * @author xfk
     * @date 2016-4-8
     * @function 获取原材料列表
     */
    public function actionListall()
    {
        $position = Yii::$app->request->get('position');
        $positionname = Yii::$app->request->get('positionname');
        $responsibilities = Yii::$app->request->get('responsibilities');
        $claim = Yii::$app->request->get('claim');
        $wage = Yii::$app->request->get('wage');
        $para=array();
        $para['position'] = $position;
        $para['positionname'] = $positionname;
        $para['responsibilities'] = $responsibilities;
        $para['claim'] = $claim;
        $para['wage'] = $wage;
        $recruitment =Recruitment::find();
        $pages = new Pagination(['totalCount' =>$recruitment->count(), 'pageSize' => Common::PAGESIZE]);
        $models = $recruitment->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('listall',[
            'recruitment' => $models,
            'pages' => $pages,
            'para' => $para,
            'position' => $position,
            'positionname' => $positionname,
            'responsibilities' => $responsibilities,
            'claim' => $claim,
            'wage' => $wage,
        ]);
    }
    /*
     * @author xfk
     * @date 2016-4-8
     * @function 跳转到增加职位页面
     */
    public function actionAdd(){
        return $this->render('add');
    }
    /*
     * @author xfk
     * @date 2016-4-8
     * @function 添加新职位
     */
    public function actionInsert(){
        $recruitment = new Recruitment();
        $recruitment->id = Common::generateID();
        $recruitment->position = Yii::$app->request->post('position');
        $recruitment->positionname = Yii::$app->request->post('positionname');
        $recruitment->responsibilities = Yii::$app->request->post('responsibilities');
        $recruitment->claim = Yii::$app->request->post('claim');
        $recruitment->wage = Yii::$app->request->post('wage');
        if($recruitment->save()){
            return "success";
        }else{
            return "fail";
        }
    }
    /*
     * @author xfk
     * @date 2016-4-8
     * @function 打开修改页面
     */
    public function actionEdit(){
        $id = Yii::$app->request->get('id');
        $recruitment = Recruitment::findOne($id);
        return $this->render('edit',[
            'recruitment' => $recruitment,
        ]);
    }
    /*
    * @author xfk
    * @date 2016-4-8
    * @function 修改
    */
    public function actionUpdate(){
        $id = Yii::$app->request->post('id');
        $recruitment = Recruitment::findOne($id);
        $recruitment->position = Yii::$app->request->post('position');
        $recruitment->positionname = Yii::$app->request->post('positionname');
        $recruitment->responsibilities = Yii::$app->request->post('responsibilities');
        $recruitment->claim = Yii::$app->request->post('claim');
        $recruitment->wage = Yii::$app->request->post('wage');
        if ($recruitment->save()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    /*
     * @author xfk
     * @date 2016-4-8
     * @function 删除一个职位
     */
    public function actionDelete(){
        $id = Yii::$app->request->post("id");
        $recruitment = Recruitment::findOne($id);
        if($recruitment->delete()){
            return "success";
        }else{
            return "fail";
        }
    }
    /*
     * @author xfk
     * @date 2016-4-8
     * @function 删除多个职位
     */
    public function actionDeleteall(){
        $ids = Yii::$app->request->post("ids");
        $ids_array = explode('-',$ids);
        foreach($ids_array as $key => $data){
            Recruitment::deleteAll('id = :id',[':id'=>$data]);
        }
        return 'success';
    }
    /*
     * @author xfk
     * @date 2016-3-22
     * @function 获取详情页
     */
    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $recruitment = Recruitment::findOne($id);
        return $this->render('detail',[
            'recruitment'=>$recruitment,
        ]);
    }

}
