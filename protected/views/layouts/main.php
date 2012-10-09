<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
         <!-- plus as tab plugin -->
         <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/emulatetab.joelpurra.js" ></script>
         <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/plusastab.joelpurra.js" ></script>
         <script type="text/javascript">
             JoelPurra.PlusAsTab.setOptions({
                 key: 13
             });
         </script>
         <!-- jsTree -->
         <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.jstree/jquery.jstree.js" ></script>
         
         <!-- custom styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" />
    </head>

    <body>

        <div class="container" id="page">

            <div id="header">
                <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
            </div><!-- header -->

            <div id="mainmenu">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => Yii::t('jacq', 'Living Plant'), 'url' => array('/livingPlant'), 'visible' => Yii::app()->user->checkAccess('oprtn_readLivingplant')),
                        array('label' => Yii::t('jacq', 'Garden Site'), 'url' => array('/organisation'), 'visible' => Yii::app()->user->checkAccess('oprtn_createOrganisation')),
                        array('label' => Yii::t('jacq', 'Tree Record File'), 'url' => array('/treeRecordFile'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => Yii::t('jacq', 'Login'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => Yii::t('jacq', 'Logout') . ' (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                    ),
                ));
                ?>
            </div><!-- mainmenu -->
            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
<?php endif ?>

<?php echo $content; ?>

            <div class="clear"></div>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->
   </body>
</html>
