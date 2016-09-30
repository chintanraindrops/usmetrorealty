<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

$layout = JRequest::getWord('layout', 'proplist');
$view = JRequest::getWord('view', 'manage'); // [[CUSTOM]] , RI

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
            <?php if($agent_type==2){ ?>
            <?php
            $this->ipauth  = new ipropertyHelperAuth();
            $this->ipauth->getAgentInfoByUserId();
            $agentId = $this->ipauth->getAgentId();
            $query_sc = $db->getQuery(true);
            $query_sc->select('*');
            $query_sc->from($db->quoteName('#__iproperty_search_criteria'));
            $query_sc->where($db->quoteName('buyer_id')." = ".$agentId);
            $db->setQuery($query_sc);
            $res_sea_cri = $db->execute();
            $res_sea_cri_num = $db->getNumRows();
            $res_sea_cri_obj = $db->loadObject();
            //var_dump($res_sea_cri_num);var_dump($res_sea_cri_obj);exit;
            //var_dump($res_sea_cri_num);exit;
            if($res_sea_cri_num){
                $link_sc = 'index.php?option=com_iproperty&view=searchcriteriaform&layout=edit&id='.$res_sea_cri_obj->id;
                $search_criteria_link = JRoute::_($link_sc);
            } else {
                $search_criteria_link = JRoute::_('index.php?option=com_iproperty&view=searchcriteriaform');
            }
            ?>
            <li <?php  if($view == 'searchcriteriaform') echo 'class="active"'; ?>> <a href="<?php echo $search_criteria_link; ?>">Search Criteria</a></li>
            <?php } ?>
            <li<?php if($layout == 'proplist' || $layout == 'sellerproplist' || $layout == 'buyerproplist') echo ' class="active"'; ?>>
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
                <?php if($agent_type==1){ ?><li><a href="<?php echo JRoute::_('index.php?option=com_transaction&view=transactionlist'); ?>">Transaction</a></li><?php } ?>
                </li>
             <?php if($agent_type==3){ ?><li<?php if($layout == 'mysellerproplist') echo ' class="active"'; ?>><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=mysellerproplist'); ?>">MyProperties</a></li><?php } ?>
            <li><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=agentform&layout=edit&id='.$this->agent->id.'&'.JSession::getFormToken().'=1&return='.$this->return, false); ?>"><?php echo JText::_('COM_IPROPERTY_PROFILE'); ?></a></li>
            <li><a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&'.JSession::getFormToken().'=1&return='.$this->return, false); ?>"><?php echo JText::_('COM_IPROPERTY_LOGOUT'); ?></a></li>
        </ul>
    </div>
</div>
<div class="clearfix"> </div>