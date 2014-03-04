// called once jquery is ready

// bind to click handler for submission
$('#statistics_send').bind('click', function() {
    var period_start = $('#statistics_period_start').val();
    var period_end = $('#statistics_period_end').val();
    var type = $('#statistics_type').val();
    var interval = $('#statistics_interval').val();

    $.ajax({
        url: jacq_url + "index.php?r=jSONStatistics/japi&action=showResults",
        data: {
            periodStart: period_start,
            periodEnd: period_end,
            type: type,
            interval: interval
        },
        dataType: "jsonp",
        success: function(data) {
            $('#statistics_result').html(data.display);
        }
    });
});