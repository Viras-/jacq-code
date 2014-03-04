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
        <select id="statistics_type">
            <option value="new_names">New Names</option>
            <option value="new_citations" selected="selected">New Citations</option>
            <option value="new_specimens">New Specimens</option>
            <option value="new_type_specimens">New Type-Specimens</option>
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
</div>
