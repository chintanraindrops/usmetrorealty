<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('bootstrap.tooltip');
JHtml::_('bootstrap.modal');
?>

<div class="ip-advsearchpage<?php echo $this->pageclass_sfx;?>">
    <?php if ($this->params->get('show_savesearch')) : ?>
    <div id="save-panel">
        <?php echo $this->loadTemplate('searchsave'); ?>
    </div>
    <?php endif; ?>
    <?php if(!$this->fullscreen): ?>
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
    <?php endif; ?>
    <div id="ip-mapcontainer"<?php echo $this->fullscreen ? ' class="full-screen"' : ''; ?>>
		<i id="ip-mapcontrol-show" class="icon-chevron-left"></i>
        <?php
		switch ($this->settings->map_provider)
		{
			case 1:
				echo $this->loadTemplate('map_google'); //load google map
				break;
			case 2:
				echo $this->loadTemplate('map_bing'); //load bing map
				break;
		}
		echo $this->loadTemplate('controls'); //load map controls
        ?>       
	</div>
	<div id="ipMapSavePanel" style="display: none;">
		<?php echo $this->loadTemplate('searchsave'); //load map controls ?>
	</div>
	<?php  
	// display disclaimer if set in params
	if ($this->params->get('show_ip_disclaimer') && $this->settings->disclaimer)
	{
		echo '<div class="well well-small" id="ip-disclaimer">'.$this->settings->disclaimer.'</div>';
	}
	// display footer if enabled
	if ($this->settings->footer == 1) echo ipropertyHTML::buildThinkeryFooter();
	?>
</div>
