<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property string $filename
 * @property string $created_at
 */
class Import extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename'], 'required'],
            [['created_at'], 'safe'],
            [['filename'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'created_at' => 'Created At',
        ];
    }
}
