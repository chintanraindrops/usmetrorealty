<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Facebook_instant_articles
 * @author     Raindrops Infotech <raindropsinfotech@gmail.com>
 * @copyright  2016 Raindrops Infotech
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Listarticless list controller class.
 *
 * @since  1.6
 */
class transactionControllertransaction extends JControllerAdmin
{
	public function approve(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('transaction');
        $model->approve($cid);
	}
	public function disapprove(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('transaction');
        $model->disapprove($cid);
	}
	public function delete(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('transaction');
        $model->delete($cid);
	}
}