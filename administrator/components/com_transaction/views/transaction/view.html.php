<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class transactionViewtransaction extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		$model = $this->getModel('transaction');
		$this->val = $model->getData();
		$this->addToolbar();
		$this->pagination	= $this->get('Pagination');
		$this->state         = $this->get('State');
		parent::display($tpl);
	}
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('List Transaction'));
		JToolBarHelper::divider();
		JToolBarHelper::custom('transaction.approve', 'transaction.png', 'transaction_f2.png', 'Approve', true);
		//JToolBarHelper::custom('transaction.disapprove', 'transaction.png', 'transaction_f2.png', 'Dispprove', true);
		JToolBarHelper::custom('transaction.delete', 'transaction.png', 'transaction_f2.png', 'Delete', true);

    }
    protected function getSortFields()
	{
		return array(
			'MLS' => JText::_('MLS'),
			'transaction' => JText::_('Transaction'),
            'status' => JText::_('Status'),
			'listing_price' => JText::_('Listing Price'),
			'listing_date' => JText::_('Listing Date'),
			'p.id' => JText::_('ID')
		);
	}


}