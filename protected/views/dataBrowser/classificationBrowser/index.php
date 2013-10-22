<?php
/* @var $this ClassificationBrowserController */

$this->breadcrumbs=array(
	Yii::t('jacq', 'Classification Browser'),
);
?>
<div align="left">
    <form action='#' onsubmit="return false;" style="<?php if($referenceType == 'citation' && $referenceId > 0) echo "display: none;"; ?>">
        <select id="classificationBrowser_referenceType">
            <option value="">select reference type</option>
            <!--<option value="person">person</option>-->
            <!--<option value="service">service</option>-->
            <!--<option value="specimen">specimen</option>-->
            <option value="periodical">citation</option>
        </select>
        <br />
        <select id="classificationBrowser_referenceID">
            <option value="">select classification reference</option>
        </select>
        <br />
        <input id="filter_taxonID" type="hidden" />
        <br />
        Filter: <input id="scientificName" type="text" />
        <input id="filter_button" type="image" src="images/magnifier.png" alt="filter" />
        <br />
    </form>
    <div id="jstree_classificationBrowser" style="padding-top: 10px; padding-bottom: 10px;"></div>
    <div id="infoBox" style="display: none; padding: 5px; background: #FFFFFF; border: 1px solid #000000; position: absolute; top: 0px; left: 0px;">Info</div>
</div>
