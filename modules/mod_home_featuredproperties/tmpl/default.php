<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */
$language = JFactory::getLanguage();
$language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
$language->load('com_iproperty', JPATH_SITE, null, true);
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Initialize variables
$usethumb           = $params->get('usethumb', 1);
$iplayout           = $params->get('iplayout', 'rows');
$rowspan            = ($iplayout == 'rows') ? 12 : (12 / $params->get('columns', '12'));
$moduleclass_sfx    = ($params->get('moduleclass_sfx')) ? ' '.htmlspecialchars($params->get('moduleclass_sfx')) : '';
//echo "<pre>"; print_r($items); exit;
        
?>
<style type="text/css">
    .ip-mod-thumb .ip-featured-img {
    transform: scale(1);
    transition: opacity 0.35s ease 0s, transform 2s ease 0s;
    width: 100%;
}


    .ip-mod-thumb .ip-featured-img:hover {
  transform: scale(1.5);
}



.ip-featuredproperties-thumb-holder-holder {
  overflow: hidden;
}

.ip-featuredproperties-thumb-holder, .ip-featured-img {
  min-height: 270px;
  position: relative;
  overflow: hidden;
}
</style>
<div class="row-fluid<?php echo $moduleclass_sfx; ?>">
    <?php
    $colcount = 0;
    foreach($items as $item)
    {
        $item->proplink = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias, '', true));
        if($item->stype == 1){ $item->stype = 'For Sale'; }
        if($item->stype == 2){ $item->stype = 'For Lease'; }
        if($item->stype == 3){ $item->stype = 'For Sale or Lease'; }
        if($item->stype == 4){ $item->stype = 'For Rent'; }
        if($item->stype == 5){ $item->stype = 'Sold'; }
        if($item->stype == 6){ $item->stype = 'Pending'; }
        ?>
        <div class="ip-featuredproperties-holder span<?php echo $rowspan; ?>">
            <?php if($iplayout == 'rows') echo '<div class="span12">'; ?>
                <div class="ip-mod-thumb ip-featuredproperties-thumb-holder">
                    <?php echo ipropertyHTML::getThumbnail($item->id, $item->proplink, $item->street_address, '', 'class="ip-featured-img thumbnail"', '', $usethumb); ?>
                    <?php if($params->get('show_banners', 1)) echo $item->banner; ?>
                </div>
            <?php 
            if($iplayout == 'rows'){
                echo '</div>';
            }else{
                echo '<div class="clearfix"></div>';
            }
            ?>
            <div class="ip-mod-desc ip-featuredproperties-desc-holder span12">
            	<?php if( $item->stype) echo '<strong>'.JText::_( 'COM_IPROPERTY_STYPE' ).':</strong> '.$item->stype.' &#160;&#160;<br>';
                if( $item->yearbuilt) echo '<strong>'.JText::_( 'COM_IPROPERTY_YEARBUILT' ).':</strong> '.$item->yearbuilt.' &#160;&#160;<br>';
                if( $item->formattedprice) echo '<strong>'.JText::_( 'COM_IPROPERTY_PRICE' ).':</strong> '.$item->formattedprice.' &#160;&#160;<br>';
                if( $item->city ) echo '<strong>'.JText::_( 'COM_IPROPERTY_CITY' ).':</strong> '.$item->city.' &#160;&#160;<br>';
                if( $item->statename ) echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.$item->statename.' &#160;&#160;<br>';
                //MOD_IP_FEATURED_READ_MORE
                ?>
            </div>
            <?php if($params->get('show_readmore', 1)): ?>
                <div class="ip-mod-readmore ip-featuredproperties-readmore span12">
                    <a href="<?php echo $item->proplink; ;?>" class="btn btn-primary readon"><?php echo JText::_('COM_IPROPERTY_VIEW_DETAILS'); ?></a>
                </div>
            <?php endif; ?>
        </div>
        <?php
        $colcount++;
        
        // we want to end div with row fluid class and start a new one if:
        // a) we are using the row layout - each row should be new
        // b) if using the column layout - if the column count has been reached
        if($iplayout == 'rows' || ($iplayout == 'columns' && $colcount == $params->get('columns')))
        {
            $colcount = 0;
            echo '</div><div class="row-fluid'.$moduleclass_sfx.'">';
        }
    }
    ?>
</div>
