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
        echo CHtml::imageButton('images/disk.png', array(
            'onclick' => "
                var id_prefix = 'Certificate_" . $model_form_id . "_';
                var data = {
                    id: $('#' + id_prefix + 'id').val(),
                    living_plant_id: $('#BotanicalObject_id').val(),
                    certificate_type_id: $('#' + id_prefix + 'certificate_type_id').val(),
                    number: $('#' + id_prefix + 'number').val(),
                    annotation: $('#' + id_prefix + 'annotation').val(),
                };

                $.ajax({
                    type: 'POST',
                    url: 'index.php?r=livingPlant/ajaxCertificateStore',
                    data: data
                }).done(function(data) {
                    $('#' + id_prefix + 'id').val(data);
                });
                return false;",
        ));
        ?>
        &nbsp;
        <?php
        echo CHtml::imageButton('images/delete.png', array(
            'onclick' => "
                $.ajax({
                    url: 'index.php?r=livingPlant/ajaxCertificateDelete&id=" . $model_form_id . "'
                }).done(function(data) {
                    $('#certificates_row_" . $model_form_id . "').remove();
                });
                return false;"
        ));
        ?>
        </td>
    </tr>
