<?php

namespace app\models\activeQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Dict]].
 *
 * @see \app\models\Dict
 */
class DictQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Dict[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Dict|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}