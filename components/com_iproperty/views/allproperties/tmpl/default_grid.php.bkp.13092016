<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
$user = JFactory::getUser();
if($user->id != ''){
    echo $this->loadTemplate('toolbar');    
}
defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('bootstrap.tooltip');

$advanced_link = JRoute::_(ipropertyHelperRoute::getAdvsearchRoute());

// uncomment this line if you want the featured listing to only show on the first page of results
// @Todo: possibly add this as a menu item parameter
//$this->enable_featured = ($this->state->get('list.start')) ? false : true;

// check if we are filtering ONLY featured-- if so disable featured position
if ($this->params->get('featured', false)) {
	$this->featured = false;
}

?>

<div class="ip-proplist<?php echo $this->pageclass_sfx;?>">
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
    <?php if ($this->params->get('show_ip_pretext', false)) : ?>
		<div class="well">
			<?php echo $this->escape($this->params->get('show_ip_pretext')); ?>
		</div>
    <?php endif; ?>

    <div class="clearfix"></div>

    <?php
    //custom 
    if ($this->params->get('qs_show_quicksearch', 1)){
        echo $this->loadTemplate('search');
    }
    //end
     if ($this->params->get('show_ip_map', 0) && $this->items) {
        switch($this->settings->map_provider)
        {
            case '1': //google
            default:
                echo $this->loadTemplate('gmap');
                break;
            case '2': //bing
                echo $this->loadTemplate('bing');
                break;
        }
    }
    ?>        
    <?php 
    // featured properties top position
    if( $this->featured && $this->enable_featured && $this->settings->featured_pos == 0 ){
        echo '
        <h2 class="ip-property-header">'.JText::_( 'COM_IPROPERTY_FEATURED_PROPERTIES' ).'</h2>';
        $this->k = 0;
        foreach( $this->featured as $f ){
            $this->p = $f;
            echo $this->loadTemplate('property');
            $this->k = 1 - $this->k;
        }
    }

    // load quick search tmpl
    if ($this->params->get('qs_show_quicksearch', 1)){
        echo $this->loadTemplate('quicksearch');
    }

    // display results for properties
    if ($this->items)
    {
        echo '<h2 class="ip-property-header">'.JText::_('COM_IPROPERTY_PROPERTIES').'</h2><span class="pull-right small ip-pagination-results">'.$this->pagination->getResultsCounter().'</span>';
        echo '<div><div class="clearfix"></div>'; // container for entire result set
            // set counter

        if ($this->params->get('qs_show_quicksearch', 1)){
        echo $this->loadTemplate('sortbox');
    }
			$c = 1;
            foreach($this->items as $p) :           
				if ($c == 1){
					// start the new row
					echo '<div class="row-fluid">';
				}
                $this->p = $p;
                echo $this->loadTemplate('propgrid');
                if ($c == $this->width){
					// close the row
					echo '</div>';
					// reset counter
					$c = 1;
				} else {
					$c++;
				}
            endforeach;
        echo '</div>'; // end result set div
        echo '<div class="pagination">
                '.$this->pagination->getPagesLinks().'<br />'.$this->pagination->getPagesCounter().'
             </div>';
    } else {
        echo $this->loadTemplate('noresult');
    }
        
    // featured properties bottom position
    /*if( $this->featured && $this->enable_featured && $this->settings->featured_pos == 1 ){
        echo '
        <h2 class="ip-property-header">'.JText::_( 'COM_IPROPERTY_FEATURED_PROPERTIES' ).'</h2>';
        $this->k = 0;
        foreach( $this->featured as $f ){
            $this->p = $f;
            echo $this->loadTemplate('property');
            $this->k = 1 - $this->k;
        }
    }*/

    // display disclaimer if set in params
    if ($this->params->get('show_ip_disclaimer') && $this->settings->disclaimer){
        echo '<div class="well well-small" id="ip-disclaimer">'.$this->settings->disclaimer.'</div>';
    }
    // display footer if enabled
    if ($this->settings->footer == 1) echo ipropertyHTML::buildThinkeryFooter(); 
    ?>
</div>
