        <div class="row">
            <table style="width: auto;">
                <tr>
                    <td>
                    <?php echo $form->labelEx($model_acquisitionDate,'year'); ?>
                    <?php echo $form->textField($model_acquisitionDate,'year', array( 'size' => 4 ) ); ?>
                    <?php echo $form->error($model_acquisitionDate,'year'); ?>
                    </td>
                    <td>
                    <?php echo $form->labelEx($model_acquisitionDate,'month'); ?>
                    <?php echo $form->textField($model_acquisitionDate,'month', array( 'size' => 2 )); ?>
                    <?php echo $form->error($model_acquisitionDate,'month'); ?>
                    </td>
                    <td>
                    <?php echo $form->labelEx($model_acquisitionDate,'day'); ?>
                    <?php echo $form->textField($model_acquisitionDate,'day', array( 'size' => 2 )); ?>
                    <?php echo $form->error($model_acquisitionDate,'day'); ?>
                    </td>
                </tr>
            </table>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model_acquisitionDate,'custom'); ?>
		<?php echo $form->textField($model_acquisitionDate,'custom'); ?>
		<?php echo $form->error($model_acquisitionDate,'custom'); ?>
	</div>

<hr/>
