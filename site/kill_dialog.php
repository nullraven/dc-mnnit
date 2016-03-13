<?php
/**
 * Called by ajax request to set a cookie to kill prompts (request.php)
 *
 * Created by PhpStorm.
 * Date: 3/13/2016
 * Time: 2:46 PM
 */
setcookie('dcp_dialog_shown',true,time()+86400,"/");    //set cookie to expire after 1 day

?>