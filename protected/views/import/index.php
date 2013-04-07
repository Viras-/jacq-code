<h1>Running import, please be patient...</h1>

<p><?php echo $start; ?>/<?php echo $akzessionCount; ?> processed.</p>

<script type="text/javascript">
    function startImport() {
        window.location = '<?php echo $this->createUrl('import/import', array('start' => $start)); ?>';
    }
    
    setTimeout(startImport, 5000);
</script>
