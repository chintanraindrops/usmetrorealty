<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Facebook_instant_articles
 * @author     Raindrops Infotech <raindropsinfotech@gmail.com>
 * @copyright  2016 Raindrops Infotech
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.

// Include dependancies
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('transaction');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
