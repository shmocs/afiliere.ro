<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property string $type
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
            [['filename', 'type'], 'required'],
            [['created_at', 'type'], 'safe'],
            [['filename', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'filename' => 'Filename',
            'created_at' => 'Created At',
        ];
    }
	
	public function getCosts()
	{
		return $this->hasMany(Cost::class, ['import_id' => 'id']);
	}
	public function getSales()
	{
		return $this->hasMany(Sale::class, ['import_id' => 'id']);
	}
}
