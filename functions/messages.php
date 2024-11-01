<?php
/*
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 */
 
// Display classic message
function upzfilemanager_displaymessage($message) {
    ?>
    <div class="updated">
        <p><?php echo $message; ?></p>
    </div>
    <?php
}

// Display error message
function upzfilemanager_displayerror($message) {
    ?>
    <div class="error">
        <p><?php echo $message; ?></p>
    </div>
    <?php
}