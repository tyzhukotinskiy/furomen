<?php

namespace forma\modules\selling\controllers;

use forma\extensions\editable\EditCellAction;
use forma\modules\selling\records\selling\Selling;
use forma\modules\selling\services\SellingService;
use forma\modules\warehouse\services\RemainsService;
use Yii;
use forma\modules\selling\records\sellingproduct\SellingProduct;
use forma\modules\selling\services\NomenclatureService;
use forma\modules\selling\widgets\NomenclatureView;
use yii\web\Controller;
use yii\web\Response;
use yii\web\HttpException;
use yii\widgets\ActiveForm;

class NomenclatureController extends Controller
{
    public function actionAddPosition()
    {
        /** @var SellingProduct $model */
        $model = NomenclatureService::addPosition(Yii::$app->request->post());
        return NomenclatureView::widget([
            'sellingId' => $model->selling_id,
            'model' => $model,
        ]);
    }

    public function actionDeletePosition($id)
    {
        /** @var SellingProduct $model */
        $model = NomenclatureService::deletePosition($id);
        return NomenclatureView::widget([
            'sellingId' => $model->selling_id,
            'model' => $model,
        ]);
    }

    public function actionGetProductCost()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = ['success' => true];

            $productId = Yii::$app->request->post('productId');
            $sellingId = Yii::$app->request->post('sellingId');
            $costType = Yii::$app->request->post('costType');

            $cost = NomenclatureService::getProductCost($productId, $sellingId, $costType);
            
            return array_merge($response, ['cost' => $cost]);
        }

        throw new HttpException(404 ,'Page not found');
    }

    public function actionChangeNomenclatureCell()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return NomenclatureService::changeCell(Yii::$app->request->post());
    }

    public function actionChangeUnitCost()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return NomenclatureService::changeCell(Yii::$app->request->post(), 'costLabel');
    }

    public function actionValidate()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            /** @var SellingProduct $model */
            $model = NomenclatureService::createPosition();
            $model->load(Yii::$app->request->post());
            return ActiveForm::validate($model);
        }
    }

    public function actions()
    {
        return [
            'editCell' => [
                'class' => EditCellAction::className(),
                'modelClass' => SellingProduct::className(),
            ],
        ];
    }
    
    public function actionChangePack($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return NomenclatureService::changePack($id, Yii::$app->request->post());
    }

    public function actionChangeCurrency($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return NomenclatureService::changeCurrencyByPost($id, Yii::$app->request->post());
    }
}
