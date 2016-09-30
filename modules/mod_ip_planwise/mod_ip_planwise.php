<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

// check if we are in an iproperty property view
$jinput = JFactory::getApplication()->input;

$view 	= $jinput->get('view', '', 'string');
$option = $jinput->get('option', '', 'string');
$id		= $jinput->get('id', '', 'integer');

if ($option !== 'com_iproperty' || $view !== 'property') return false;

// we're in a property view, load the model
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_iproperty/models');
$model = JModelLegacy::getInstance( 'Property', 'IpropertyModel' );

$item = $model->getItem($id);

// if we have no price return false
if ($item->call_for_price) return false;

// Initialize variables
$locale	= $params->get('locale', 0);
$layout	= $params->get('slim', 0) ? 'slim' : 'standard';
$ref_id	= $params->get('referral_id', 'XW9BKCK9XU');
$country = 'US';

$script = "
var PLANWISE={widgets:{configParams:{
        global: {
          partnerReferralId: '".$ref_id."'";
// check for Canada
if ($locale) {
	$country = 'CA';
	$script .= ",
			currency: 'CAD',
			locale: 'en-CA'";
}          
$script .= "          
        },
        instances:[{
          type: 'BREst',
          settings: {
            selector: '#PLANWISE',
            renderingMode: '".$layout."'
          },
          data:{
            description: '".addslashes($item->short_description)."'
          }
        }],
        shared: {
          data: {
            property: {
              id: '".addslashes($item->mls_id)."',
              address: {
                streetAddress1: '".addslashes($item->street_address)."',
                city: '".addslashes($item->city)."',
                county: '".addslashes($item->county)."',
                postalCode: '".addslashes($item->postcode)."',";
// check for Canada
if (!$locale) {
	$script .= "
			usState: '".ipropertyHTML::getStateCode($item->locstate)."',
			";
} 

$script .= "                
                country: '".$country."'
              },
              type: 'SF',
              purchasePrice: ".addslashes($item->price)."
            }
          }
        }
      }}};

";

$doc =& JFactory::getDocument();
$doc->addScriptDeclaration($script);
// this SHOULD be the live URL for the script but it appears to be broken
$doc->addScript("https://widgets.planwise.com/widgets/widgetsLoader", 'text/javascript', false, true); // async true

// include iproperty css if set in parameters
if ($params->get('include_ipcss', 1) && !defined('_IPMODCSS')){
	define('_IPMODCSS', true);
}

require(JModuleHelper::getLayoutPath('mod_ip_planwise', $params->get('layout', 'default')));
