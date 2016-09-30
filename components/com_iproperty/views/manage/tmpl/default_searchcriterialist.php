<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHTML::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('dropdown.init');
JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
JFactory::getLanguage()->load('', JPATH_ADMINISTRATOR);

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering   = ($listOrder == 'ordering');

$sortFields = $this->getSortFields('property');
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
        //alert('here');
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<div class="ip-manage-list<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        </div>
    <?php endif; ?>
    <?php if ($this->params->get('show_ip_title') && $this->iptitle) : ?>
        <div class="ip-mainheader">
            <h2>
                <?php echo $this->escape($this->iptitle); ?>
            </h2>
        </div>        
    <?php endif; ?>
    <div class="clearfix"></div>
    
    <?php
    // main agent display
    if ($this->agent)
    {
        $this->k = 1;        
        $this->agents_folder = $this->ipbaseurl.'/media/com_iproperty/agents/';
        $this->agent_photo_width = $this->settings->agent_photo_width ? $this->settings->agent_photo_width : 90;
        // build agent display
        //echo $this->loadTemplate('agent');
    } elseif (!$this->ipauth->getAdmin()){
        // redirect to home page if not admin or agent
        JFactory::getApplication()->redirect(JURI::root);
    }
    // Build toolbar
    echo $this->loadTemplate('toolbar');
    ?>
        
    <form action="<?php echo JRoute::_(ipropertyHelperRoute::getManageRoute().'&layout='.JFactory::getApplication()->input->get('layout','proplist')); ?>" method="post" name="adminForm" id="adminForm">
        
        

        <h2><?php echo JText::_('COM_IPROPERTY_PROPERTIES_LIST'); ?></h2>    
        <div class="btn-toolbar">
            <div class="btn-group">
                <?php if ($this->ipauth->canAddProp()): ?>
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('searchcriteriaform.add')">
                    <i class="icon-plus"></i> <?php echo JText::_('JNEW'); ?>
                </button>
                <?php endif; ?> 
                <button type="button" class="btn hasTooltip" title="<?php echo htmlentities(JText::_('COM_IPROPERTY_DELETE'), ENT_QUOTES, 'UTF-8'); ?>" onclick="if(confirm('<?php echo addslashes(JText::_('COM_IPROPERTY_CONFIRM_DELETE')); ?>')){Joomla.submitbutton('searchcriteriaform.delete')}else{return false;}">
                    <i class="icon-remove"></i> 
                </button>                       
            </div>
        </div>
        <table class="ptable table table-striped" id="propertyList">
            <thead>
                <tr>
                    <th width="1%" class="center hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo htmlentities(JText::_('JGLOBAL_CHECK_ALL'), ENT_QUOTES, 'UTF-8'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>                    
                    <th width="55%" class="nowrap">
                        <?php //echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_STREET' ), 'street', $listDirn, $listOrder ); ?>
                        <?php //echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_LOCATION' ), 'city', $listDirn, $listOrder ); ?>
                        <?php echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_TITLE' ), 'title', $listDirn, $listOrder ); ?>
                        <?php //echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_REF' ), 'mls_id', $listDirn, $listOrder ); ?>
                        <?php //echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_PRICE' ), 'price', $listDirn, $listOrder ); ?>
                    </th>
                    <th width="20%" class="nowrap hidden-phone">City/Cities</th>
                    <th width="20%" class="nowrap center hidden-phone"><?php echo JText::_('COM_IPROPERTY_ACTION'); ?></th>
                    <th width="5%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <td class="center" colspan="6">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>

            <tbody>
                <?php
                //echo '<pre>';print_r($this->data);exit;
                if( $this->data ):                           
                    $k = 0;
                    foreach($this->data as $i => $item){

                        $canEdit        = $this->ipauth->canEditProp($item->id) && !$item->checked_out;
                        $canPublish     = $this->ipauth->canPublishProp($item->id, ($item->state == 1) ? 0 : 1) && !$item->checked_out;
                        $canFeature     = $this->ipauth->canFeatureProp($item->id, ($item->featured == 1) ? 0 : 1) && !$item->checked_out;
                        $canOrder       = ($this->ipauth->getAdmin() || $this->ipauth->getSuper()) && !$item->checked_out;
                        $canCheckin     = $this->user->authorise('core.manage',       'com_checkin') || $item->checked_out == $this->userId || $item->checked_out == 0;
                        $canApprove     = $this->ipauth->canApproveProp($item->id, ($item->approved == 1) ? 0 : 1) && !$item->checked_out;

                        //$linktitle      = ipropertyHTML::getStreetAddress($this->settings, $item);
                        //if($item->city) $linktitle .= ', '.$item->city;
                        //if($item->locstate) $linktitle .= ' - '.ipropertyHTML::getStateName($item->locstate);
                        $linktitle = $item->title;
                        $edit_link      = JRoute::_('index.php?option=com_iproperty&view=searchcriteriaform&layout=edit&id='.(int)$item->id.'&Itemid='.JRequest::getInt('Itemid'));
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>                            
                            <td class="has-context">
                                <div class="pull-left">
                                    <?php if ($item->checked_out) : ?>
                                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'proplist.', $canCheckin); ?>
                                    <?php endif; ?>
                                    <?php //echo '<span class="hasTooltip" title="'.htmlentities(ipropertyHTML::getThumbnail($item->id, '', $linktitle, 100)).'"><i class="icon-camera"></i></span> | '; ?>                                 
                                    <a href="<?php echo $edit_link; ?>">
                                        <?php echo $this->escape($linktitle); ?>
                                    </a>
                                    <?php //if ($this->settings->showtitle && $item->title) echo '<br /><span class="small">'.$item->street_address.'</span>'; ?>
                                    <?php //if ($item->mls_id) echo '<br /><b>'.JText::_('COM_IPROPERTY_MLS_ID').':</b> '.$item->mls_id; ?> 
                                    <?php //if ($item->price) echo '<br /><b>'.JText::_('COM_IPROPERTY_PRICE').':</b> '.ipropertyHTML::getFormattedPrice($item->price, $item->stype_freq, '', '', $item->price2, true); ?>
                                </div>
                                
                            </td>
                            <td class="small hidden-phone">
                                <?php $cities_names = $this->model->getCitiesNameFromId($item->city); ?>
                                <?php echo $cities_names; ?>
                            </td>
                            <td class="small hidden-phone center">
                              <a class="btn hasTooltip" href="<?php echo $edit_link; ?>">EDIT</a>
                               
                            </td>
                            <td class="center">
                                <?php echo $item->id; ?>
                            </td>
                          </tr>
                          <?php

                        $k = 1 - $k;
                    }
                else:
                    echo '<td class="center" colspan="6">'.JText::_('COM_IPROPERTY_NO_RESULTS').'</td>';
                endif;
                ?>
            </tbody>
        </table>
        <input type="hidden" name="id" value="<?php echo $item->id;?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>