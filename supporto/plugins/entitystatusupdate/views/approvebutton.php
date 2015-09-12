
            <div class="col-lg-6 col-sm-12 text-center" >
            
            <div class="panel panel-default">
                        <div class="panel-heading">
                            Customer Interactions
                        </div>
                        
                        
                        <div class="panel-body">
            
            <?php
	            if(!in_array($data['plugin_data']['entstatus'], $data['plugin_data']['entitystatusupdate']['config'][$data['plugin_data']['modulename']]['hide_on_status'])): ?>
	       <form method="post" class="pull-left" action="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            <input type="hidden" name="update" value="true">
            <button type="submit" class="btn btn-lg btn-success"><?php echo Language::translate("Approve ".$data['plugin_data']['modulename']); ?></button>
            </form>
             <font size="6px" color="grey">or</font> 
            <form method="post" class="pull-right" action="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            <input type="hidden" name="update" value="true">
            <input type="hidden" name="reject" value="true">
            <button type="submit" class="btn btn-lg btn-danger"><?php echo Language::translate("Reject ".$data['plugin_data']['modulename']); ?></button>
            </form>
	            <? endif; ?>

                        </div>
            </div>
               </div><!-- /.col-lg-6 -->
   
                