/**
 * Initialize the message dialog(s)
 */
$(document).ready(function() {
    // setup the error dialog
    $('#error-dialog').dialog({
        resizable: false,
        modal: true,
        autoOpen: false,
        draggable: false,
        buttons: {
            'Okay': function() {
                $(this).dialog('close');
            }
        }
    });

    // setup the info dialog
    $('#info-dialog').dialog({
        resizable: false,
        modal: true,
        autoOpen: false,
        draggable: false,
        buttons: {
            'Okay': function() {
                $(this).dialog('close');
            }
        }
    });
});

/**
 * Helper class for msgbox functionality based on jquery-ui
 */
function MsgBox() {
}
;

/**
 * Display an error message
 * @param string p_errorMsg Error message to display
 * @returns {undefined}
 */
MsgBox.errorMsg = function(p_errorMsg) {
    $('#error-dialog_text').html(p_errorMsg);
    $('#error-dialog').dialog('open');
}

/**
 * Display an info message
 * @param string p_infoMsg
 * @returns {undefined}
 */
MsgBox.infoMsg = function(p_infoMsg) {
    $('#info-dialog_text').html(p_infoMsg);
    $('#info-dialog').dialog('open');
}
