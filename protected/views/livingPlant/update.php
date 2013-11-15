<?php
$this->breadcrumbs = array(
    Yii::t('jacq', 'Living Plants') => array('index'),
    $model_livingPlant->id => array('update', 'id' => $model_livingPlant->id),
    Yii::t('jacq', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('jacq', 'Create Living Plant'), 'url' => array('create')),
    array('label' => Yii::t('jacq', 'Manage Living Plant'), 'url' => array('admin')),
);
?>

<h1><?php echo Yii::t('jacq', 'Update Living Plant'); ?> <?php echo $model_livingPlant->id; ?></h1>

<?php
// check if saving was successfull
if( $success ) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            MsgBox.infoMsg('<?php echo Yii::t('jacq', 'Successfully saved'); ?>');
        });
    </script>
    <?php
}
?>

<?php
echo $this->renderPartial('_form', $data);
