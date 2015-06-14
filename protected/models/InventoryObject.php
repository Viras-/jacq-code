<?php

/**
 * This is the model class for table "tbl_inventory_object".
 *
 * The followings are the available columns in table 'tbl_inventory_object':
 * @property integer $inventory_object_id
 * @property integer $inventory_id
 * @property integer $botanical_object_id
 * @property integer $message
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property BotanicalObject $botanicalObject
 * @property Inventory $inventory
 */
class InventoryObject extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_inventory_object';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('inventory_id, message', 'required'),
            array('inventory_id, botanical_object_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('inventory_object_id, inventory_id, botanical_object_id, message, timestamp', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'botanicalObject' => array(self::BELONGS_TO, 'BotanicalObject', 'botanical_object_id'),
            'inventory' => array(self::BELONGS_TO, 'Inventory', 'inventory_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'inventory_object_id' => Yii::t('jacq', 'Inventory Object'),
            'inventory_id' => Yii::t('jacq', 'Inventory'),
            'botanical_object_id' => Yii::t('jacq', 'Botanical Object'),
            'message' => Yii::t('jacq', 'Message'),
            'timestamp' => Yii::t('jacq', 'Timestamp'),
            'renderedMessage' => Yii::t('jacq', 'Message'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('inventory_object_id', $this->inventory_object_id);
        $criteria->compare('inventory_id', $this->inventory_id);
        $criteria->compare('botanical_object_id', $this->botanical_object_id);
        $criteria->compare('message', $this->message);
        $criteria->compare('timestamp', $this->timestamp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => array(
                    'timestamp' => true
                )
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InventoryObject the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Virtual attribute for returning the rendered message
     * @return string
     */
    public function getRenderedMessage() {
        return InventoryHandler::getMessage($this);
    }

}
