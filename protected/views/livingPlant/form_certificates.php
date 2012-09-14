<div class="form">
    <table>
        <tr>
            <td><?php echo CHtml::activeLabelEx(Certificate::model(), 'certificate_type_id'); ?></td>
            <td><?php echo CHtml::activeLabelEx(Certificate::model(), 'number'); ?></td>
            <td><?php echo CHtml::activeLabelEx(Certificate::model(), 'annotation'); ?></td>
            <td><?php echo CHtml::activeLabelEx(Certificate::model(), 'actions'); ?></td>
        </tr>
        <?php
        $model_certificates = $model_livingPlant->certificates;

        foreach( $model_certificates as $model_certificate ) {
            require('form_certificate.php');
        }
        ?>
        <tr id="certificates_table_lastRow">
            <td colspan="4" style="text-align: left;">
                <?php
                echo CHtml::imageButton('images/add.png', array(
                    'onclick' => "
                        $.ajax({
                            url: 'index.php?r=livingPlant/ajaxCertificate'
                        }).done(function(data) {
                            $('#certificates_table_lastRow').before(data);
                        });
                        return false;"
                ));
                ?>
            </td>
        </tr>
    </table>
</div>
