<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('garden_site_id')); ?>:</b>
	<?php echo CHtml::encode($data->garden_site_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('accession_number')); ?>:</b>
	<?php echo CHtml::encode($data->accession_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ipen_number')); ?>:</b>
	<?php echo CHtml::encode($data->ipen_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phyto_control')); ?>:</b>
	<?php echo CHtml::encode($data->phyto_control); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tree_record_id')); ?>:</b>
	<?php echo CHtml::encode($data->tree_record_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phyto_sanitary_product_number')); ?>:</b>
	<?php echo CHtml::encode($data->phyto_sanitary_product_number); ?>
	<br />


</div>