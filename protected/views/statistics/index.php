<?php
/* @var $this StatisticsController */

$this->breadcrumbs=array(
	Yii::t('jacq', 'Statistics'),
);
?>
<div align="left">
    <form action='#' onsubmit="return false;">
        <input id="statistics_period_start" type="text" value="<?php echo $periodStart; ?>" maxlength="10" size="10">
        &mdash;
        <input id="statistics_period_end" type="text" value="<?php echo $periodEnd; ?>" maxlength="10" size="10">
        &rightarrow;
        <select id="statistics_updated">
            <option value="0" selected="selected">New</option>
            <option value="1">Updated</option>
        </select>
        <select id="statistics_type">
            <option value="names">Names</option>
            <option value="citations">Citations</option>
            <option value="names_citations">Names used in Citations</option>
            <option value="specimens" selected="selected">Specimens</option>
            <option value="type_specimens">Type-Specimens</option>
            <option value="names_type_specimens">use of names for Type-Specimens</option>
            <option value="types_name">Types per Name</option>
            <option value="synonyms">Synonyms</option>
        </select>
        /
        <select id="statistics_interval">
            <option value="year">year</option>
            <option value="month">month</option>
            <option value="week" selected="selected">week</option>
            <option value="day">day</option>
        </select>
        &nbsp;
        <input id="statistics_send" type="image" src="images/magnifier.png" alt="submit" />
        <br />
    </form>
    <div id="statistics_result" style="padding-top: 10px; padding-bottom: 10px; overflow:auto;"></div>
    <div id="statistics_plot" style="width:100%;height:300px"></div>
</div>
