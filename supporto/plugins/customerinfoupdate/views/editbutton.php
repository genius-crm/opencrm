<?php if(isset($GLOBALS['plugin_data']['customerinfoupdate']['updatestatus']) && $GLOBALS['plugin_data']['customerinfoupdate']['updatestatus']=="SUCCESS"): ?>
	<div class="alert alert-success">Thank you, your informations had been updated successfully!</div>
<?php elseif(isset($GLOBALS['plugin_data']['customerinfoupdate']['updatestatus']) && $GLOBALS['plugin_data']['customerinfoupdate']['updatestatus']=="FAILED"): ?>
	<div class="alert alert-danger">Something went wrong with your operation, please try again!</div>
<?php endif;  ?>

<div class="col-lg-12 text-right" style="margin-top:5px; margin-bottom:10px; padding:0px;">
            <a class="btn btn-success btn-lg" href="index.php?module=<?php echo $data['plugin_data']['customerinfoupdate']['module']; ?>&action=edit&id=<?php echo $data['plugin_data']['customerinfoupdate']['id']; ?>">Edit <?php echo $data['plugin_data']['customerinfoupdate']['module']; ?> Informations</a>
            
              </div>  
                