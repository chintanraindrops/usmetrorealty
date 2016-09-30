<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
?>

<h1><?php echo JText::_('COM_IPROPERTY_CHANGE_LOG'); ?></h1>
<div>
    <?php
        jimport('joomla.filesystem.file');
    
        $logfile        = JPATH_COMPONENT_ADMINISTRATOR.'/assets/CHANGELOG.TXT';
        if(JFile::exists($logfile)){
            $logcontent     = JFile::read($logfile);
            $logcontent     = htmlspecialchars($logcontent, ENT_COMPAT, 'UTF-8');
        }else{
            $logcontent     = '';
        }

        if( !$logcontent ) {
            echo '<div class="center">'.JText::_('COM_IPROPERTY_NO_CHANGELOG' ).'</div>';
        }else{
            echo '<div><pre>'.$logcontent.'</pre></div>';
        }
    ?>
</div>