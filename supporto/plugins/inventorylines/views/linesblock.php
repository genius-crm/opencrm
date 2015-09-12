  <?php 
  
/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */



  if(isset($data['plugin_data']['data']['inventorylines']) && count($data['plugin_data']['data']['inventorylines'])>0 && $data['plugin_data']['data']['inventorylines']!=""){ ?>
            <div class="col-lg-12">
            
               
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php  echo Language::translate("Products/Services"); ?>
                        </div>
                        
                        <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                        <thead>
                        	<?php
                        	
                        	foreach($data['plugin_data']['data']['inventorylines']['fields'] as $fname){
                        		if(isset($data['plugin_data']['inventorylines']['fieldslabels'][$fname]))
                        			$flabel=$data['plugin_data']['inventorylines']['fieldslabels'][$fname];
                        		else $flabel=$fname;
	                        	echo "<th>".Language::translate($flabel)."</th>";
                        	}
                        	echo "</thead>";
                        		foreach($data['plugin_data']['data']['inventorylines']['lineitems'] as $invline){
                        			if(isset($invline['productname'])) $type="Products";
                        			else $type="Services";
                        			
                        			echo "<tr>";
                        			foreach($data['plugin_data']['data']['inventorylines']['fields'] as $fname){
                        				if(isset($data['plugin_data']['commonfieldsassoc'][$type][$fname])) $fieldval=$invline[$data['plugin_data']['commonfieldsassoc'][$type][$fname]];
                        				else $fieldval=$invline[$fname];
                        				
                        				
                        				
                        				if(in_array($fname, array("listprice","unit_price"))){
                        					if($fieldval=="") $fieldval=0;
											$fieldval=number_format($fieldval,2)." ".$data['plugin_data']['curencysymbol'];
										}
                        				elseif($fname=="totalprice"){
	                        				$fieldval=number_format($invline["listprice"]*$invline["quantity"],2)." ".$data['plugin_data']['curencysymbol'];
                        				}
                        				elseif($fname=="finaltotalprice"){
                        					$finalprice=$invline["listprice"]*$invline["quantity"];
                        					if(isset($invline["discount_amount"]) && $invline["discount_amount"]!=""){
	                        					$finalprice-=$invline["discount_amount"];
                        					}
                        					else if(isset($invline["discount_percent"]) && $invline["discount_percent"]!=""){
	                        					$finalprice-=(($invline["discount_percent"]*$finalprice)/100);
                        					}
                        					
	                        				$fieldval=number_format($finalprice,2)." ".$data['plugin_data']['curencysymbol'];
                        				}
                        				
                        			
                        				
                        				elseif($fname=="commonf_discount"){ 
                        					if(isset($invline["discount_amount"]) && $invline["discount_amount"]!=""){
	                        					$fieldval=number_format($fieldval,2)." ".$data['plugin_data']['curencysymbol'];
                        					}
                        					else if(isset($invline["discount_percent"]) && $invline["discount_percent"]!=""){
	                        				$fieldval=number_format($fieldval,2)." %";
	                        				}
                        				}
                        				
                        				if($fname=="commonf_name"){
                        					if($type=="Products")
                        						$fieldval="<a href='index.php?module=".$type."&action=index&productid=".substr($invline['productid'],strpos($invline['productid'], "x")+1)."'>".$fieldval."</a>";
                        					else if($type=="Services")
                        						$fieldval="<a href='index.php?module=".$type."&action=index&id=".substr($invline['productid'],strpos($invline['productid'], "x")+1)."'>".$fieldval."</a>";	
                        				}	
                        				if($fname=="commonf_namedesc"){
                        					$fieldval=$invline[$data['plugin_data']['commonfieldsassoc'][$type]['commonf_name']];
                        					if($type=="Products")
                        						$fieldval="<b><a href='index.php?module=".$type."&action=index&productid=".substr($invline['productid'],strpos($invline['productid'], "x")+1)."'>".$fieldval."</a></b><hr style='margin-top: 5px; margin-bottom: 5px;'>".$invline['comment'];
                        					else if($type=="Services")
                        						$fieldval="<b><a href='index.php?module=".$type."&action=index&id=".substr($invline['productid'],strpos($invline['productid'], "x")+1)."'>".$fieldval."</a></b><hr style='margin-top: 5px; margin-bottom: 5px;'>".$invline['comment'];	
                        				}
                        				
		                        		echo "<td>".Language::translate($fieldval)."</td>";
		                        	}
		                        	echo "</tr>";
		                        }
	                       
                        	?>
                        	<tr><td colspan="<?php echo count($data['plugin_data']['data']['inventorylines']['fields'])-2; ?>"></td><td class="text-right"><b><?php  echo Language::translate("Subtotal"); ?></b></td><td><?php echo number_format($data['plugin_data']['entdetail']['hdnSubTotal'],2); ?> <?php echo $data['plugin_data']['curencysymbol']; ?></td></tr>
                        	<tr><td colspan="<?php echo count($data['plugin_data']['data']['inventorylines']['fields'])-2; ?>"></td><td class="text-right"><b><?php  echo Language::translate("Shipment"); ?></b></td><td><?php echo number_format($data['plugin_data']['entdetail']['hdnS_H_Amount'],2); ?> <?php echo $data['plugin_data']['curencysymbol']; ?></td></tr>
	                        	<tr><td colspan="<?php echo count($data['plugin_data']['data']['inventorylines']['fields'])-2; ?>"></td><td class="text-right"><b><?php  echo Language::translate("Discount"); ?></b></td><td><?php if(isset($data['plugin_data']['entdetail']['hdnDiscountAmount']) && $data['plugin_data']['entdetail']['hdnDiscountAmount']!="") echo number_format($data['plugin_data']['entdetail']['hdnDiscountAmount'],2); else echo "0.00"; ?> <?php echo $data['plugin_data']['curencysymbol']; ?></td></tr>
	                        	<tr><td colspan="<?php echo count($data['plugin_data']['data']['inventorylines']['fields'])-2; ?>"></td><td class="text-right"><b><?php  echo Language::translate("Tax"); ?></b></td><td><?php echo number_format($data['plugin_data']['entdetail']['hdnS_H_Percent']*$data['plugin_data']['entdetail']['pre_tax_total']/100,2); ?> <?php echo $data['plugin_data']['curencysymbol']; ?></td></tr>
	                        	<tr><td colspan="<?php echo count($data['plugin_data']['data']['inventorylines']['fields'])-2; ?>"></td><td class="text-right"><h4><b><?php  echo Language::translate("Total"); ?></b></h4></td><td><h4><?php echo number_format($data['plugin_data']['entdetail']['hdnGrandTotal'],2); ?> <?php echo $data['plugin_data']['curencysymbol']; ?></h4></td>
                        </tr>
						    
						</table>
						</div>
                            
                            
                    </div>
                    <!-- /.panel -->
               
               </div>
                <!-- /.col-lg-6 -->
                
                <?php  } ?>