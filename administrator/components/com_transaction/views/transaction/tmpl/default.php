<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->state); exit;
// no direct access
defined('_JEXEC') or die;
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$ordering   = ($listOrder == 'ordering');
$colspan    = 12;
$sortFields = $this->getSortFields();
//echo "<pre>"; print_r($this->state); exit;
?>
<script type="text/javascript">
    Joomla.orderTable = function() {
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
<form method="post" name="adminForm" id="adminForm">

         <div id="filter-bar" class="btn-toolbar">
             <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="filter_search"><?php echo JText::_('Search'); ?></label>
                <input type="text" name="filter_search" class="inputbox" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search'));?>" title="<?php echo JText::_('Submit'); ?>" />
            </div>          
           <div class="btn-group pull-left hidden-phone">
                <button class="btn tip" type="submit" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button class="btn tip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
    ;
                <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                    <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                    <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                </select>
            </div>
          <div class="btn-group pull-right">
                <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                </select>
            </div>
        </div>
 <div class="clearfix"> </div>
<table class="table table-striped" id="propertyList">
            <thead>
                <tr>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="Check All" onclick="Joomla.checkAll(this)" />
                    </th>
                    <th width="5%" class="nowrap hidden-phone">Status</th>
                    <th width="5%" class="nowrap">MLS # </th>
                    <th width="5%" class="nowrap">Transaction # </th>
                    <th width="5%" class="nowrap hidden-phone">Listing Price</th>
                    <th width="5%" class="nowrap hidden-phone">Listing Date</th>
                    <th width="5%" class="nowrap hidden-phone">ID</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($this->val) > 0):
                    foreach ($this->val as $i => $item) :
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class=" hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="hidden-phone"><?php echo $item->status ?></td>
                            <td class="small hidden-phone "><?php echo $item->MLS; ?></td>
                            <td class="small hidden-phone "><?php echo $item->transaction; ?></td>
                            <td class="small hidden-phone"><?php echo $item->listing_price;?></td>
                            <td class="small hidden-phone"> <?php echo $item->listing_date;?></td>
                            <td class="small hidden-phone"> <?php echo $item->id;?></td>

                        </tr>
                    <?php 
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>" class="center">
                            <?php echo "No result found" ; ?>
                        </td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>