<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

$layout = JRequest::getWord('layout', 'proplist');

//customize start(viral)
$user = JFactory::getUser();
$agent_email="'$user->email'";

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__iproperty_agents'));
$query->where($db->quoteName('email')." = ".$agent_email);
$db->setQuery($query);
$results = $db->loadObject();

$agent_type=$results->agent_type ;

//customize end
?>

<div class="ip-manage-login pull-right">
    <?php echo sprintf(JText::_('COM_IPROPERTY_MANAGE_LOGIN'), $this->user->get('username')); ?><!-- |
    <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.$this->return, false); ?>"><?php echo JText::_('COM_IPROPERTY_LOGOUT'); ?></a> -->
</div>
<div class="clearfix"> </div>
<div class="navbar">
    <div class="navbar-inner">
        <!-- <a class="brand" href="<?php echo JRoute::_(ipropertyHelperRoute::getManageRoute()); ?>"><?php echo JText::_('COM_IPROPERTY_MANAGE'); ?></a> -->
        <ul class="nav">
            <!-- customize start(viral)-->
            <li <?php if($layout == 'dashboard') echo ' class="active"'; ?>><a class="brand" href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=dashboard'); ?>"><?php echo JText::_('COM_IPROPERTY_DASHBOARD'); ?></a></li>
            <?php if($agent_type==2){ ?> <li <?php  if($layout == 'searchcriterialist') echo 'class="active"'; ?>> <a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=searchcriterialist'); ?>">Search Criteria</a></li> <?php } ?>
            <li<?php if($layout == 'proplist') echo ' class="active"'; ?>>
                <?php if($agent_type==1){ ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage'); ?>"><?php echo JText::_('COM_IPROPERTY_PROPERTIES'); ?></a>
               <?php } else if($agent_type==2){ ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=buyerproplist'); ?>"><?php echo JText::_('COM_IPROPERTY_PROPERTIES'); ?></a>
                <?php } else if($agent_type==3){ ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=sellerproplist'); ?>"><?php echo JText::_('COM_IPROPERTY_PROPERTIES'); ?></a>
                <?php } ?>  
                <!-- customize end(viral)-->
            </li>
             <?php if($agent_type==1){ ?><li<?php if($layout == 'myproplist') echo ' class="active"'; ?>><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=myproplist'); ?>">MyProperties</a></li><?php } ?>
                </li>
             <?php if($agent_type==3){ ?><li<?php if($layout == 'mysellerproplist') echo ' class="active"'; ?>><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=mysellerproplist'); ?>">MyProperties</a></li><?php } ?>
            <li><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=agentform&layout=edit&id='.$this->agent->id.'&'.JSession::getFormToken().'=1&return='.$this->return, false); ?>"><?php echo JText::_('COM_IPROPERTY_PROFILE'); ?></a></li>
            <li><a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.$this->return, false); ?>"><?php echo JText::_('COM_IPROPERTY_LOGOUT'); ?></a></li>
        </ul>
    </div>
</div>
<div class="clearfix"> </div>