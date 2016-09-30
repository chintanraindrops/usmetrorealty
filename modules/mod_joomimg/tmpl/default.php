<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-3/Modules/JoomImages/trunk/tmpl/default.php $
// $Id: default.php 4120 2013-02-28 21:51:03Z erftralle $
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$template = $app->getTemplate();
require_once JPATH_BASE.'/templates/'. $template .'/includes/Mobile_Detect.php';
JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript(JURI::base().'templates/'.$template.'/js/jquery.mixitup.min.js');
$detect = new Mobile_Detect;

// Defining sectiontableentry class
$sectiontableentry = "jg_row";
$secnr = 1;
$count_img_per_row = 0;

$csstag = $joomimgObj->getConfig("csstag");
$countobjects = count($imgobjects);
$count_img_per_row = 0;
        $count_img = 0; ?>
<?php if($joomimgObj->getConfig('sectiontableentry') == 1 )
{
  $rowclass = $sectiontableentry.$secnr." ".$csstag."row";
}
else
{
  $rowclass = "joomimg_row";
}

// Global module div
?>
<div class="<?php echo $csstag; ?>main joomimg_main" id="module_<?php echo $module->id; ?>">
  <ul class="filters btn-group" id="filters_<?php echo $module->id; ?>">
    <li><a class="filter btn btn-info" data-filter="all"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_SHOW_ALL'); ?></a></li>
    <?php foreach($cat_rows as $row) : ?>
    <li><a class="filter btn btn-info" data-filter="<?php echo $row->cid; ?>"><?php echo $row->name; ?></a></li>
  <?php
          endforeach; ?>
  </ul>
<?php
if($joomimgObj->getConfig('scrollthis')):
?>
  <marquee behavior="scroll" direction="<?php echo $joomimgObj->getConfig('scrolldirection'); ?>" loop="infinite"
  height="<?php echo $joomimgObj->getConfig('scrollheight'); ?>" width="<?php echo $joomimgObj->getConfig('scrollwidth'); ?>"
  scrollamount="<?php echo $joomimgObj->getConfig('scrollamount'); ?>" scrolldelay="<?php echo $joomimgObj->getConfig('scrolldelay'); ?>"
  <?php echo $joomimgObj->scrollmousecode; ?> class="<?php echo $joomimgObj->getConfig('csstag');?>scroll joomimg_scroll">
<?php
endif;

if($joomimgObj->getConfig('pagination') && $joomimgObj->getConfig('paginationpos') == 0):

  $paglinks = ceil($countobjects / $joomimgObj->getConfig('paginationct'));
?>
  <div class="<?php echo $csstag."pagnavi";?> joomimg_pagnavi">
    <span id="<?php echo $csstag."paglink_1"?>" class="<?php echo $csstag."paglinkactive";?> joomimg_paglinkactive">1</span>
<?php
  for($linkct = 2; $linkct <= $paglinks; $linkct++ ):
?>
    <span id="<?php echo $csstag."paglink_".$linkct?>" class="<?php echo $csstag."paglink";?> joomimg_paglink"><?php echo $linkct;?></span>
<?php
  endfor;
?>
  </div>
<?php
endif;
?>
  <div class="<?php echo $rowclass;?>">

<?php
$imgct=0;
if($countobjects > 0):
  foreach($imgobjects as $obj):
    $imgct++;
    if ($joomimgObj->getConfig('pagination')
        && $imgct > $joomimgObj->getConfig('paginationct')):
      break;
    endif;
?>
    <div class="<?php echo $csstag;?>imgct joomimg_imgct <?php echo $obj->catelem;?>">
    <div class="<?php echo $csstag;?>imgct_inner joomimg_imgct_inner">
<?php
  $count_img_per_row++;
?>
      <?php echo $obj->imgelem;?>
    </div>
    </div>
<?php
  endforeach;
// close last row
?>
  </div>
  <div class="joomimg_clr"></div>
<?php
else:
  if($joomimgObj->getConfig('show_empty_message')):
    echo JText::_('JINO_PICTURES_AVAILABLE');
  endif;
endif;
if($joomimgObj->getConfig('scrollthis') == 1):
?>
</marquee>
<?php
endif;
// Pagination if active
// Output all image elements in hidden container
// and the links for pagination
if($joomimgObj->getConfig('pagination')):
  if($joomimgObj->getConfig('paginationpos') == 1):
    $paglinks = ceil($countobjects / $joomimgObj->getConfig('paginationct'));
?>
  <div class="<?php echo $csstag."pagnavi";?>">
    <span id="<?php echo $csstag."paglink_1"?>" class="<?php echo $csstag."paglinkactive";?>">1</span>
