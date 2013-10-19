<?php
/* @var $this ClassificationBrowserController */

$this->breadcrumbs=array(
	Yii::t('jacq', 'Classification Browser'),
);
?>
<!-- initialize jstree for classification browser -->
<script type="text/javascript">
    var jacq_url = '<?php echo $this->JACQ_URL; ?>';
    var initital_data = <?php echo ($data) ? $data : 'null'; ?>;

    // handler function for jstree ajax data
    var jstree_data = function(n) {
        // extract citation & taxon information from selected node
        var link = (n.children) ? n.children('a').first() : n;
        var taxon_id = (link.attr) ? link.attr("data-taxon-id") : 0;
        var reference_id = (link.attr) ? link.attr("data-reference-id") : 0;
        var reference_type = (link.attr) ? link.attr("data-reference-type") : 0;

        // check if we have a valid reference-type, if not use the default one
        if( !reference_type ) {
            reference_type = $('#classificationBrowser_referenceType').val();
        }

        // check for a set reference, if not use default one
        if( !reference_id ) {
            reference_id = $('#classificationBrowser_referenceID').val();
        }

        // return information
        return {
            "referenceType": reference_type,
            "referenceId": reference_id,
            "taxonID": taxon_id
        };
    }

    // init function for jstree
    function init_jstree() {
        // delete any old instance
        $('#jstree_classificationBrowser').jstree( 'destroy' );

        // initialize jsTree for organisation
        $('#jstree_classificationBrowser').jstree({
            "json_data" : {
                    "data" : initital_data,
                    "ajax" : {
                        "url" : jacq_url + "index.php?r=jSONjsTree/japi&action=classificationBrowser",
                        "data": jstree_data,
                        "dataType": "jsonp"
                    }
            },
            "plugins" : [ "themes", "json_data" ],
            "core": {"html_titles": true}
        });
    }

    // called once jquery is ready
    $(document).ready(function(){
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

        // add click handlers for jsTree nodes (since they should do nothing)
        $('#jstree_classificationBrowser a').live('click', function() {return false;});

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
                            $('#infoBox').html('<b>also used in:</b>');

                            // remember return reference-data
                            $('#infoBox').data('referenceData', data);

                            // add all found references to infobox
                            for( var i = 0; i < data.length; i++ ) {
                                var referenceInfo = data[i].referenceName +
                                    '&nbsp;<span id="arrow_down_' + i + '" style="cursor: pointer;" onclick="arrow_down(' + i + '); return false;"><img src="images/arrow_down.png"></span>' +
                                    '&nbsp;<span id="world_link_' + i + '" style="cursor: pointer;" onclick="world_link(' + i + '); return false;"><img src="images/world_link.png"></span>';
                                $('#infoBox').html($('#infoBox').html() + '<br/>' + referenceInfo);
                            }
                        }
                        // if not display notification
                        else {
                            $('#infoBox').html('no other references');
                        }

                        $('#infoBox').show();
                    }
                });

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
            source: jacq_url + 'index.php?r=autoComplete/taxon',
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
    });

    /**
     * open link to other classification
     */
    function world_link( p_i ) {
        var index = p_i;
        var referenceData = $('#infoBox').data('referenceData');
        referenceData = referenceData[index];

        var url = '<?php echo CHtml::encode($this->createUrl('/dataBrowser/classificationBrowser')); ?>&referenceType=' + referenceData.referenceType +
            '&referenceId=' + referenceData.referenceId + "&filterId=" + referenceData.taxonID;

        window.open(url);
    }

    /**
     * add other reference to tree
     */
    function arrow_down( p_i ) {
        var index = p_i;
        var referenceData = $('#infoBox').data('referenceData');
        referenceData = referenceData[index];
        var liElement = $('#infoBox').data('liElement');
        var addedReferences = liElement.data(referenceData.referenceType);

        // check if there are references stored already
        if( addedReferences == null ) {
            addedReferences = {};
        }

        // ignore if references was already added
        if( typeof addedReferences[referenceData.referenceId] !== "undefined" ) return;

        // setup node data
        var nodeData = {
            data: {
                title: referenceData.referenceName,
                attr: {
                    "data-taxon-id": referenceData.taxonID,
                    "data-reference-id": referenceData.referenceId,
                    "data-reference-type": referenceData.referenceType
                },
                icon: "images/book_open.png"
            }
        };

        // check if node has children
        if( referenceData.hasChildren ) {
            nodeData.state = 'closed';
        }

        // finally add the node to the classification-browser
        $('#jstree_classificationBrowser').jstree( 'create_node', liElement, "after", nodeData );

        // remember added reference
        addedReferences[referenceData.referenceId] = true;
        liElement.data(referenceData.referenceType, addedReferences);
    }

</script>


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
