<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image) {
		$item->params->get('menu_text', 1) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else { $linktype = $item->title;
}

?>
<style>
#ip-quicksearch-form .adv-link_wrapper .adv-link{
	font-size: 24px;
}
#ip-quicksearch-form .adv-link_wrapper{
	margin-top: -24px; 
}
</style>
<form novalidate="novalidate" id="ip-quicksearch-form" class="ip-quicksearch-form form-inline adv-search" name="ip_quick_search" method="post" action="index.php?option=com_iproperty&amp;view=allproperties&amp;id=0&amp;Itemid=323">
            <div class="ip-quicksearch-optholder">
                <!-- Basic filters -->
                <div class="control-group">
                    <input type="text" value="" id="filter_keyword" name="filter_keyword" placeholder="Property MLS #, Address, City &amp; State or Zip" class="input-medium ip-qssearch custom-width">
                </div>

                <!-- Location filters -->
            </div>
            <div class="ip-quicksearch-sortholder">
                <div class="control-group pull-left">
                    <div class="btn-group">
                       <!--  <button class="btn" onclick="clearForm(this.form);this.form.submit();" type="button"></button> -->
                        <button type="submit" name="commit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>

            <!-- <div class="adv-link_wrapper">
                <a class="adv-link" href="index.php?option=com_iproperty&amp;view=advsearch&amp;Itemid=323">Advanced Search</a>
            </div> -->
            <input type="hidden" value="1" name="596f89a5e9fe6c9ef4a3e4e9570358cd">
            </form>

<span class="separator"><?php echo $title; ?><?php echo $linktype; ?></span>
