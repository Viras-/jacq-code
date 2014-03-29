<!-- specimens -->
<div class="row">
    <?php require("form_specimens.php"); ?>
</div>

<!-- display images -->
<div class="row">
    <?php
    $imageServer = NULL;
    
    if( $model_botanicalObject->organisation != NULL ) {
        $imageServer = $model_botanicalObject->organisation->getImageServer();
    }
    
    if( $imageServer != NULL ) {
        // construct new image server instance
        $jacqImageServer = new JacqImageServer($imageServer->base_url, $imageServer->key);
        
        $resources = $jacqImageServer->listResources(array(
            $model_livingPlant->accessionNumber . '_%'
        ));
        
        foreach( $resources as $resource ) {
        ?>
    <a href="<?php echo $imageServer->base_url; ?>/jacq-viewer/viewer.html?rft_id=<?php echo $resource; ?>&identifiers=<?php echo join(',', $resources); ?>" target="_blank">
        <img width="160" src="<?php echo $imageServer->base_url; ?>/adore-djatoka/resolver?url_ver=Z39.88-2004&rft_id=<?php echo $resource ?>&svc_id=info:lanl-repo/svc/getRegion&svc_val_fmt=info:ofi/fmt:kev:mtx:jpeg2000&svc.format=image/jpeg&svc.scale=160,0" />
    </a>
    &nbsp;
        <?php
        }
    }
    ?>
</div>
