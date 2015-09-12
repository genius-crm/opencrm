{*
/**
 * VGS Webmail Extension Module
 *
 *
 * @package        VGSWebmail Module
 * @author         Conrado Maggi - VGS Global - www.vgsglobal.com
 * @license        Comercial / VPL
 * @copyright      2014 VGS Global
 */
*}

<script>
    {literal}
        jQuery(document).ready(function() {
            jQuery('#leftPanel').addClass('hide');
            jQuery('#rightPanel').removeClass('span10').addClass('span12');
        });

    {/literal}    
</script>        
<div width="100%">
    <iframe id="rcframe" src="index.php?module=VGSWebmail&action=RCLogin" width="100%" height="800px" style="border:none;"></iframe>
</div>