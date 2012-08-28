<div class="row">
    <table>
    <?php
    $model_certificates = $model_livingPlant->certificates;
    $model_certificates[] = new Certificate;    // Add one new entry for adding

    foreach( $model_certificates as $i => $model_certificate ) {
    ?>
        <tr>
            <td width="20%">
            <?php
            // Fetch list of certificate types
            $certificate_types = CHtml::listData(CertificateType::model()->findAll(), 'id', 'type');
            
            // check if we have a valid id already, if not skip the hidden field
            if( $model_certificate->id > 0 ) {
                echo $form->hiddenField($model_certificate, "[$i]id");
            }
            else {
                $certificate_types = array( '' => 'None' ) + $certificate_types;
            }

            echo $form->labelEx($model_certificate, 'certificate_type_id');
            echo $form->dropDownList(
                    $model_certificate,
                    "[$i]certificate_type_id",
                    $certificate_types
            );
            echo $form->error($model_certificate, "certificate_type_id");
            ?>
            </td>
            <td>
            <?php
            echo $form->labelEx($model_certificate, 'number');
            echo $form->textField(
                    $model_certificate,
                    "[$i]number"
            );
            echo $form->error($model_certificate, "number");
            ?>
            </td>
            <td>
            <?php
            echo $form->labelEx($model_certificate, 'annotation');
            echo $form->textField(
                    $model_certificate,
                    "[$i]annotation"
            );
            echo $form->error($model_certificate, "annotation");
            ?>
            </td>
        </tr>
    <?php
    }
    ?>
    </table>
</div>
