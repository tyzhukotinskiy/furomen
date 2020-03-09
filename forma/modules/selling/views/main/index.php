<?php

use kartik\dynagrid\DynaGrid;
use yii\widgets\Pjax;
use forma\modules\selling\records\selling\Selling;
use forma\components\ActiveRecordHelper;
use forma\modules\customer\records\Customer;
use forma\modules\warehouse\records\Warehouse;
use forma\widgets\DateRangeFilter;

/* @var $this yii\web\View */
/* @var $searchModel \forma\modules\selling\records\selling\SellingSearch
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Продажи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="selling-index">

    <a href="/selling/form/index" class="btn btn-success">Новая продажа</a>
    <a href="/selling/main?SellingSearch[state]=0" class="btn btn-primary">План на обзвон</a>
    <a href="/selling/main-state/index" class="btn btn-success">Cостояния пользователя</a>

    <hr>

<?php Pjax::begin(); ?>

    <?php

    $columns = [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
        [
            'attribute' => 'customer_id',
            'value' => 'customer.name',
            'filter' => ActiveRecordHelper::getList(Customer::className()),
        ],
        [
            'attribute' => 'customer_id',
            'label' => 'Компания',
            'value' => 'customer.firm',
            'filter' => ActiveRecordHelper::getList(Customer::className()),
        ],
        [
            'attribute' => 'warehouse_id',
            'value' => 'warehouse.name',
            'filter' => ActiveRecordHelper::getList(Warehouse::className()),
        ],
        [
            'attribute' => 'state',
            'value' => function (Selling $selling) { return $selling->getState()->getName(); },
            'filter' => Selling::getStatesList(),
        ],
    ];

    foreach (['date_create', 'date_complete'] as $attribute) {
        $columns[] = [
            'attribute' => $attribute,
            'filter' => DateRangeFilter::widget(compact('attribute', 'searchModel')),
        ];
    }

    echo DynaGrid::widget([
        'options' => ['id' => 'dyna-grid-' . $searchModel->tableName()],
        'theme' => 'panel-default',
        'columns' => $columns,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsiveWrap' => false,
        ],
    ]);

    ?>

<?php Pjax::end(); ?>

</div>

<style>
    tr:hover {
       background-color: #8dcb96 !important;
    }
</style>
