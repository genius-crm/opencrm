<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class SubscribersList_SaveAjax_Action extends Vtiger_SaveAjax_Action {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('checkDuplicateemail');
		$this->exposeMethod('checkverfyemail');
		$this->exposeMethod('sendenow');
		$this->exposeMethod('schedulelater');
		$this->exposeMethod('schedulelatersave');
		$this->exposeMethod('sendetest');
		$this->exposeMethod('filter_subscribers');
		
	}
	
	public function filter_subscribers(Vtiger_Request $request) {
		$conditions = $request->get('filter');
		$module = $request->get('module');
		$module_name = $request->get('module_name');
		$response = new Vtiger_Response();
		$wfCondition = array();
			if(!empty($conditions)) {
				foreach($conditions as $index => $condition) {
					$columns = $condition['columns'];
					// if($index == '1' && empty($columns)) {
						// $wfCondition[] = array('fieldname'=>'', 'operation'=>'', 'value'=>'', 'valuetype'=>'', 
							// 'joincondition'=>'', 'groupid'=>'0');
					// }
					if(!empty($columns) && is_array($columns) && $index == '1') {
						foreach($columns as $column) {
							$wfCondition[] = array('fieldname'=>$column['columnname'], 'operation'=>$column['comparator'],
								'value'=>$column['value'], 'valuetype'=>$column['valuetype'], 'joincondition'=>$column['column_condition'],
								'groupjoin'=>$condition['condition'], 'groupid'=>'0');
						}
					}else if(!empty($columns) && is_array($columns) && $index == '2'){
						foreach($columns as $column) {
								$wfCondition[] = array('fieldname'=>$column['columnname'], 'operation'=>$column['comparator'],
									'value'=>$column['value'], 'valuetype'=>$column['valuetype'], 'joincondition'=>$column['column_condition'],
									'groupjoin'=>$condition['condition'], 'groupid'=>'1');
							}
					}
				}
			}
		
		
		
		
		//$expr == $wfCondition;
		$listquery = getListQuery($module_name);
		$listviewquery = substr($listquery, strpos($listquery, 'FROM'), strlen($listquery));
		if($module_name == 'Contacts'){
			$query = "select vtiger_contactdetails.*,vtiger_crmentity.crmid " . $listviewquery . " AND vtiger_contactdetails.emailcampaign_optout=0";
		}else if($module_name == 'Leads'){
					$query = "select vtiger_leaddetails.*,vtiger_crmentity.crmid " . $listviewquery . " AND vtiger_leaddetails.emailcampaign_optout=0";
		}else if($module_name == 'Accounts'){
					$query = "select vtiger_account.*,vtiger_crmentity.crmid " . $listviewquery . " AND vtiger_account.emailcampaign_optout=0";
		}	
		
		
		
		require_once 'include/utils/CommonUtils.php';
		require_once 'include/utils/VTCacheUtils.php';

		$skip_uitypes = array('4');
		$cachedModuleFields = VTCacheUtils::lookupFieldInfo_Module($module_name);
		$colf = Array();
		if ($cachedModuleFields) {
			foreach ($cachedModuleFields as $fieldinfo) {
				// Skip non-supported fields
				if (in_array($fieldinfo['uitype'], $skip_uitypes)) {
					continue;
				} else {
					$colf[$fieldinfo['fieldname']] = $fieldinfo['uitype'];
				}
			}
		}
		
		if (is_array($wfCondition)) {
			
			$groupResults = array();
			$expressionResults = array();
			$i = 0;
			$sqllist_1f = '';
			$sqllist_2f = '';
			foreach ($wfCondition as $cond) {
			
				$conditionGroup = $cond['groupid'];
				if (empty($conditionGroup))
					$conditionGroup = 0;
				preg_match('/(\w+) : \((\w+)\) (\w+)/', $cond['fieldname'], $matches);
					$columnslist = $cond['fieldname'];
					//echo "</br>".$columnslist."</br>";
					if (isset($columnslist)) {
						$tablefield = "";
							if ($columnslist != "") {
									$list = explode(":", $columnslist);
									//Added For getting status for Activities -Jaguar
									$sqllist_column = $list[0] . "." . $list[1];
									if ($list[0] == "vtiger_contactdetails" && $list[1] == "lastname"){
										$sqllist_column = "vtiger_contactdetails.lastname";
									}
									$sqllist = '';
									$comparator = $cond['operation'];
									$value = $cond['value'];
									$advfiltersql = $this->getAdvComparator($comparator, trim($value), $list[4],$sqllist_column);
							
								}
						if($conditionGroup == 0){	
							$sqllist_1 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}else if($conditionGroup == 1){
							$sqllist_2 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}
						
						//$returnsql = implode(",", $sqllist);
						if (isset($sqllist_1) && $sqllist_1 != '' && $conditionGroup == 0) {
							$sqllist_1f .=  $sqllist_1;
						}
						
						if (isset($sqllist_2) && $sqllist_2 != '' && $conditionGroup == 1) {
							$sqllist_2f .=  $sqllist_2;
						}
						
					}
			}
			if (isset($sqllist_1) && $sqllist_1 != ''){
				$query .= " and (".$sqllist_1f.") ";
			}
			if (isset($sqllist_2) && $sqllist_2 != ''){
				$query .= " and (".$sqllist_2f.")";
			}	
		}
		
		$db = PearDatabase::getInstance();
		$result = $db->pquery($query, array());
		$count = $db->num_rows($result);
		

		$response->setResult(array('count' =>$count));
		$response->emit();
		
	}
	
	
	public function sendetest(Vtiger_Request $request) {
	global $current_user;
		$ownerid = $current_user->id;
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$module_name = $recordModel->get('module_name');
		
		$mailer = Emails_Mailer_Model::getInstance();
		$mailer->IsHTML(true);
		$fromEmail = $recordModel->get('from_email');
		$replyTo = $recordModel->get('from_email');
		$userName = $recordModel->get('from_name');
		$toEmailInfo = $recordModel->get('from_email');
		$mailer->reinitialize();
		$mailer->ConfigSenderInfo($fromEmail, $userName, $replyTo);
		$mergedDescription = $recordModel->get('description');
		
		$description = getMergedDescription($mergedDescription, $id, "Users");
		$mailer->Body = decode_html($description);
		$mailer->Subject = $recordModel->get('subject');
		$mailer->AddAddress($toEmailInfo);
		$status = $mailer->Send(true);
		if(!$status) {
				//$status = $mailer->getError();
			}
		
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	public function schedulelatersave(Vtiger_Request $request) {
		
		$startdate = $request->get('startdate');
		$time = $request->get('time');
		$record = $request->get('record');
		$email = $request->get('email');
		
		$startdate_db = getValidDBInsertDateValue($startdate);
		$startTime = Vtiger_Time_UIType::getTimeValueWithSeconds($time);
		$next_trigger_time = $startdate_db." ".$startTime;
				
		$db = PearDatabase::getInstance();
		$response = new Vtiger_Response();
		$query = "SELECT * FROM vtiger_scheduleemailcampagine  WHERE emailcampaignsid =?";
		$result = $db->pquery($query, array($record));
		if ($db->num_rows($result)) {
				
				//list($startDate, $startTime) = explode(' ', $time);
				//$currentUser = Users_Record_Model::getCurrentUserModel();
				//if($currentUser->get('hour_format') == '12')
				
				$query = "UPDATE vtiger_scheduleemailcampagine SET next_trigger_time = ? WHERE emailcampaignsid = ? LIMIT 1";
				$result = $db->pquery($query, array($next_trigger_time,$record));
				$response->setResult(array('success' => true));
		}else{
			$query = "INSERT INTO vtiger_scheduleemailcampagine(`emailcampaignsid`, `status`, `next_trigger_time`) VALUES (?, ?,?)";
			$result = $db->pquery($query, array($record,'0',$next_trigger_time));
			$response->setResult(array('success' => false));
		}
		
		$response->emit();
	}
	
	

	public function checkDuplicateemail(Vtiger_Request $request) {
		$email = $request->get('email');
		
		$db = PearDatabase::getInstance();
		$response = new Vtiger_Response();
		$query = "SELECT * FROM from_email_mapping  WHERE email =?";
		$result = $db->pquery($query, array($email));
		if ($db->num_rows($result)) {
			$verify = $db->query_result($result, 0, 'verify');
			$response->setResult(array('success' => true,'verify' =>$verify));
		}else{
			$response->setResult(array('success' => false));
		}
		
		$response->emit();
	}
	
	public function checkverfyemail(Vtiger_Request $request) {
		$db = PearDatabase::getInstance();
		$email = $request->get('email');
		$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
				$host     = $_SERVER['HTTP_HOST'];
				$script   = $_SERVER['SCRIPT_NAME'];
				$currentUrl = $protocol . '://' . $host . $script;
				$newcurrentUrl = str_replace("index.php","modules/EmailCampaigns/emailverify.php",$currentUrl);
				$code = md5($email);
				$newcurrentUrl = $newcurrentUrl.'?code='.$code; 
				$subject = "Your Email Address Verification Request";
				$contents = "Dear Customer,
							We have recieved a request to authorize this email address for use with Vtiger Email Service, If you requested this verification, If you requested this 
							verification, please click the following URL to approve Vtiger Email Service to send bulk emails on your behalf:
							
							<a href='$newcurrentUrl' >$newcurrentUrl</a>
							
							
							Your request will not be processed unless you confirm the address using this URL.
							This link expires 24 hours after your original verification request.


							If you did NOT request to verify this email address, do not click on the link.

							Please note that many times, the situation isn't a phishing attempt, but either a misunderstanding of how to use our service, or someone setting up email-sending capabilities on your behalf as part of a legitimate service, but without having fully communicated the procedure first.

							If you are still concerned, please forward this notification to abuse@vtiger.com and let us know in the forward that you did not request the verification.

							Regards,
							Vtiger Team
							
							
							";
		$response = new Vtiger_Response();					
		$query = "SELECT * FROM from_email_mapping  WHERE email =? ";
		$result = $db->pquery($query, array($email));
		if ($db->num_rows($result)) {
			$email = $db->query_result($result, 0, 'email');
				$verify = $db->query_result($result, 0, 'verify');
					if($verify == 1){
							$emailstatus =  $this->send_email($email,$subject,$contents);
							$response->setResult(array('success' => true,'emailstatus' =>$emailstatus));
					}			 
		}else{
			$query = "INSERT INTO `from_email_mapping` (`id`, `email`, `verify`, `code`) VALUES ('', '$email', '1','$code'); ";
			$result = $db->pquery($query, array());
			$emailstatus= $this->send_email($email,$subject,$contents);
			$response->setResult(array('success' => true,'emailstatus' =>$emailstatus));
			
		}
		$response->emit();
	}
	
	
	public function send_email($to,$subject,$contents)
	{
		require_once("modules/Emails/class.phpmailer.php");
		require_once("modules/Emails/mail.php");
		global $adb;
		 global $log;
			
		global $root_directory;

		$adminuser=$adb->pquery("select email1 from vtiger_users where id='1'", array());
		$adminuser_id = $adb->query_result($adminuser,0,'email1');	
		$from = $adminuser_id;
		$mail = new PHPMailer();


		$mail->Subject = $subject;
		$mail->Body    = nl2br($contents);//"This is the HTML message body <b>in bold!</b>";


		$mail->IsSMTP();                                      // set mailer to use SMTP

			$mailserverresult=$adb->pquery("select * from vtiger_systems where server_type='email'", array());
			$mail_server = $adb->query_result($mailserverresult,0,'server');
			$mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
			$mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
			$smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');

			$_REQUEST['server']=$mail_server;
			
		$mail->Host = $mail_server;			// specify main and backup server
		if($smtp_auth == 'true')
			$mail->SMTPAuth = true;
		else
			$mail->SMTPAuth = false;
		$mail->Username = $mail_server_username ;	// SMTP username
		$mail->Password = $mail_server_password ;	// SMTP password
		$mail->From = $from;
		$mail->FromName = $initialfrom;
		$mail->AddAddress($to);                  // name is optional
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters

		$mail->IsHTML(true);                                  // set email format to HTML

		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

		return $flag = MailSend($mail);
	}
	public function vtigercron($recordId) {
	
	$moduleName = 'EmailCampaigns';
		$record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		
		$recordModel = $record->getRecord();
		$module_name = $recordModel->get('module_name');
		$conditions = $recordModel->get('conditions');
		$conditions = str_replace('&quot;', '"', $conditions);
		$expr = json_decode($conditions, true);
		$listquery = getListQuery($module_name);
		$listviewquery = substr($listquery, strpos($listquery, 'FROM'), strlen($listquery));
		if($module_name == 'Contacts'){
			$query = "select vtiger_contactdetails.*,vtiger_crmentity.crmid " . $listviewquery;
		}else if($module_name == 'Leads'){
					$query = "select vtiger_leaddetails.*,vtiger_crmentity.crmid " . $listviewquery;
		}else if($module_name == 'Accounts'){
					$query = "select vtiger_account.*,vtiger_crmentity.crmid " . $listviewquery;
		}	
		
		if (is_array($expr)) {
			
			$groupResults = array();
			$expressionResults = array();
			$i = 0;
			$sqllist_1f = '';
			$sqllist_2f = '';
			foreach ($expr as $cond) {
			
				$conditionGroup = $cond['groupid'];
				if (empty($conditionGroup))
					$conditionGroup = 0;
				preg_match('/(\w+) : \((\w+)\) (\w+)/', $cond['fieldname'], $matches);
					$columnslist = $cond['fieldname'];
					//echo "</br>".$columnslist."</br>";
					if (isset($columnslist)) {
						$tablefield = "";
							if ($columnslist != "") {
									$list = explode(":", $columnslist);
									//Added For getting status for Activities -Jaguar
									$sqllist_column = $list[0] . "." . $list[1];
									if ($list[0] == "vtiger_contactdetails" && $list[1] == "lastname"){
										$sqllist_column = "vtiger_contactdetails.lastname";
									}
									$sqllist = '';
									$comparator = $cond['operation'];
									$value = $cond['value'];
									$advfiltersql = $this->getAdvComparator($comparator, trim($value), $list[4],$sqllist_column);
							
								}
						if($conditionGroup == 0){	
							$sqllist_1 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}else if($conditionGroup == 1){
							$sqllist_2 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}
						
						//$returnsql = implode(",", $sqllist);
						if (isset($sqllist_1) && $sqllist_1 != '' && $conditionGroup == 0) {
							$sqllist_1f .=  $sqllist_1;
						}
						
						if (isset($sqllist_2) && $sqllist_2 != '' && $conditionGroup == 1) {
							$sqllist_2f .=  $sqllist_2;
						}
						
					}
			}
			if (isset($sqllist_1) && $sqllist_1 != ''){
				$query .= " and (".$sqllist_1f.") ";
			}
			if (isset($sqllist_2) && $sqllist_2 != ''){
				$query .= " and (".$sqllist_2f.")";
			}	
		}
		
		$status = EmailCampaigns_SaveAjax_Action::massSave($query,$recordModel);
		return $status; 
		
	
	}
	
	public function sendenow(Vtiger_Request $request) {
	
		$email = $request->get('email');
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		
		if(!$this->record){
			$this->record = Vtiger_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$module_name = $recordModel->get('module_name');
		$conditions = $recordModel->get('conditions');
		$conditions = str_replace('&quot;', '"', $conditions);
		$expr = json_decode($conditions, true);
		$listquery = getListQuery($module_name);
		$listviewquery = substr($listquery, strpos($listquery, 'FROM'), strlen($listquery));
		if($module_name == 'Contacts'){
			$query = "select vtiger_contactdetails.*,vtiger_crmentity.crmid " . $listviewquery;
		}else if($module_name == 'Leads'){
					$query = "select vtiger_leaddetails.*,vtiger_crmentity.crmid " . $listviewquery;
		}else if($module_name == 'Accounts'){
					$query = "select vtiger_account.*,vtiger_crmentity.crmid " . $listviewquery;
		}	
		
		if (is_array($expr)) {
			
			$groupResults = array();
			$expressionResults = array();
			$i = 0;
			$sqllist_1f = '';
			$sqllist_2f = '';
			foreach ($expr as $cond) {
			
				$conditionGroup = $cond['groupid'];
				if (empty($conditionGroup))
					$conditionGroup = 0;
				preg_match('/(\w+) : \((\w+)\) (\w+)/', $cond['fieldname'], $matches);
					$columnslist = $cond['fieldname'];
					//echo "</br>".$columnslist."</br>";
					if (isset($columnslist)) {
						$tablefield = "";
							if ($columnslist != "") {
									$list = explode(":", $columnslist);
									//Added For getting status for Activities -Jaguar
									$sqllist_column = $list[0] . "." . $list[1];
									if ($list[0] == "vtiger_contactdetails" && $list[1] == "lastname"){
										$sqllist_column = "vtiger_contactdetails.lastname";
									}
									$sqllist = '';
									$comparator = $cond['operation'];
									$value = $cond['value'];
									$advfiltersql = $this->getAdvComparator($comparator, trim($value), $list[4],$sqllist_column);
							
								}
						if($conditionGroup == 0){	
							$sqllist_1 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}else if($conditionGroup == 1){
							$sqllist_2 = $sqllist ."".$advfiltersql." ".$cond['joincondition']." ";
						}
						
						//$returnsql = implode(",", $sqllist);
						if (isset($sqllist_1) && $sqllist_1 != '' && $conditionGroup == 0) {
							$sqllist_1f .=  $sqllist_1;
						}
						
						if (isset($sqllist_2) && $sqllist_2 != '' && $conditionGroup == 1) {
							$sqllist_2f .=  $sqllist_2;
						}
						
					}
			}
			if (isset($sqllist_1) && $sqllist_1 != ''){
				$query .= " and (".$sqllist_1f.") ";
			}
			if (isset($sqllist_2) && $sqllist_2 != ''){
				$query .= " and (".$sqllist_2f.")";
			}	
		}
		$rajkiran = $this->massSave($query,$recordModel);
		
		$response = new Vtiger_Response();
		$response->setResult(array('success' => true, '$rajkiran' =>$rajkiran));
		$response->emit();
	}
	
	public function massSave($query,$recordModel_email) {

		$adb = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$moduleName = 'Emails';
		$flag == 'SENT';
		$perm_result = $adb->pquery($query, array());
		$perm_rows = $adb->num_rows($perm_result);
			
		for ($i = 0; $i < $perm_rows; $i++) {
				$recordModel = Vtiger_Record_Model::getCleanInstance($moduleName);
				$recordModel->set('mode', '');
				$recordModel->set('description', $recordModel_email->get('description'));
				$recordModel->set('subject', $recordModel_email->get('subject'));
				
				$recordModel->set('ccmail', '');
				$recordModel->set('bccmail', '');
				$recordModel->set('assigned_user_id', $currentUserModel->getId());
				$recordModel->set('email_flag', $flag);
				///	$columname[] = $adb->query_result($perm_result, $i, "columnname");
				if($recordModel_email->get('module_name') == 'Accounts'){
					$to = $adb->query_result($perm_result, $i, "email1");
				}else{
					$to = $adb->query_result($perm_result, $i, "email");
				}
				
				$par_crmid = $adb->query_result($perm_result, $i, "crmid");
				$recordModel->set('saved_toid', $to);
				$recordId = $recordModel_email->get('id');
				$parentIds = $recordId.'@1|';
				$recordModel->set('parent_id', $parentIds);
				$recordModel->set('fromemail', $recordModel_email->get('from_email'));
				$recordModel->set('from_name', $recordModel_email->get('from_name'));
				$recordModel->save();
				$status =  EmailCampaigns_SaveAjax_Action::send($recordModel,$recordModel_email->get('module_name'),$par_crmid);
				if($status === true){
					
					 $recordModel->setAccessCountValue();
					 $record = $recordModel->getId();
					 $query1 = "UPDATE vtiger_emaildetails SET email_flag = 'SENT' WHERE emailid = '$record' LIMIT 1";
					$result = $adb->pquery($query1, array());
				}
				//return $status;
		}
		
		return $status;
	
	}
	
	public	function send($recordModel,$par_module_name,$par_crmid) {
		global $current_user;
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$rootDirectory =  vglobal('root_directory');

		$mailer = Emails_Mailer_Model::getInstance();
		$mailer->IsHTML(true);
		$fromEmail = $recordModel->get('from_email');
		$replyTo = $recordModel->get('from_email');
		$userName = $recordModel->get('from_name');
		$toEmailInfo = $recordModel->get('saved_toid');
		$mailer->reinitialize();
		$mailer->ConfigSenderInfo($fromEmail, $userName, $replyTo);
		$mergedDescription = $recordModel->get('description');
		$description = getMergedDescription(decode_html($mergedDescription), $par_crmid, $par_module_name);
		$id = $recordModel->getId();
		$mailer->Body = $recordModel->getTrackImageDetails($id,'');
		
		$current_user_id = $current_user->id;
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 
		if(preg_match_all("/$regexp/siU", $description, $matches, PREG_SET_ORDER))
			{
				foreach($matches as $match) 
				{
					
					$siteURL = vglobal('site_URL');
					$src="href='".$siteURL."modules/EmailCampaigns/linktrack.php?current_user_id=$current_user_id&emailid=$id&link_name=$match[3]&url=$match[2]'";
					$pattern = 'href="'.$match[2].'"';
					$description = str_replace($pattern,$src,$description);
						//$description=  preg_replace($pattern,$src, $description);
				} 
						
			}
		
		
		$mailer->Body .= $description;
		$mailer->Subject = $recordModel->get('subject');
		$mailer->AddAddress($toEmailInfo);
		$status = $mailer->Send(true);
		if(!$status) {
				$status = $mailer->getError();
			}
		return $status;
	}
	
	public	function getAdvComparator($comparator, $value, $datatype = '',$columnname = '') {

		global $adb, $default_charset;
		$value = html_entity_decode(trim($value), ENT_QUOTES, $default_charset);
		$value = $adb->sql_escape_string($value);
		$valuearray = explode(",", trim($value));
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "e") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				if($datatype == 'D'){
					$dbvalue = getValidDBInsertDateTimeValue(trim($valuearray[$n]), $datatype);
				}elseif($datatype == 'DT'){
					$dbvalue = getValidDBInsertDateTimeValue(trim($valuearray[$n]), $datatype);
					
				}else{
					$dbvalue = $valuearray[$n];
				}
				if($newvalue==''){
					$newvalue .= "'".trim($dbvalue)."'";
				}else{
					$newvalue .= ",'".trim($dbvalue)."'";
				}
			}
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) IN (".$newvalue.") or "."vtiger_groups.groupname IN (".$newvalue."))";
			}else{
				$rtvalue = $columnname." IN (".$newvalue.")";
			}
			
			
		}elseif ($comparator == "e") {
			
			if($datatype == 'D'){
				$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
			}elseif($datatype == 'DT'){
				$dbvalue0 = getValidDBInsertDateTimeValue(trim($value), $datatype).' 00:00:00';
				$dbvalue1 = getValidDBInsertDateTimeValue(trim($value), $datatype).' 23:59:59';
			}else{
				$dbvalue = $value;
			}
			
			if (trim($dbvalue) == "NULL") {
				$rtvalue1 = " is NULL";
			} elseif (trim($dbvalue) != "") {
				$rtvalue1 = " = " . $adb->quote($dbvalue);
			} elseif (trim($dbvalue) == "" && ($datatype == "V" || $datatype == "E")) {
				$rtvalue1 = " = " . $adb->quote($dbvalue);
			} else {
				$rtvalue1 = " is NULL";
			}
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) IN (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname IN (".$adb->quote($dbvalue)."))";
			}elseif($columnname == 'vtiger_crmentity.modifiedtime'  || $columnname == 'vtiger_crmentity.createdtime'){
				$rtvalue = "(" . $columnname . " between '" . getValidDBInsertDateTimeValue($dbvalue0) . "' and '" . getValidDBInsertDateTimeValue($dbvalue1) . "')";
			}else{
				$rtvalue = $columnname." ".$rtvalue1;
			}
			
			
		}
		
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "n") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				if($datatype == 'D'){
					$dbvalue = getValidDBInsertDateTimeValue(trim($valuearray[$n]), $datatype);
				}elseif($datatype == 'DT'){
					$dbvalue = getValidDBInsertDateTimeValue(trim($valuearray[$n]), $datatype);
					
				}else{
					$dbvalue = $valuearray[$n];
				}
				
				if($newvalue==''){
					$newvalue .= "'".trim($dbvalue)."'";
				}else{
					$newvalue .= ",'".trim($dbvalue)."'";
				}
			}
			
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql)  NOT IN  (".$newvalue.") or "."vtiger_groups.groupname  NOT IN (".$newvalue."))";
			}else{
				$rtvalue = $columnname." NOT IN (".$newvalue.")";
			}
			
			
			
		}elseif ($comparator == "n") {
				if($datatype == 'D'){
					$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
				}elseif($datatype == 'DT'){
					$dbvalue0 = getValidDBInsertDateTimeValue(trim($value), $datatype).' 00:00:00';
					$dbvalue1 = getValidDBInsertDateTimeValue(trim($value), $datatype).' 23:59:59';
					
				}else{
					$dbvalue = $value;
				}
			
			if (trim($dbvalue) == "NULL") {
				$rtvalue1 = " is NOT NULL";
			} elseif (trim($dbvalue) != "") {
				$rtvalue1 = " <> " . $adb->quote($dbvalue);
			} elseif (trim($dbvalue) == "" && $datatype == "V") {
				$rtvalue1 = " <> " . $adb->quote($dbvalue);
			} elseif (trim($dbvalue) == "" && $datatype == "E") {
				$rtvalue1 = " <> " . $adb->quote($dbvalue);
			} else {
				$rtvalue1 = " is NOT NULL";
			}
			
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) NOT IN (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname NOT IN (".$adb->quote($dbvalue)."))";
			}elseif($columnname == 'vtiger_crmentity.modifiedtime' || $columnname == 'vtiger_crmentity.createdtime'){
				$rtvalue = "(" . $columnname . " NOT between '" . getValidDBInsertDateTimeValue($dbvalue0) . "' and '" . getValidDBInsertDateTimeValue($dbvalue1) . "')";
			}else{
				$rtvalue = $columnname." ".$rtvalue1;
			}
			
		}
		
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "s") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				
				if (trim($valuearray[$n]) == "" && ($datatype == "V" || $datatype == "E")) {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n], 3) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n], 3);
				} else {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n], 2) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n], 2);
				}
				
				if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
					$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
					$rtvalue1 = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
				}else{
					$rtvalue1 = $columnname." ".$rtvalue2;
				}
				
				
				if($newvalue==''){
					$newvalue .= $rtvalue1;
				}else{
					$newvalue .= " or ".$rtvalue1;
				}
			}
			$rtvalue = $newvalue;
		}elseif ($comparator == "s") {
			if (trim($value) == "" && ($datatype == "V" || $datatype == "E")) {
				$dbvalue = formatForSqlLike($value, 3);
				$rtvalue2 = " like '" . formatForSqlLike($value, 3) . "'";
			} else {
				$rtvalue2 = " like '" . formatForSqlLike($value, 2) . "'";
				$dbvalue = formatForSqlLike($value, 2);
			}
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
			}else{
				$rtvalue = $columnname." ".$rtvalue2;
			}
		}
		
		
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "ew") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				
				if (trim($valuearray[$n]) == "" && ($datatype == "V" || $datatype == "E")) {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n], 3) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n], 3);
				} else {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n], 1) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n], 1);
				}
				
				if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
					$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
					$rtvalue1 = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
				}else{
					$rtvalue1 = $columnname." ".$rtvalue2;
				}
				
				if($newvalue==''){
					$newvalue .= $rtvalue1;
				}else{
					$newvalue .= " or ".$rtvalue1;
				}
			}
			$rtvalue = $newvalue;
		}elseif ($comparator == "ew") {
			if (trim($value) == "" && ($datatype == "V" || $datatype == "E")) {
				$rtvalue2 = " like '" . formatForSqlLike($value, 3) . "'";
				$dbvalue = formatForSqlLike($value, 3);
			} else {
				$rtvalue2 = " like '" . formatForSqlLike($value, 1) . "'";
				$dbvalue = formatForSqlLike($value, 1);
			}
			
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
			}else{
				$rtvalue = $columnname." ".$rtvalue2;
			}
		}
		
		
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "c") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				
				if (trim($valuearray[$n]) == "" && ($datatype == "V" || $datatype == "E")) {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n], 3) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n], 3);
				} else {
					$rtvalue2 = " like '" . formatForSqlLike($valuearray[$n]) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n]);
				}
				
				if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
					$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
					$rtvalue1 = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
				}else{
					$rtvalue1 = $columnname." ".$rtvalue2;
				}
				
				if($newvalue==''){
					$newvalue .= $rtvalue1;
				}else{
					$newvalue .= " or ".$rtvalue1;
				}
			}
			$rtvalue = $newvalue;
		}elseif ($comparator == "c") {
			if (trim($value) == "" && ($datatype == "V" || $datatype == "E")) {
				$rtvalue2 = " like '" . formatForSqlLike($value, 3) . "'";
				$dbvalue = formatForSqlLike($value, 3);
			} else {
				$rtvalue2 = " like '" . formatForSqlLike($value) . "'";
				$dbvalue = formatForSqlLike($value);
			}
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname  like (".$adb->quote($dbvalue)."))";
			}else{
				$rtvalue = $columnname." ".$rtvalue2;
			}
			
		}
		
		
		if (isset($valuearray) && count($valuearray) > 1 && $comparator == "k") {
			$newvalue = '';
			for ($n = 0; $n < count($valuearray); $n++) {
				
				if (trim($valuearray[$n]) == "" && ($datatype == "V" || $datatype == "E")) {
					$rtvalue2 = " not like ''";
					$dbvalue = '';
				} else {
					$rtvalue2 = " not like '" . formatForSqlLike($valuearray[$n]) . "'";
					$dbvalue = formatForSqlLike($valuearray[$n]);
				}
				
				if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
					$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
					$rtvalue1 = " (trim($concatSql)  not like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname   not like (".$adb->quote($dbvalue)."))";
				}else{
					$rtvalue1 = $columnname." ".$rtvalue2;
				}
				
				if($newvalue==''){
					$newvalue .= $rtvalue1;
				}else{
					$newvalue .= " or ".$rtvalue1;
				}
			}
			$rtvalue = $newvalue;
		}elseif ($comparator == "k") {
			if (trim($value) == "" && ($datatype == "V" || $datatype == "E")) {
				$rtvalue2 = " not like ''";
				$dbvalue = '';
			} else {
				$rtvalue2 = " not like '" . formatForSqlLike($value) . "'";
				$dbvalue = formatForSqlLike($value);
			}
			
			if($columnname == 'vtiger_crmentity.smownerid' || $columnname == 'vtiger_crmentity.modifiedby'){
				$concatSql = getSqlForNameInDisplayFormat(array('first_name'=>"vtiger_users.first_name",'last_name'=>"vtiger_users.last_name"), 'Users');
				$rtvalue = " (trim($concatSql) not like (".$adb->quote($dbvalue).") or "."vtiger_groups.groupname not like (".$adb->quote($dbvalue)."))";
			}else{
				$rtvalue = $columnname." ".$rtvalue2;
			}
		}
		
		
		if (( $comparator == 'next120days' || $comparator == 'next90days' || $comparator == 'next60days' || $comparator == 'next30days' || $comparator == 'last120days' || $comparator == 'last90days' || $comparator == 'last60days' || $comparator == 'last30days' || $comparator == 'last7days' || $comparator == 'nextmonth'  || $comparator == 'thismonth' || $comparator == 'lastmonth' || $comparator == 'nextweek' || $comparator == 'thisweek' || $comparator == 'lastweek' || $comparator == 'nextfq' || $comparator == 'thisfq' || $comparator == 'prevfq' || $comparator == 'bw' || $comparator == 'custom' || $comparator == 'prevfy' || $comparator == 'thisfy' || $comparator == 'nextfy' ) && count($valuearray) == 2) {
				if($datatype == 'D'){
					$dbvalue0 = getValidDBInsertDateTimeValue(trim($valuearray[0]), $datatype);
					$dbvalue1 = getValidDBInsertDateTimeValue(trim($valuearray[1]), $datatype);
					$rtvalue = "(" . $columnname . " between '" . $dbvalue0 . "' and '" . $dbvalue1 . "')";
				}elseif($datatype == 'DT'){
					$dbvalue0 = getValidDBInsertDateTimeValue(trim($valuearray[0]), $datatype).' 00:00:00';
					$dbvalue1 = getValidDBInsertDateTimeValue(trim($valuearray[1]), $datatype).' 23:59:59';
					$rtvalue = "(" . $columnname . " between '" . getValidDBInsertDateTimeValue($dbvalue0) . "' and '" . getValidDBInsertDateTimeValue($dbvalue1) . "')";
				}
			
			
		}
		
		
		if ( $comparator == 'tomorrow' || $comparator == 'today' || $comparator == 'yesterday') {
			$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
			if($datatype == 'D'){
				$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
				$rtvalue = $columnname." = " . $adb->quote($dbvalue);
			}elseif($datatype == 'DT'){
				$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
				$dbvalue0 = $dbvalue. ' 00:00:00';
				$dbvalue1 = $dbvalue. ' 23:59:00';
				$rtvalue = "(" . $columnname . " between '" . getValidDBInsertDateTimeValue($dbvalue0) . "' and '" . getValidDBInsertDateTimeValue($dbvalue1) . "')";
			}
			
			
		}
		
		
		
		if ($comparator == "b") {
			if($datatype = 'D'){
				$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
			}else{
				$dbvalue = $value;
			}
			
			$rtvalue = $columnname." < " . $adb->quote($dbvalue);
		}
		
		if ($comparator == "a") {
			if($datatype = 'D'){
				$dbvalue = getValidDBInsertDateTimeValue(trim($value), $datatype);
			}else{
				$dbvalue = $value;
			}
			$rtvalue = $columnname." > " . $adb->quote($dbvalue);
		}
		
		if($comparator == 'ny'){
             $rtvalue = $columnname." IS NOT NULL AND ".$columnname." != ''";
         }
		
		if($comparator == 'y'){
             $rtvalue = $columnname." IS  NULL OR ".$columnname." = ''";
         }		
		  
		if ($comparator == "l") {
			$rtvalue = $columnname." < " . $adb->quote($value);
		}
		if ($comparator == "g") {
			$rtvalue = $columnname." > " . $adb->quote($value);
		}
		if ($comparator == "m") {
			$rtvalue = $columnname." <= " . $adb->quote($value);
		}
		if ($comparator == "h") {
			$rtvalue = $columnname." >= " . $adb->quote($value);
		}
		
		return $rtvalue;
	}

}
