{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}
{strip}
<style>
    #wrap { width: 430px; height: 250px; padding: 0; overflow: hidden; {$CSS_MARGIN} }
    #frame { width: 700px; height: 420px; border: 1px solid black; }
    #frame {
        -ms-zoom: {$SCALE},0.55;
        -moz-transform: scale({$SCALE},0.55);
        -moz-transform-origin: 0 0;
        -o-transform: scale({$SCALE},0.55);
        -o-transform-origin: 0 0;
        -webkit-transform: scale({$SCALE},0.55);
        -webkit-transform-origin: 0 0;
    }
</style>
<div id="wrap">
<iframe id="frame" scrolling="no" sandbox="allow-same-origin allow-scripts allow-popups allow-forms" src="../index.php?module=ChartsWidget&view=ChartDetail&record={$RECORD}" style="border:0;"></iframe>
</div>
{/strip}
