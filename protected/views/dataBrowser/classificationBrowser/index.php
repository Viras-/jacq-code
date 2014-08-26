<?php
/* @var $this ClassificationBrowserController */

$this->breadcrumbs = array(
    Yii::t('jacq', 'Classification Browser'),
);
?>
<div align="left">
    <form action='#' onsubmit="return false;" style="<?php if ($referenceType == 'citation' && $referenceId > 0) echo "display: none;"; ?>">
        <select id="classificationBrowser_referenceType">
            <option value=""><?php echo Yii::t('jacq', 'select reference type'); ?></option>
            <!--<option value="person">person</option>-->
            <!--<option value="service">service</option>-->
            <!--<option value="specimen">specimen</option>-->
            <option value="periodical"><?php echo Yii::t('jacq', 'citation'); ?></option>
        </select>
        <br />
        <select id="classificationBrowser_referenceID">
            <option value=""><?php echo Yii::t('jacq', 'select classification reference'); ?></option>
        </select>
        <br />
        <input id="filter_taxonID" type="hidden" />
        <br />
        <?php echo Yii::t('jacq', 'Filter'); ?>: <input id="scientificName" type="text" />
        <input id="filter_button" type="image" src="images/magnifier.png" alt="filter" />
        
        <span style="margin-left: 30px;">
            <label><input type="checkbox" id="open_all"> <?php echo Yii::t('jacq', 'expand Subhierarchies'); ?></label>
            <label><input type="checkbox" id="include-author-names"> <?php echo Yii::t('jacq', 'include author names'); ?></label>
        </span>
        <div id="progressbar" style="width:50%; height:10px; position:fixed; top:60px;"></div>
        <br />
    </form>
    <div id="jstree_classificationBrowser" style="padding-top: 10px; padding-bottom: 10px;"></div>
    <div id="infoBox" style="display: none; padding: 5px; background: #FFFFFF; border: 1px solid #000000; position: absolute; top: 0px; left: 0px;"><?php echo Yii::t('jacq', 'Info'); ?></div>
</div>

<?php
// widget for authorization management
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'authorization_management_dialog',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => Yii::t('jacq', 'Authorization'),
        'autoOpen' => false,
        'resizable' => false,
        'width' => 630,
        'buttons' => array(
            array(
                'text' => Yii::t('jacq', 'Close'),
                'click' => new CJavaScriptExpression("function() { $(this).dialog('close'); }")
            ),
            array(
                'text' => Yii::t('jacq', 'Save'),
                'click' => new CJavaScriptExpression('authorizationSave')
            ),
        ),
        'close' => new CJavaScriptExpression('authorizationClose'),
    ),
));
?>
<div id="authorization_view" style="height: 400px;"></div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
