<?php if(isset($data['plugin_data']['entitystatusupdate']['operationresult']) && $data['plugin_data']['entitystatusupdate']['operationresult']=="SUCCESS"): ?>
	<div class="alert alert-success">Thank you, your operation has been completed, we will check it as soon as possible!</div>
<?php elseif(isset($data['plugin_data']['entitystatusupdate']['operationresult']) && $data['plugin_data']['entitystatusupdate']['operationresult']=="CANCEL"): ?>
	<div class="alert alert-danger">Something went wrong with your operation, please try again!</div>
<?php endif;  ?>