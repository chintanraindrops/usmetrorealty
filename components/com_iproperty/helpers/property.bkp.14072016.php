<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

abstract class IpropertyHelperProperty
{
    public static function getPropertyItems($items = array(), $extras = false, $advsearch = false, $hidenopic = false)
    {
        $settings   = ipropertyAdmin::config();
        $hide_round = 3; // TODO: hardcoded, maybe should be option
        $nformat    = $settings->nformat;

        foreach ($items as $key => $item)
        {
            if (empty($item)) return false;
            // unset private data or items that shouldn't be shown to front end
            if (property_exists($item, 'agent_notes')) unset($item->agent_notes);
            if (property_exists($item, 'publish_up')) unset($item->publish_up);
            if (property_exists($item, 'publish_down')) unset($item->publish_down);
            if (property_exists($item, 'checked_out')) unset($item->checked_out);
            
            // common object properties
            if (property_exists($item, 'available')) {
                $item->available            = ($item->available && $item->available != '0000-00-00') ? $item->available : '';
            }
            
            $item->street_address       = ipropertyHTML::getStreetAddress($settings, $item);
            
            if (property_exists($item, 'short_description') && property_exists($item, 'description')) {
                $item->short_description    = ($item->short_description) ? $item->short_description : strip_tags($item->description);
            }
            
            // format the baths
            if (property_exists($item, 'baths')){
                if (!$settings->baths_fraction) {
                    $item->baths = round ($item->baths);
                } else {
                    $item->baths = ($nformat == 1) ? $item->baths : number_format($item->baths, 2, ',', '.');
                }
            }
            
            // check if latitude and longitude are not blank values. 
            // if blank, set them as false to return no marker or preview icon. 
            // if not, continue processing
            $item->lat_pos          = (!empty($item->latitude) && $item->latitude == '0.000000') ? false : (($item->hide_address) ? round($item->latitude, $hide_round) : $item->latitude);
            $item->long_pos         = (!empty($item->longitude) && $item->longitude == '0.000000') ? false : ($item->hide_address) ? round($item->longitude, $hide_round) : $item->longitude;

            // Get the thumbnail
            $item->thumb            = ipropertyHTML::getThumbnail($item->id, '', $item->street_address, '', 'class="img-polaroid ip-overview-thumb"');
            if ( $hidenopic && (strpos($item->thumb, 'nopic') !== false) ) unset($items[$key]);

            // Formatted 
            if (property_exists($item, 'price')){
                $item->formattedprice   = ipropertyHTML::getFormattedPrice($item->price, $item->stype_freq, false, $item->call_for_price, $item->price2);           
            }
            if (property_exists($item, 'sqft')){
                $item->formattedsqft    = ($nformat == 1) ? number_format($item->sqft) : number_format($item->sqft,  0, ',', '.');
            }

            // Check if new or updated
            if (property_exists($item, 'created')){
                $item->new          = ipropertyHTML::isNew($item->created, $settings->new_days);
            } else {
                $item->new          = false;
            }
            if (property_exists($item, 'modified')){
                $item->updated          = ipropertyHTML::isNew($item->modified, $settings->updated_days);
                // Get last modified date if available
                $item->last_updated     = ($item->modified != '0000-00-00 00:00:00') ? JHTML::_('date', htmlspecialchars($item->modified),JText::_('DATE_FORMAT_LC2')) : '';                        
            } else {
                $item->updated          = false;
            }

            // decode locations
            if (property_exists($item, 'country')){
                $item->countryname      = ipropertyHTML::getCountryName($item->country);
            }
            if (property_exists($item, 'locstate')){
                $item->statename        = ipropertyHTML::getStateName($item->locstate);
            }
            
            // Get common values for non-advanced search queries            
            if (!$advsearch)
            {
                if (isset($item->lotsize)){
                    $item->lotsize          = ($item->lotsize && is_numeric($item->lotsize)) ? ($nformat == 1) ? number_format($item->lotsize) : number_format($item->lotsize,  0, ',', '.') : $item->lotsize;
                }
                if (isset($item->lot_acres)){
                    $item->lot_acres        = ($item->lot_acres && is_numeric($item->lot_acres)) ? ($nformat == 1) ? number_format($item->lot_acres, 2) : number_format($item->lot_acres,  2, ',', '.') : $item->lot_acres;
                }
                if (isset($item->tax)){
                    $item->tax              = ($item->tax && is_numeric($item->tax)) ? ipropertyHTML::getFormattedPrice($item->tax) : '';
                }
                if (isset($item->income)){
                    $item->income           = ($item->income && is_numeric($item->income)) ? ipropertyHTML::getFormattedPrice($item->income) : '';
                }
                if (isset($item->stypename)){
                    $item->stypename        = ipropertyHTML::get_stype($item->stype);
                }
            }
            
            // Get extras and add to property objects for use in advanced search and modules
            if ($extras) 
            {
                if (property_exists($item, 'new') || property_exists($item, 'updated')){
                    $item->banner           = ipropertyHTML::displayBanners($item->stype, $item->new, JURI::root(), $settings, $item->updated);
                }
                // Get category icons
                $item->available_cats   = ipropertyHTML::getAvailableCats($item->id);
                $item->caticons         = array();
                if(property_exists($item, 'available_cats'))
                {
                    foreach( $item->available_cats as $c ){
                        $item->caticons[]  = ipropertyHTML::getCatIcon($c, 20);
                    }
                }
            }

            // get property link
            $item->proplink         = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias));
        }

        return $items;
    }
}
?>
