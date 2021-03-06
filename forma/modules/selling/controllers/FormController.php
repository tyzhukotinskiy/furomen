<?php

namespace forma\modules\selling\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use forma\modules\selling\services\SellingService;
use forma\modules\selling\widgets\SellingFormView;
use forma\modules\selling\records\state\State;

class FormController extends Controller
{
    public function actionIndex($id = null)
    {
        $model = SellingService::get($id);
        $sellingState = State::findOne($model->state_id);
        $userState = State::find()->where(['user_id' => Yii::$app->user->getId()])
            ->all();
        if ($sellingState){
            $toState = $sellingState->state;
            return $this->render('index', compact('model', 'userState', 'sellingState','toState'));
        }else{
            return $this->render('index', compact('model', 'userState', 'sellingState'));
        }


    }

    public function actionTest()
    {
        $id = $_GET['id'];
        $model = SellingService::get($id);
        $state_id = $_GET['state_id'];
        $sellingState = State::findOne($state_id);
        if($sellingState){
            $model->state_id = $sellingState->id;
            $model->save();
        }
        $userState = State::find()->where(['user_id' => Yii::$app->user->getId()])->asArray()
            ->all();

        if ($sellingState){
            $toState = $sellingState->state;
            return $this->render('index', compact('model', 'userState', 'sellingState','toState'));
        }else{
            return $this->render('index', compact('model', 'userState', 'sellingState'));
        }

    }

    public function actionSave($id = null)
    {
        $model = SellingService::save($id, Yii::$app->request->post());
        if (!$id) {
            return $this->redirect(Url::to(['/selling/form', 'id' => $model->id]));
        }
        return SellingFormView::widget(compact('model'));
    }

}
