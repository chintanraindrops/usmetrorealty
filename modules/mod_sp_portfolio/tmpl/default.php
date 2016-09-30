<?php
/*
# SP Portfolio - Simple Portfolio module by JoomShaper.com
# -------------------------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2013 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL V2 or Later
# Websites: http://www.joomshaper.com
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//echo "<pre>"; print_r($items); exit;
$language = JFactory::getLanguage();
$language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
$language->load('com_iproperty', JPATH_SITE, null, true);
$count = count($items);
//echo $column; exit;
?>

<?php if( $ajaxRequest ){ ?>
	<?php if($count>0){ ?>

		<?php foreach($items as $index=>$item){ 
			$item->proplink = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias, '', true));
        if($item->stype == 1){ $item->stype = 'For Sale'; }
        if($item->stype == 2){ $item->stype = 'For Lease'; }
        if($item->stype == 3){ $item->stype = 'For Sale or Lease'; }
        if($item->stype == 4){ $item->stype = 'For Rent'; }
        if($item->stype == 5){ $item->stype = 'Sold'; }
        if($item->stype == 6){ $item->stype = 'Pending'; }
			?>
			<li class="sp-portfolio-item col-<?php echo $column . ' ' . modSPPortfolioJHelper::slug($item->tag); ?>">
				<div class="sp-portfolio-item-inner">

					<div class="sp-portfolio-thumb">
						 <?php
							  echo ipropertyHTML::getrecentThumbnail($item->id, '','','', '','','', ''); ?>
					</div>

					<div class="sp-portfolio-item-details">
						<?php if( $item->stype) echo '<strong>'.JText::_( 'COM_IPROPERTY_STYPE' ).':</strong> '.$item->stype.' &#160;&#160;<br>';
		                if( $item->yearbuilt) echo '<strong>'.JText::_( 'COM_IPROPERTY_YEARBUILT' ).':</strong> '.$item->yearbuilt.' &#160;&#160;<br>';
		                if( $item->formattedprice){
			                	if($item->formattedprice == 'COM_IPROPERTY_CALL_FOR_PRICE'){
			                			$item->formattedprice = 'Call for price';
			                		} else {
			                			$item->formattedprice = $item->formattedprice;
			                		}
			                		echo '<strong>'.JText::_( 'COM_IPROPERTY_PRICE' ).':</strong> '.$item->formattedprice.' &#160;&#160;<br>';
			                }

		                if( $item->city ){
		                	if(preg_match('/^\d+$/',$item->city)) {
		                  		$item->city = ipropertyHTML::getCityName($item->city);
		                	} else {
		                  		$item->city = $value->city;
		                	}
		            	}
		                 echo '<strong>'.JText::_( 'COM_IPROPERTY_CITY' ).':</strong> '.$item->city.' &#160;&#160;<br>';
		                 if( $item->locstate ){
		                	echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';
		                 /*echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';*/
		                } else {
		                	echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';
		                }
                //MOD_IP_FEATURED_READ_MORE
                ?>
                <div class="ip-mod-readmore ">
                    <a href="<?php echo $item->proplink; ;?>" class="btn btn-primary readon"><?php echo JText::_('COM_IPROPERTY_VIEW_DETAILS'); ?></a>
                </div>
											</div><!--/.sp-portfolio-item-details-->
					<div style="clear:both"></div>	
				</div><!--/.sp-portfolio-item-inner-->
			</li>
		<?php } //end foreach ?>
	<?php } ?>
	<?php jexit(); ?>
<?php } ?>
<!--/Ajax Load-->
<style>
  .sp-portfolio-item-inner .sp-portfolio-thumb  {
    transform: scale(1);
    transition: opacity 0.35s ease 0s, transform 2s ease 0s;
    width: 100%;
}
    .sp-portfolio-item-inner .sp-portfolio-thumb :hover {
  transform: scale(1.5);
}
.ip-featuredproperties-thumb-holder-holder {
  overflow: hidden;
}