<?php
    for($linkct = 2; $linkct <= $paglinks; $linkct++ ):
?>
    <span id="<?php echo $csstag."paglink_".$linkct?>" class="<?php echo $csstag."paglink";?>"><?php echo $linkct;?></span>
<?php
    endfor;
?>
  </div>
<?php
  endif;
?>
  <div id="<?php echo $csstag."pagelems";?>" style="display:none">
<?php
  // Output the html code of all image elements
  $imgct=0;
  foreach($imgobjects as $obj):
    $imgct++;
?>
    <div id="<?php echo $csstag."pagelem_".$imgct;?>" class="<?php echo $csstag."pagelem";?>">
<?php
      echo $obj->pagelem;
?>
    </div>
<?php
  endforeach;
?>
  </div>
<?php
endif;
?>
</div>

<script>
  jQuery(function($){

<?php 
if($detect->isIpad() || $detect->isIphone()) : ?>
  $('.jg_imgalign_catimgs').click(function(){
    $('.jg_imgalign_catimgs').removeClass('hover');
    $(this).addClass('hover');
  })
  $('*').not('.jg_imgalign_catimgs').click(function(){
    $('.jg_imgalign_catimgs').removeClass('hover');
  })
<?php endif; ?>
    var click = true;
    function fancybox_init(){
      $('a[data-fancybox="fancybox"]:visible').fancybox({
        padding: 0,
        margin: 0,
        loop: true,
        openSpeed:500,
        closeSpeed:500,
        nextSpeed:500,
        prevSpeed:500,
        afterLoad : function (){
          <?php if( $detect->isMobile() || $detect->isTablet() ){ ?>
          $('body').swipe({
            swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
              click = false;
              if(direction == 'left'){
                $.fancybox.next()
              }
              if(direction == 'right'){
                $.fancybox.prev()
              }
              setTimeout(function(){
                click = true;
              },100)
            }
          })*
          <?php } ?>
          $('.fancybox-inner').click(function(){
            if(click == true){
              $('body').toggleClass('fancybox-full');
            }
          })
        },
        beforeShow: function() {
          $('body').addClass('fancybox-lock');
        },
        afterClose : function() {
          $('body').removeClass('fancybox-lock');
          <?php if( $detect->isMobile() || $detect->isTablet() ){ ?>
          $('body').swipe('destroy')
          <?php } ?>
        },
        tpl : {
          image    : '<div class="fancybox-image" style="background-image: url(\'{href}\');"></div>'
        },
        helpers: {
          title : null,
          thumbs: {
            height: 50,
            width: 80,
            source: function(current) {
              return $(current.element).data('thumbnail');
            }
          },
          overlay : {
            css : {
              'background' : '#191919'
            }
          }
        }
      });
    }
    fancybox_init();
    $('#sort .sort').click(function(){
      $('#sort .sort').removeClass('selected');
      $(this).addClass('selected');
      $('#order .sort').attr('data-sort', $(this).attr('data-sort'))
    })
    $('#order .sort').click(function(){
      $('#order .sort').removeClass('selected');
      $(this).addClass('selected');
      $('#sort .sort').attr('data-order', $(this).attr('data-order'))
    })

    $(window).load(function(){

      var $container = $('#module_<?php echo $module->id; ?> .joomimg_row');

      $container.mixitup({
        targetSelector: '.<?php echo $csstag;?>imgct',
        filterSelector: '.filter',
        buttonEvent: 'click',
        effects: ['fade','scale','rotateZ'],
        listEffects: null,
        easing: 'smooth',
        layoutMode: 'grid',
        targetDisplayGrid: 'inline-block',
        targetDisplayList: 'block',
        gridClass: 'grid',
        listClass: 'list',
        transitionSpeed: 600,
        showOnLoad: 'all',
        sortOnLoad: false,
        multiFilter: false,
        filterLogic: 'or',
        resizeContainer: true,
        minHeight: 0,
        failClass: 'fail',
        perspectiveDistance: '3000',
        perspectiveOrigin: '50% 50%',
        animateGridList: true,
        onMixLoad: function(){
          $container.addClass('loaded');
        },
        onMixEnd: function(config){
          if(typeof $.fn.lazy == "function"){
            $("img.lazy:visible").lazy({
              bind: "event",
              threshold: 0,
              visibleOnly: false,
              effect: "fadeIn",
              effectTime: 500,
              enableThrottle: true,
              throttle: 500,
              afterLoad: function(element) {
                if(typeof $.fn.BlackAndWhite_init == "function"){
                  jQuery(element).parents(".item_img a .lazy_container").BlackAndWhite_init();
                }
              }
            });
          }
          fancybox_init()
        }
      });
   });
}); 
</script>