
<div class="col-lg-12">
            <div class="col-lg-5 col-lg-offset-7 col-sm-12 text-right well" style="padding: 0px; padding-right: 15px; padding-bottom: 15px; padding-left: 15px;">
            
            <?php 
	            if(!in_array($data['plugin_data']['entstatus'], $data['plugin_data']['config'][$data['plugin_data']['modulename']]['hide_on_status'])){
	          
	          echo "<h3>Online Payment</h3><h2><small>".Language::translate("Total").":</small> ".$data['plugin_data']['total']." ".$data['plugin_data']['curencysymbol']."</h2>";   
            
            $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
			$pay_url = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			
			$pay_url = preg_replace('/&?ps=[^&]*/', '', $pay_url);
			$pay_url = preg_replace('/&?paynow=[^&]*/', '', $pay_url);
			$pay_url = preg_replace('/&?token=[^&]*/', '', $pay_url);			
            $pay_url.="&paynow=true";
            
            ?>
            <a href="<?php echo $pay_url; ?>" class ="btn btn-success btn-lg"><?php echo Language::translate("Pay Now with PayPal"); ?></a><br><br>

<input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="<?php echo Language::translate("Pay Now with PayPal"); ?>">
      
<?php }

else echo "<h3>Payment Status</h3><h2><s><small>".Language::translate("Total").":</small> ".$data['plugin_data']['total']." ".$data['plugin_data']['curencysymbol']."</s></h2><p style='font-weight:bolder; font-size:60px; color: green;'>PAID</p>";
?>
               </div><!-- /.col-lg-6 -->
   
              </div>  
                