.sp-portfolio-item-inner, .sp-portfolio-thumb {
  min-height: 270px;
  position: relative;
  overflow: hidden;
}
</style>
<div id="sp-portfolio-module-<?php echo $uniqid; ?>" class="sp-portfolio default">

	<!-- <?php if(!empty($item)){ ?>
		<?php if($show_filter){ ?>
			<ul class="sp-portfolio-filter">
				<li><a class="btn active" href="#" data-filter="*"><?php echo JText::_('Show All'); ?></a></li>
				<?php foreach (modSPPortfolioJHelper::getCategories($catid) as $key => $value) { ?>
					<li>
						<a class="btn" href="#" data-filter=".<?php echo modSPPortfolioJHelper::slug($value) ?>">
							<?php echo ucfirst(trim($value)) ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	<?php } ?> -->

	<?php if($count>0) { 
		?>
		<ul class="sp-portfolio-items">
			<?php foreach($items as $index=>$item){
				$item->proplink = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias, '', true));
		        if($item->stype == 1){ $item->stype = 'For Sale'; }
		        if($item->stype == 2){ $item->stype = 'For Lease'; }
		        if($item->stype == 3){ $item->stype = 'For Sale or Lease'; }
		        if($item->stype == 4){ $item->stype = 'For Rent'; }
		        if($item->stype == 5){ $item->stype = 'Sold'; }
		        if($item->stype == 6){ $item->stype = 'Pending'; }
				?>
				<li class="sp-portfolio-item col-<?php echo $column . ' ' . modSPPortfolioJHelper::slug($item->tag); ?> visible">
					<div class="sp-portfolio-item-inner">

						<div class="sp-portfolio-thumb ">
						<?php
							  echo ipropertyHTML::getrecentThumbnail($item->id, '','','', '','','', ''); ?>
						</div>

						<div class="sp-portfolio-item-details">
							<?php if( $item->stype) echo '<strong>'.JText::_( 'COM_IPROPERTY_STYPE' ).':</strong> '.$item->stype.' &#160;&#160;<br>';
			                if( $item->yearbuilt) echo '<strong>'.JText::_( 'COM_IPROPERTY_YEARBUILT' ).':</strong> '.$item->yearbuilt.' &#160;&#160;<br>';
			                if( $item->formattedprice){
			                	if($item->formattedprice == 'COM_IPROPERTY_CALL_FOR_PRICE'){
			                			$item->formattedprice = 'Call for price';
			                		} else {
			                			$item->formattedprice = $item->formattedprice;
			                		}
			                		echo '<strong>'.JText::_( 'COM_IPROPERTY_PRICE' ).':</strong> '.$item->formattedprice.' &#160;&#160;<br>';
			                } 
			                if( $item->city ){
                		if(preg_match('/^\d+$/',$item->city)) {
		                  $item->city = ipropertyHTML::getCityName($item->city);
		                } else {
		                  $value->city = $value->city;
		                }
		        }
                 echo '<strong>'.JText::_( 'COM_IPROPERTY_CITY' ).':</strong> '.$item->city.' &#160;&#160;<br>';
               if( $item->locstate ){
		                	echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';
		                 /*echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';*/
		                } else {
		                	echo '<strong>'.JText::_( 'COM_IPROPERTY_STATE' ).':</strong> '.ipropertyHTML::getstatename($item->locstate).' &#160;&#160;<br>';
		                }

                //MOD_IP_FEATURED_READ_MORE
                ?>
                <div class="ip-mod-readmore ip-featuredproperties-readmore span12">
                    <a href="<?php echo $item->proplink; ;?>" class="btn btn-primary readon"><?php echo JText::_('COM_IPROPERTY_VIEW_DETAILS'); ?></a>
                </div>
						</div><!--/.sp-portfolio-item-details-->
						<div style="clear:both"></div>	
					</div><!--/.sp-portfolio-item-inner-->
				</li>
			<?php } ?>
		</ul><!--/.sp-portfolio-items-->

		<?php
		//echo $ajax_loader."ajax".$show_filter.$count."dddd".$total; exit;
		 if(($ajax_loader && $show_filter) && ($count>=3)) { ?>
			<div class="sp-portfolio-loadmore">
				<a href="#" class="btn btn-primary btn-large">
					<i class="icon-spinner icon-spin"></i>
					<span>Load More</span>
				</a>
			</div>
		<?php } ?>

	<?php } else { ?>
		<p class="alert">No item found!</p>
	<?php } ?>
</div><!--/.sp-portfolio-->

<?php if ($show_filter){ ?>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(function($){
			jQuery(window).load(function(){
				var $gallery = jQuery('.sp-portfolio-items');
				
				<?php if($rtl) { ?>
					// RTL Support
					$.Isotope.prototype._positionAbs = function( x, y ) {
						return { right: x, top: y };
					};
				<?php } ?>

				$gallery.isotope({
					// options
					itemSelector : 'li',
					layoutMode : 'fitRows'
					<?php if($rtl) { ?>
						,transformsEnabled: false
					<?php } ?>	
				});
				
				$filter = $('.sp-portfolio-filter');
				$selectors = $filter.find('>li>a');
					
				$filter.find('>li>a').click(function(){
					var selector = $(this).attr('data-filter');
					
					$selectors.removeClass('active');
					$(this).addClass('active');
					
					$gallery.isotope({ filter: selector });
					return false;
				});

				var $currentURL = '<?php echo  JURI::getInstance()->toString(); ?>';
				var $start = <?php echo $limit ?>;  // ajax start from last limit
				var $limit = <?php echo $ajaxlimit ?>;
				var $totalitem = <?php echo $total ?>;

				$('.sp-portfolio-loadmore > a').on('click', function(e){
					var $this = $(this);
					$this.removeClass('done').addClass('loading');
					$.get($currentURL, { moduleID: <?php echo $uniqid?>, start:$start, limit: $limit }, function(data){

						$start += $limit;

						var $newItems = $(data);
						$gallery.isotope( 'insert', $newItems );

						if( $totalitem <= $start ){
							$this.removeClass('loading').addClass('hide');

							// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
							if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
								jQuery(function($) {
								$("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
									return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
								});
							});
							}
							////

							return false;
						} else {
							$this.removeClass('loading').addClass('done');
							////

							// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
							if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
								jQuery(function($) {
								$("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
									return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
								});
							});
							}

						}

						});

					return false;
				});

			});
		});
	</script>
<?php }	