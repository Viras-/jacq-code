<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('department')); ?>:</b>
	<?php echo CHtml::encode($data->department); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('greenhouse')); ?>:</b>
	<?php echo CHtml::encode($data->greenhouse); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_organisation_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_organisation_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gardener_id')); ?>:</b>
	<?php echo CHtml::encode($data->gardener_id); ?>
	<br />


</div>