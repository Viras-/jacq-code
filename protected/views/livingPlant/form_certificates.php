<div class="row">
    <table>
<?php
$i = 0;
foreach( $model_livingPlant->certificates as $i => $model_certificate ) {
?>
        <tr>
            <td width="20%">
            <?php
            echo $form->hiddenField($model_certificate, "[$i]id");

            echo $form->labelEx($model_certificate, 'certificate_type_id');
            echo $form->dropDownList(
                    $model_certificate,
                    "[$i]certificate_type_id",
                    CHtml::listData(CertificateType::model()->findAll(), 'id', 'type')
            );
            echo $form->error($model_certificate, 'certificate_type_id');
            ?>
            </td>
            <td>
            <?php
            echo $form->labelEx($model_certificate, 'number');
            echo $form->textField(
                    $model_certificate,
                    "[$i]number"
            );
            echo $form->error($model_certificate, 'number');
            ?>
            </td>
            <td>
            <?php
            echo $form->labelEx($model_certificate, 'annotation');
            echo $form->textField(
                    $model_certificate,
                    "[$i]annotation"
            );
            echo $form->error($model_certificate, 'annotation');
            ?>
            </td>
        </tr>
<?php
}
?>
    </table>
</div>
<hr />
<div class="row">
    <table>
        <tr>
            <td width="20%">
            <?php
            echo CHtml::label(Yii::t('jacq', 'certificate_type_id'), 'Certificate_certificate_type_id');
            $certificate_types = array('' => 'none') + CHtml::listData(CertificateType::model()->findAll(), 'id', 'type');
            echo CHtml::dropDownList(
                    "Certificate[9999][certificate_type_id]",
                    "",
                    $certificate_types
            );
            ?>
            </td>
            <td>
            <?php
            echo CHtml::label(Yii::t('jacq', 'number'), 'Certificate_number');
            echo CHtml::textField(
                    "Certificate[9999][number]"
            );
            ?>
            </td>
            <td>
            <?php
            echo CHtml::label(Yii::t('jacq', 'annotation'), 'Certificate_annotation');
            echo CHtml::textField(
                    "Certificate[9999][annotation]"
            );
            ?>
            </td>
        </tr>
    </table>
</div>
