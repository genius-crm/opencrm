<?php
/**
 * Multiple SMTP server
 *
 * Roundcube plugin to set multiple SMTP server.
 *
 * @version 0.2
 * @author Stefan Koch
 * @url https://github.com/unstko/Roundcube-plugins
 * @licence MIT License
 *
 * Copyright (c) 2011 Stefan Koch
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class multiple_smtp_server extends rcube_plugin
{
    /**
     * @var string Task to run the plugin in.
     */
    public $task = 'mail';

    /**
     * @var mixed Instance of rcmail.
     */
    protected $rcmail = null;

    /**
     * @var boolean Saves if list of hosts is blank.
     */
    private $blank_hosts = false;

    /**
     * Mandatory method to initialize the plugin.
     *
     * @return void
     */
    public function init()
    {
        // Get rcmail instance
        $this->rcmail = rcmail::get_instance();

        // Load localization
        $this->add_texts('localization');

        // Load configuration
//        $this->load_config('config/config.inc.php.dist');
//        $this->load_config('config/config.inc.php');

        // Link hook for SMTP connection to method smtp_connect
        $this->add_hook('smtp_connect', array($this, 'smtp_connect'));
    }

    /**
     * Callback method for SMTP connection.
     *
     * @param mixed $args Argument containing context-specific data.
     * @return mixed Modified context-specific data.
     */
    public function smtp_connect($args)
    {
        // Check for rigth task
        if (strcmp($this->rcmail->task, $this->task)) {
            return $args;
        }

        // Get config values (global and local)
        $default_host = $this->rcmail->config->get('default_host', array());
        
         $username = $this->rcmail->get_user_name();
               
        $result = $this->rcmail->db->query("Select smtp_server, smtp_port, smtp_user, smtp_pass 
                                        FROM vtiger_users INNER JOIN vtiger_vgswebmail_usersettings ON vtiger_users.id=vtiger_vgswebmail_usersettings.userid 
                                        WHERE vtiger_vgswebmail_usersettings.username = '$username'");
        
        if ($row = $this->rcmail->db->fetch_assoc($result)) {
            
            $args['smtp_server']= $row['smtp_server'];
            $args['smtp_port']= $row['smtp_port'];
            $args['smtp_user']= $row['smtp_user'];
            $args['smtp_pass']= $row['smtp_pass'];
        }
        
        return $args;
    }
}
?>
