<!-- specimens -->
<div class="row">
    <?php require("form_specimens.php"); ?>
</div>

<!-- display images -->
<div class="row">
    <?php
    if (!$model_botanicalObject->isNewRecord) {

        $imageServer = NULL;

        if ($model_botanicalObject->organisation != NULL) {
            $imageServer = $model_botanicalObject->organisation->getImageServer();
        }

        if ($imageServer != NULL) {
            // construct new image server instance
            $jacqImageServer = new JacqImageServer($imageServer->base_url, $imageServer->key);

            $resourcesDetails = $jacqImageServer->listResources(array(
                $model_livingPlant->accessionNumber . '_%'
            ));

            $resources = [];
            foreach ($resourcesDetails as $resourceDetails) {
                $resources[] = $resourceDetails['identifier'];
            }
            $resources = join(',', $resources);

            foreach ($resourcesDetails as $resourceDetails) {
                ?>
                <div style="display: table-cell; margin-right: 1.0em; text-align: center;">
                    <a href="<?php echo $imageServer->base_url; ?>/jacq-viewer/viewer.html?rft_id=<?php echo $resourceDetails['identifier']; ?>&identifiers=<?php echo $resources; ?>" target="_blank">
                        <img width="160" src="<?php echo $imageServer->base_url; ?>/adore-djatoka/resolver?url_ver=Z39.88-2004&rft_id=<?php echo $resourceDetails['identifier'] ?>&svc_id=info:lanl-repo/svc/getRegion&svc_val_fmt=info:ofi/fmt:kev:mtx:jpeg2000&svc.format=image/jpeg&svc.scale=160,0" />
                    </a>
                    <br />
                    <?php
                    echo Html::checkBox(
                            "resource_" . $resourceDetails['identifier'], ($resourceDetails['public'] == 0) ? false : true, array(
                        'onchange' => "
                                $.ajax({
                                    url: 'index.php?r=livingPlant/ajaxImageServerResource&botanical_object_id=" . $model_botanicalObject->id . "&identifier=" . $resourceDetails['identifier'] . "&public=' + this.checked
                                });"
                            )
                    );
                    ?>
                </div>
                <?php
            }
        }
    }
    ?>
</div>
