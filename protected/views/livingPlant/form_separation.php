	<div class="row">
            <table style="width: auto;">
                <tr>
                    <td>
                    <?php echo $form->labelEx($model_separation,'separation_type_id'); ?>
                    <?php echo $form->dropDownList($model_separation,'separation_type_id', CHtml::listData(SeparationType::model()->findAll(), 'id', 'type')); ?>
                    <?php echo $form->error($model_separation,'separation_type_id'); ?>
                    </td>
                    <td>
                    <?php echo $form->labelEx($model_separation,'date'); ?>
                    <?php echo $form->textField($model_separation,'date'); ?>
                    <?php echo $form->error($model_separation,'date'); ?>
                    </td>
                    <td>
                    <?php echo $form->labelEx($model_separation,'annotation'); ?>
                    <?php echo $form->textField($model_separation,'annotation'); ?>
                    <?php echo $form->error($model_separation,'annotation'); ?>
                    </td>
                </tr>
            </table>
	</div>
