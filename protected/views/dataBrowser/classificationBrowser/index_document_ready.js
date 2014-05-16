// called once jquery is ready

// initialize the jsTree
init_jstree();

// update references when new type is chosen
$('#classificationBrowser_referenceType').bind('change', function() {
    $.ajax( {
        url: jacq_url + "index.php?r=jSONClassification/japi&action=references",
        data: {
            referenceType: $('#classificationBrowser_referenceType').val()
        },
        dataType: "jsonp"
    } ).done( function(data) {
        // reset reference drop-down
        $('#classificationBrowser_referenceID').html('<option value="">select classification reference</option>');

        // add all references as drop-down options
        for( var i = 0; i < data.length; i++ ) {
            // create new option element for reference
            var referenceOption = $('<option>');
            referenceOption.val(data[i].id);
            referenceOption.html(data[i].name);
            // append new option to reference select
            $('#classificationBrowser_referenceID').append(referenceOption);
        }

        // trigger a change to the reference dropdown (since content was updated)
        $('#classificationBrowser_referenceID').trigger('change');
    } );
});

// update tree when a new reference is chosen
$('#classificationBrowser_referenceID').bind('change', function() {
    initital_data = null;
    init_jstree();
});

// add click handlers for jsTree nodes
$('#jstree_classificationBrowser a').live('click', function() {
    if ($('#open_all')[0].checked) {
        $('#jstree_classificationBrowser').jstree('true').open_all(jQuery(this), 1);
    } else {
        $('#jstree_classificationBrowser').jstree('true').toggle_node(jQuery(this));
    }
    return false;
});

// add hover handler for all info links
$('#jstree_classificationBrowser .infoBox').live({
    mouseover: function() {
        var position = $(this).position();
        var taxonID = $(this).parent().attr('data-taxon-id');
        var referenceId = $(this).parent().attr('data-reference-id');
        var liElement = $(this).parent().parent();

        // keep reference to li-Element
        $('#infoBox').data('liElement', liElement);

        // re-position the infobox
        $('#infoBox').css("top", position.top);
        $('#infoBox').css("left", position.left + $(this).width() + 10);

        // display loading & show the infobox
        $('#infoBox').html( "loading..." );
        $('#infoBox').fadeIn(100);

        // query the JSON-services for detail information
        $.ajax({
            url: jacq_url + "index.php?r=jSONClassification/japi&action=nameReferences",
            data: {
                taxonID: taxonID,
                excludeReferenceId: referenceId
            },
            dataType: "jsonp",
            success: function(data) {
                // check if we found additional references
                if( data && data.length && data.length > 0 ) {
                    $('#infoBox').html('<b>also used in:</b><br/>');

                    // remember return reference-data
                    $('#infoBox').data('referenceData', data);

                    // add all found references to infobox
                    var referenceInfos = new Array();
                    for( var i = 0; i < data.length; i++ ) {
                        var referenceInfo = data[i].referenceName +
                            '&nbsp;<span id="arrow_down_' + i + '" style="cursor: pointer;" onclick="arrow_down(' + i + '); return false;"><img src="images/arrow_down.png"></span>' +
                            '&nbsp;<span id="world_link_' + i + '" style="cursor: pointer;" onclick="world_link(' + i + '); return false;"><img src="images/world_link.png"></span>';
                        referenceInfos.push(referenceInfo);
                    }
                    $('#infoBox').html($('#infoBox').html() + referenceInfos.join("<br/>"));
                }
                // if not display notification
                else {
                    $('#infoBox').html('<i>no other references</i>');
                }

                // add download link
                $('#infoBox').html($('#infoBox').html() + '<br /><b>actions</b><br />');
                $('#infoBox').html($('#infoBox').html() + '<span style="cursor: pointer;" onclick="download(\'citation\', ' + referenceId + ',' + taxonID + '); return false;"><img src="images/disk.png"></span>');

                // finally show the info box
                $('#infoBox').show();
            }
        });

        return false;
    }
});

// add click handler for access handling
$('#jstree_classificationBrowser .acl').live({
    click: function() {
        var tax_syn_ID = $(this).attr('data-tax-syn-id');

        // load authorization view and assign it to div
        $('#authorization_view').load(
                jacq_url + "index.php?r=authorization/ajaxClassificationAccess&tax_syn_ID=" + tax_syn_ID,
                null,
                function(responseText, textStatus, XMLHttpRequest) {
                    $('#authorization_management_dialog').dialog('open');
                }
        );

        return false;
    }
});

// Add hover-behaviour for infoBox
$('#infoBox').mouseleave( function(evt) {
    if( $(evt.target).attr('id') != 'infoBox' ) return;

    $(this).fadeOut(100);
} );

// initialize auto-complete
$('#scientificName').autocomplete({
    source: jacq_url + 'index.php?r=autoComplete/scientificName',
    minLength: 2,
    select: function( event, ui ) {
        if( typeof ui.item !== "undefined" ) {
            $( "#filter_taxonID" ).val( ui.item.id );
        }
    },
    change: function( event, ui ) {
        if( ui.item == null ) {
            $( "#filter_taxonID" ).val( 0 );
        }
    }
});

// bind to click handler for filter
$('#filter_button').bind('click', function() {
    var filter_id = $('#filter_taxonID').val();
    var reference_type = $('#classificationBrowser_referenceType').val();
    var reference_id = $('#classificationBrowser_referenceID').val();

    if( filter_id > 0 && reference_type != "" && reference_id > 0 ) {
        $('#jstree_classificationBrowser').jstree('destroy');
        $('#jstree_classificationBrowser').html('');

        $.ajax({
            url: jacq_url + "index.php?r=jSONjsTree/japi&action=classificationBrowser",
            data: {
                referenceType: reference_type,
                referenceId: reference_id,
                filterId: filter_id
            },
            dataType: "jsonp",
            success: function(data) {
                // remember initital data
                initital_data = data;

                // re-inititalize jstree
                init_jstree();
            }
        });
    }
});