<h1>Running import, please be patient...</h1>

<script type="text/javascript">
    function startImport() {
        window.location = '<?php echo $this->createUrl('import/import', array('start' => $start)); ?>';
    }
    
    setTimeout(startImport, 5000);
</script>
