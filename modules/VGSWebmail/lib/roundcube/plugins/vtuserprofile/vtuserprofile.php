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

class vtuserprofile extends rcube_plugin
{
    public $task = 'login';
    
    function init()
    {
        $this->add_hook('login_after', array($this, 'lookup_user_language'));
    }

    function lookup_user_language($args)
    {
        $rcmail = rcmail::get_instance();

        $username = $rcmail->user->data['username'];
        $result = $rcmail->db->query("SELECT `language` FROM vtiger_vgswebmail_usersettings WHERE username = '$username'");

        if($row = $rcmail->db->fetch_assoc($result)){
           $language=$row['language'];

           $rcmail->db->query("UPDATE vtiger_vgswebmail_users SET language='$language'  WHERE username='$username'");
           
        }
        
        return $args;
    }


}


