<?php
/* * *******************************************************************************
 * The content of this file is subject to the MYC Vtiger Customer Portal license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is Proseguo s.l. - MakeYourCloud
 * Portions created by Proseguo s.l. - MakeYourCloud are Copyright(C) Proseguo s.l. - MakeYourCloud
 * All Rights Reserved.
 * ****************************************************************************** */
?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script type="text/javascript" src="plugins/customerinfoupdate/views/assets/js/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="plugins/customerinfoupdate/views/assets/css/bootstrap-datetimepicker.min.css" />
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo Language::translate($module." Informations Update"); ?> </h1>
                    </div>
                <!-- /.col-lg-12 -->
            </div>
            
          <div class="row">
          
<div class="col-lg-12">
             
  <?php if(isset($data['recordinfo']) && count($data['recordinfo'])>0 && $data['recordinfo']!=""){ 
  			echo "<form method='POST' action='index.php?module=".$module."&action=index&id=".$_GET['id']."'>";
   
  		$tc=1;
          foreach($data['pluginconfig'][$module]['fields'] as $field){
          if($tc==1) echo "<div class='row'>";
          
          
          if($data['fieldinfos'][$field]['type']['name']=="picklist"){
	          $fieldform='<select  class="form-control" name="updatedinfos['.$field.']"><option>- - - - - -</option>';
	          foreach($data['fieldinfos'][$field]['type']['picklistValues'] as $pv){
		          if($pv['value'] == $data['recordinfo'][$field]) $selected="selected";
		          else $selected="";
		          
		          $fieldform.="<option value='".$pv['value']."' $selected>".$pv['label']."</option>";
	          }
	          $fieldform.="</select>";
          }
          
          
          else if($data['fieldinfos'][$field]['type']['name']=="date"){ 
	          
          $fieldform='<div class="form-group">
            <div class="input-group date datetimepicker" >
                <input type="text" class="form-control" name="updatedinfos['.$field.']" value="'.$data['recordinfo'][$field].'" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar">
                      </span>
                </span>
            </div>
			</div>';
        
           }
          
          
          
          else $fieldform='<input type="'.$data['fieldinfos'][$field]['type']['name'].'" class="form-control" name="updatedinfos['.$field.']" value="'.$data['recordinfo'][$field].'">';
          
                echo '<div class="col-lg-6"><label for="'.$field.'">'.$data['fieldinfos'][$field]['label'].'</label>'.$fieldform.'</div>';
          if($tc==2){ 
          	echo "</div><br>";
          	$tc=1;
          }	
          else $tc++;
		  }
	                       
      ?>           
      <input type="hidden" name="update_data" value="true">       	
             <div class='col-lg-12 text-center'><br><hr><a onclick="window.history.back()" class="btn btn-default btn-lg"><?php echo Language::translate("Go Back"); ?></a>&nbsp;<input class="btn btn-success btn-lg" name="update_info" type="submit" value="<?php echo Language::translate("Update Informations"); ?>"><br><br> </div> </form>     
                   
        <?php   } else echo "<div class='col-lg-12'><h2>".Language::translate("You are not authorized to edit this record!")."</h2></div>"; ?>
                
      </div>     
                
                                    </div>
                <!-- /.row -->
           
           
           
           
           
            
            
		</div>
        <!-- /#page-wrapper -->
        
    <script type="text/javascript">
        $(function() {
            $('.datetimepicker').datetimepicker({
                viewMode: 'years',
               format: 'YYYY-MM-DD'
            });
        });
    </script>