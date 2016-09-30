<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->result);
defined( '_JEXEC' ) or die( 'Restricted access');
echo $this->loadTemplate('toolbar');
$pspan = ($this->settings->show_propupdate) ? 6 : 5;
?>

<h3><?php echo JText::_('Help Desk List'); ?></h3>
<table class="table table-striped" id="favoritePropList">
    <thead>
        <tr>
            <th class="nowrap" width="35%"><?php echo JText::_('Question ref'); ?></th>
            <th class="nowrap hidden-phone" width="20%"><?php echo JText::_('Status'); ?></th>
            <th class="nowrap" width="20%"><?php echo JText::_('Subject'    ); ?></th>
            <th class="nowrap hidden-phone" width="15%"><?php echo JText::_('Post Date'); ?></th>
            <?php if($this->settings->show_propupdate): ?>
                <th class="nowrap" width="5%">
                    <span class="hasTooltip" title="<?php echo JText::_('COM_IPROPERTY_EMAIL_UPDATES'); ?> :: <?php echo JText::_('COM_IPROPERTY_EMAIL_UPDATES_TIP'); ?>">
                    <?php echo JText::_('COM_IPROPERTY_UPDATES'); ?>
                    </span>
                </th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        // list saved properties
        if ($this->result)
        {
            $k = 0;
            foreach($this->result as $p)
            {
                //echo "<pre>"; print_r($p); exit;
                    echo '<tr class="ip-row'.$k.'" id="'.$p->id.'" >
                            <td class="ipuser-title"><a href="index.php?option=com_iproperty&view=help&layout=agentdetails&id='.$p->id.'">'.$p->tc_ref_no.'</a></td>
                            <td class="nowrap hidden-phone">'.$p->status.'</td>
                            <td class="nowrap">'.$p->subject.'</td>
                            <td class="nowrap center hidden-phone">'.$p->post_date.'</td>';
                            if($this->settings->show_propupdate){
                                echo '<td class="nowrap center"><input class="ipsave_eupdate" type="checkbox"'.$checked.' /></td>';
            }   
            }
       } else {
            echo '<tr class="ip-row0">
                    <td colspan="'.$pspan.'" class="nowrap center">'.JText::_('COM_IPROPERTY_NO_RESULTS').'</td>
                  </tr>';
       }
       ?>
    </tbody>
</table>