<?php

namespace forma\modules\core\records;

/**
 * This is the ActiveQuery class for [[\forma\modules\core\records\regularity\Regularity]].
 *
 * @see \forma\modules\core\records\regularity\Regularity
 */
class RegularityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \forma\modules\core\records\regularity\Regularity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \forma\modules\core\records\regularity\Regularity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}