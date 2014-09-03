<?php

/**
 * This is the model class for table "srvc_uuid_minter_type".
 *
 * The followings are the available columns in table 'srvc_uuid_minter_type':
 * @property integer $uuid_minter_type_id
 * @property string $description
 * @property string $timestamp
 *
 * The followings are the available model relations:
 * @property UuidMinter[] $uuidMinters
 */
class UuidMinterType extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'srvc_uuid_minter_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('description, timestamp', 'required'),
            array('description', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('uuid_minter_type_id, description, timestamp', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'uuidMinters' => array(self::HAS_MANY, 'UuidMinter', 'uuid_minter_type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'uuid_minter_type_id' => 'Uuid Minter Type',
            'description' => 'Description',
            'timestamp' => 'Timestamp',
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

        $criteria->compare('uuid_minter_type_id', $this->uuid_minter_type_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('timestamp', $this->timestamp, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UuidMinterType the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
