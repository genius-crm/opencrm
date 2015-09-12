<?php

/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */
Class vtrc extends rcube_plugin {

    public $vtclient = null;
    public $_leadresult = null;
    public $_accountresult = null;
    public $_contactresult = null;

    public function init() {

        $rcmail = rcmail::get_instance();

        //add vtiger label
        // Hide VT plugin settings
//       $this->register_action('plugin.vtsettings', array($this, 'settings_init'));
//        $this->register_action('plugin.vtsettings-save', array($this, 'settings_save'));
        $this->add_hook('message_load', array($this, 'message_load'));
        $this->add_hook('message_sent', array($this, 'message_sent'));
        $this->add_hook('message_headers_output', array($this, 'add_select_create'));
        $this->register_action('plugin.vtrc-createrecord', array($this, 'vtrc_createrecord'));
        $this->include_script('vtrc.js');

        if ($rcmail->action == 'compose') {
            //$this->vtiger_emailtemplates_init(); remove email templates feature
        }
    }

    function message_load($args) {
        global $current_user,$settings;

        $rcmail = rcmail::get_instance();
        $user_email = $rcmail->get_user_email();
        $user_name = $rcmail->get_user_name();
        $rcmessage = $args['object'];
        $from_arr = $rcmessage->sender;
        $from = $from_arr['mailto'];
        $to_arr = rcube_mime::decode_address_list($rcmessage->headers->get('to'));
        $cc_arr = rcube_mime::decode_address_list($rcmessage->headers->get('cc'));
        $date = $rcmessage->headers->get('date');
        $subject = $rcmessage->subject;


        if ($rcmessage->first_html_part() == '') {
            $body = $rcmessage->first_text_part();
        } else {
            $body = $rcmessage->first_html_part();
        }

        $is_sent = $this->is_sent($from, $user_email);

        $file = $this->home . "/vtwsclib/Vtiger/WSClient.php";
        if (!file_exists($file)) {
            raise_error(array(
                'code' => 600,
                'type' => 'php',
                'file' => __FILE__, 'line' => __LINE__,
                'message' => "Vtiger-RC plugin: Unable to load client lib ($file)"
                    ), true, false);
            return $this->gettext('internalerror');
        }

        $include_path = get_include_path();
        set_include_path($this->home);
        include_once $file;
        set_include_path($include_path);

        if (!$this->is_attached($rcmail, $record['id'], $rcmessage->uid) && $settings = $this->getSettings($rcmail)) {

            if ($this->getVTConnection($settings, $rcmail)) {
                $lookup_arr = array($from_arr);
                foreach ($lookup_arr as $key => $email_arr) {
                    $con_result = $this->lookup_contact($email_arr['mailto']);
                    if (count($con_result) > 0) {
                        foreach ($con_result as $i => $record) {
                            $this->attach_email($record, $from, $user_name . "<" . $user_email . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $rcmessage->uid);
                        }
                    }

                    $org_result = $this->lookup_organization($email_arr['mailto']);
                    if (count($org_result) > 0) {
                        foreach ($org_result as $i => $record) {
                            $this->attach_email($record, $from, $user_name . "<" . $user_email . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $rcmessage->uid);
                        }
                    }

                    $lead_result = $this->lookup_lead($email_arr['mailto']);
                    if (count($lead_result) > 0) {
                        foreach ($lead_result as $i => $record) {
                            $this->attach_email($record, $from, $user_name . "<" . $user_email . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $rcmessage->uid);
                        }
                    }
                }
            }
        }
    }

    function message_sent($args) {
        $rcmail = rcmail::get_instance();
        $headers = $args['headers'];
        $date = $headers['Date'];
        $messageid = $headers['Message-ID'];
        $from = $headers['From'];
        $to = explode(',', $headers['To']);
        $subject = $headers['Subject'];
        $body = $args['body'];
        

        $file = $this->home . "/vtwsclib/Vtiger/WSClient.php";
        if (!file_exists($file)) {
            raise_error(array(
                'code' => 600,
                'type' => 'php',
                'file' => __FILE__, 'line' => __LINE__,
                'message' => "Vtiger-RC plugin: Unable to load client lib ($file)"
                    ), true, false);
            return $this->gettext('internalerror');
        }

        $include_path = get_include_path();
        set_include_path($this->home);
        include_once $file;
        set_include_path($include_path);


        if (!$this->is_attached($rcmail, $record['id'], $messageid) && $settings = $this->getSettings($rcmail)) {
            if ($this->getVTConnection($settings)) {
                foreach ($to as $i => $to_email) {

                    $to_email = $this->GetBetween('<', '>', $to_email);
                    $from = $this->GetBetween('<', '>', $from);
                    $con_result = $this->lookup_contact($to_email);
                    if (count($con_result) > 0) {
                        foreach ($con_result as $i => $record) {
                            $this->attach_email($record, $from, $record['firstname'] . " " . $record['lastname'] . "<" . $to_email . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $messageid);
                        }
                    }

                    $org_result = $this->lookup_organization($to_email);
                    if (count($org_result) > 0) {
                        foreach ($org_result as $i => $record) {
                            $this->attach_email($record, $from, $record['accountname'] . "<" . $to . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $messageid);
                        }
                    }

                    $lead_result = $this->lookup_lead($to_email);
                    if (count($lead_result) > 0) {
                        foreach ($lead_result as $i => $record) {
                            $this->attach_email($record, $from, $record['firstname'] . " " . $record['lastname'] . "<" . $to . ">", $subject, $body, $date, $rcmessage);
                            $rcmail->db->query("insert into vtiger_vgswebmail_emailtrack value(?,?)", $record['id'], $messageid);
                        }
                    }
                }
            }
        }
    }

    function add_select_create($args) {
        global $settings;


        $from = $this->GetBetween('<', '>', $args['output']['from']['raw']);

        $this->getVTConnection($settings);

        $lead_result = $this->lookup_lead($from);
        $org_result = $this->lookup_organization($from);
        $con_result = $this->lookup_contact($from);

        if (count($lead_result) > 0) {
            $module = 'Leads';
            $id = explode('x', $lead_result[0]['id']);
            $record = $id[1];
        } elseif (count($org_result) > 0) {
            $module = 'Accounts';
            $id = explode('x', $org_result[0]['id']);
            $record = $id[1];
        } elseif (count($con_result) > 0) {
            $module = 'Contacts';
            $id = explode('x', $con_result[0]['id']);
            $record = $id[1];
        } else {
            $record = false;
        }

        if ($record) {

            $select = '<span style="font-weight:300"> | <a href="' . $settings['vtiger_url'] . '/index.php?module=' . $module . '&view=Detail&record=' . $record . '" target="_blank">View vTiger Record</a> | Actions:  </span>';

            $select .= '<select onchange="parent.addRecordVT()" name="add_vt" style="margin-left: 30px; width: 100px;" id="add_vt">'
                    . '<option value=""></option>'
                    . '<option value="event">Add Event</option>'
                    . '<option value="todo">Add Todo</option>'
                    . '<option value="ticket">Add Ticket</option>'
                    . '</select>'
                    . '<input type="hidden" id="from_email" value="' . $this->GetBetween('<', '>', $args['headers']->from) . '"/>'
                    . '<input type="hidden" id="from_name" value="' . $this->GetBetween('"', '"', $args['headers']->from) . '" />'
                    . '<input type="hidden" id="to_email" value="' . $args['headers']->to . '"/>';




            $args['output']['from']['value'] = $args['output']['from']['value'] . $select;
        } else {
            $uid = $args['headers']->uid;

            $select = '<span style="font-weight:300"> | No Record Found | Actions:  </span>';


            $select .= '<select onchange="parent.addRecordVT()" name="add_vt" style="margin-left: 30px; width: 100px;" id="add_vt">'
                    . '<option value=""></option><option value="contact">Add Contact</option>'
                    . '<option value="lead">Add Lead</option>'
                    . '<option value="account">Add Account</option>'
                    . '<option value="event">Add Event</option>'
                    . '<option value="todo">Add Todo</option>'
                    . '<option value="ticket">Add Ticket</option>'
                    . '</select>'
                    . '<input type="hidden" id="from_email" value="' . $this->GetBetween('<', '>', $args['headers']->from) . '"/>'
                    . '<input type="hidden" id="from_name" value="' . $this->GetBetween('"', '"', $args['headers']->from) . '" />'
                    . '<input type="hidden" id="to_email" value="' . $this->GetBetween('<', '>', $args['headers']->to) . '"/>';


            $args['output']['from']['value'] = $args['output']['from']['value'] . $select;
        }


        return $args;
    }

    function getSettings($rcmail) {
        global $settings;

        $username = $rcmail->get_user_name();

        if (isset($_SESSION[$username]['settings'])) {
            return $_SESSION[$username]['settings'];
        } else {
            $result = $rcmail->db->query("Select id, user_name, accesskey, vtiger_url, autosaveattachments 
                                        FROM vtiger_users INNER JOIN vtiger_vgswebmail_usersettings ON vtiger_users.id=vtiger_vgswebmail_usersettings.userid 
                                        WHERE vtiger_vgswebmail_usersettings.username = '$username'");

            if ($row = $rcmail->db->fetch_assoc($result)) {
                $settings = $row;
                $_SESSION[$username]['settings'] = $settings;
                return $settings;
            }
        }

        return false;
    }

    function getVTConnection($settings) {
        $rcmail = rcmail::get_instance();
        $username = $rcmail->get_user_name();

        if (!isset($_SESSION[$username]['vtconnection']) && $_SESSION[$username]['vtconnection_valid'] > time()) {
            $this->vtclient = new Vtiger_WSClient($settings['vtiger_url']);
            $this->vtclient->_serviceuser = $_SESSION[$username]['_serviceuser'];
            $this->vtclient->_servicekey = $_SESSION[$username]['_servicekey'];
            $this->vtclient->_sessionid = $_SESSION[$username]['_sessionid'];
            $this->vtclient->_userid = $_SESSION[$username]['_userid'];
            return true;
        }

        try {
            $this->vtclient = new Vtiger_WSClient($settings['vtiger_url']);
            $this->vtclient->doLogin($settings['user_name'], $settings['accesskey']);
            $_SESSION[$username]['vtconnection_valid'] = $this->vtclient->_expiretime; //save 30 seconds just in case.
            $_SESSION[$username]['_serviceuser'] = $this->vtclient->_serviceuser;
            $_SESSION[$username]['_servicekey'] = $this->vtclient->_servicekey;
            $_SESSION[$username]['_sessionid'] = $this->vtclient->_sessionid;
            $_SESSION[$username]['_userid'] = $this->vtclient->_userid;

            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function is_sent($from, $user_email) {
        ($user_email == $from) ? $sent = true : $sent = false;
        return $sent;
    }

    function is_attached($rcmail, $vtid, $rcid) {
        $check_result = $rcmail->db->query("select 1 from vtiger_vgswebmail_emailtrack where rcid=?", $rcid);
        if ($rcmail->db->num_rows($check_result) > 0) {
           return true;
        }

        return false;
    }

    function lookup_contact($email) {

        if (!isset($this->_contactresult)) {
            $wsquery = "select lastname,firstname,id from Contacts where email='" . $email . "'";
            $result = $this->vtclient->doQuery($wsquery);
            $this->_contactresult = $result;
            return $result;
        } else {
            return $this->_contactresult;
        }
    }

    function lookup_organization($email) {

        if (!isset($this->_accountresult)) {
            $wsquery = "select accountname,id from Accounts where email1='" . $email . "'";
            $result = $this->vtclient->doQuery($wsquery);
            $this->_accountresult = $result;
            return $result;
        } else {
            return $this->_accountresult;
        }
    }

    function lookup_lead($email) {

        if (!isset($this->_leadresult)) {
            $wsquery = "select lastname,firstname,id from Leads where email='" . $email . "'";
            $result = $this->vtclient->doQuery($wsquery);
            $this->_leadresult = $result;
            return $result;
        } else {
            return $this->_leadresult;
        }

        $wsquery = "select lastname,firstname,id from Leads where email='" . $email . "'";
        $result = $this->vtclient->doQuery($wsquery);
        return $result;
    }

    function attach_email($record, $from, $to, $subject, $body, $date, $mailobject) {
        global $settings;

        $date = strtotime($date);
        $date1 = date('d-m-Y', $date);
        $hours = date('G:i:s', $date);
        $data = Array
            (
            'date_start' => "$date1",
            'time_start' => "$hours",
            'activitytype' => 'Emails',
            'subject' => "$subject",
            'description' => $body,
            'from_email' => "$from",
            'parent_id' => $record['id'],
            'email_flag' => 'SENT',
            'saved_toid' => "$to"
        );

        //write_log('vtrc', print_r($data, true));
        $vtemail = $this->vtclient->doCreate('Emails', $data);

        if ($settings['autosaveattachments'] == 1) {
            $attachments = $mailobject->attachments;

            foreach ($attachments as $attachment) {
                $this->saveAttachment($mailobject, $attachment, $vtemail);
            }
        }
        return true;
    }

    function saveAttachment($mailobject, $attachment, $vtemail) {
        $rcmail = rcmail::get_instance();

        $temp_dir = $rcmail->config->get('temp_dir');
        $pid = $attachment->mime_id;
        $part = $mailobject->mime_parts[$pid];
        $filename = $part->filename;
        if ($filename === null || $filename === '') {
            $ext = (array) rcube_mime::get_mime_extensions($part->mimetype);
            $ext = array_shift($ext);
            $filename = $rcmail->gettext('messagepart') . ' ' . $pid;
            if ($ext) {
                $filename .= '.' . $ext;
            }
        }
        $disp_name = strtr($filename, array(':' => '', '/' => '-'));
        $newFile = fopen($temp_dir . '/' . $disp_name, 'w');
        $mailobject->get_part_content($part->mime_id, $newFile);
        $vtFilePath = $this->getStorageFileDirectory();

        fclose($newFile);

        //Insert en crmentity
        $userdId = explode('x', $this->vtclient->_userid, $this->vtclient->_userid);
        $emailId = explode('x', $vtemail['id']);
        $emailId = $emailId[1];

        $sql = "SELECT id FROM vtiger_crmentity_seq;";
        $result = $rcmail->db->query($sql);
        $row = $rcmail->db->fetch_assoc($result);
        $attachmentId = $row['id'] + 1;

        $filenameUpdated = $attachmentId . "_" . $disp_name;


        $rcmail->db->query('UPDATE vtiger_crmentity_seq SET id=?', $attachmentId);


        $sql1 = "insert into vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) values(?, ?, ?, ?, ?, ?, ?)";
        $rcmail->db->query($sql1, $attachmentId, $userdId[1], $userdId[1], 'Emails Attachment', '', date('Y-m-d G:i:s'), date('Y-m-d G:i:s'));

        //insert vtiger_seattachmentsrel

        $sql1 = "insert into vtiger_seattachmentsrel (crmid,attachmentsid) values(?, ?)";
        $rcmail->db->query($sql1, $emailId, $attachmentId);

        //insert vtiger_attachments

        $sql1 = "insert into vtiger_attachments (attachmentsid,name,description,type,path) values(?, ?,?,?,?)";
        $rcmail->db->query($sql1, $attachmentId, $disp_name, '', $part->mimetype, $vtFilePath);

        rename($temp_dir . '/' . $disp_name, $vtFilePath . $filenameUpdated);
    }

    function getStorageFileDirectory() {
        global $root_directory;
        include '../../../../config.inc.php';

        $filepath = $root_directory . 'storage/';

        $year = date('Y');
        $month = date('F');
        $day = date('j');
        $week = '';

        if (!is_dir($filepath . $year)) {
            //create new folder
            mkdir($filepath . $year);
        }

        if (!is_dir($filepath . $year . "/" . $month)) {
            //create new folder
            mkdir($filepath . "$year/$month");
        }

        if ($day > 0 && $day <= 7)
            $week = 'week1';
        elseif ($day > 7 && $day <= 14)
            $week = 'week2';
        elseif ($day > 14 && $day <= 21)
            $week = 'week3';
        elseif ($day > 21 && $day <= 28)
            $week = 'week4';
        else
            $week = 'week5';

        if (!is_dir($filepath . $year . "/" . $month . "/" . $week)) {
            //create new folder
            mkdir($filepath . "$year/$month/$week");
        }

        $filepath = $filepath . $year . "/" . $month . "/" . $week . "/";

        return $filepath;
    }

    function GetBetween($var1 = "", $var2 = "", $pool) {
        $temp1 = strpos($pool, $var1) + strlen($var1);
        $result = substr($pool, $temp1, strlen($pool));
        $dd = strpos($result, $var2);
        if ($dd == 0) {
            $dd = strlen($result);
        }
        return trim(substr($result, 0, $dd));
    }

    function vtiger_emailtemplates_init() {

        $rcmail = rcmail::get_instance();

        // add the addressbook button
        $rcmail->output->include_script('../../../../../../layouts/vlayout/modules/VGSWebmail/resources/VGSWebmail.js');
    }

}

?>
