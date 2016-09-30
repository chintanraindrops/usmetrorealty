<?php
/**
 * @version 3.3 6-2014
 * @package Joomla
 * @subpackage Intellectual Property Spark Platform
 * @copyright (C) 2009-2014 the Thinkery
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
JHtml::_('bootstrap.framework');

?>

<div class="ip_qsmod_holder">
    <form action="<?php echo JRoute::_(ipropertyHelperRoute::getAllPropertiesRoute().'&ipquicksearch=1'); ?>" method="<?php echo $params->get('form_method', 'post'); ?>" name="ip_searchmod" class="ip_quicksearch_form">
        <div class="control-group">
			<div class="controls text-center"">
				<div class="input-append">
					<input type="text" class="input-xxlarge ip-qssearch" placeholder="<?php echo JText::_('MOD_IP_ZSEARCH_PLACEHOLDER'); ?>" name="filter_keyword" />
					<button class="btn" type="submit"><i class="icon-search"></i></button>
				</div>
			</div>
        </div>

        <input type="hidden" name="option" value="com_iproperty" />
        <input type="hidden" name="view" value="allproperties" />
        <input type="hidden" name="Itemid" value="<?php echo $params->get('form_itemid'); ?>" />
		<input type="hidden" name="ipquicksearch" value="1" />
    </form>
</div>
