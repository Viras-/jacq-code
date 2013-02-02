<?php
// Fetch list of certificate types
$certificate_types = CHtml::listData(CertificateType::model()->findAll(), 'id', 'type');

// check if we have a valid id already, if not skip the hidden field
if( $model_certificate->id > 0 ) {
    $model_form_id = $model_certificate->id;
}
else {
    // not exactly a clean definition, but for rendering the form it should work
    // real id is assigned on saving
    $model_form_id = "new_" . rand(0, 10000);
}
?>
    <tr id="certificates_row_<?php echo $model_form_id; ?>">
        <td width="20%">
        <?php
        echo CHtml::activeHiddenField($model_certificate, "[$model_form_id]id");
        echo CHtml::activeHiddenField($model_certificate, "[$model_form_id]delete");

        echo CHtml::activeDropDownList(
                $model_certificate,
                "[$model_form_id]certificate_type_id",
                $certificate_types
        );
        echo CHtml::error($model_certificate, "certificate_type_id");
        ?>
        </td>
        <td>
        <?php
        echo CHtml::activeTextField(
                $model_certificate,
                "[$model_form_id]number"
        );
        echo CHtml::error($model_certificate, "number");
        ?>
        </td>
        <td>
        <?php
        echo CHtml::activeTextField(
                $model_certificate,
                "[$model_form_id]annotation"
        );
        echo CHtml::error($model_certificate, "annotation");
        ?>
        </td>
        <td>
        <?php
        echo CHtml::imageButton('images/delete.png', array(
            'onclick' => "
                $('#certificates_row_{$model_form_id}').hide();
                $('#Certificate_{$model_form_id}_delete').val(1);

                return false;"
        ));
        ?>
        </td>
    </tr>
