<?php
defined('_JEXEC') or die;
include_once ('includes/functions.php');
include_once ('includes/includes.php');
require_once 'includes/Mobile_Detect.php';
$detect = new Mobile_Detect;
JHtml::_('bootstrap.framework');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
  <head>
    <?php if( $detect->isMobile() || $detect->isTablet() ){
      if(isset($_COOKIE['disableMobile'])){ ?>
        <?php if($_COOKIE['disableMobile']=='false'){ ?>
          <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1">
        <?php }
      }
      else { ?>
        <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1">
      <?php }
    }
      if ($themeLayout == 1){
          $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/layout.css');
      }
      if ($hideByEdit == false){
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/jquery.fancybox.css');
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/jquery.fancybox-buttons.css');
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/jquery.fancybox-thumbs.css');
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/template.css');
      }
      else{
        $doc->addStyleSheet(JURI::base().'administrator/templates/'.$adminTemplate.'/css/template.css');
        $doc->addStyleSheet(JURI::base().'templates/'.$this->template.'/css/edit.css');
      }
    ?>
    <jdoc:include type="head" />
    <!--[if lt IE 9]>
      <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie8.css" />
      <script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/html5shiv+printshiv.js"></script>
    <![endif]-->
     <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/font-awesome.css" />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
  </head>
  <body class="<?php echo $option . " view-" . $view . " task-" . $task . " itemid-" . $itemid . " body__" . $pageClass;if( $detect->isMobile() || $detect->isTablet() ){echo ' mobile';}?>">
    <!--[if lt IE 9]>
      <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
          <img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
        </a>
      </div>
    <![endif]-->
    <!-- Body -->
    <div id="wrapper">
      <div class="wrapper-inner">
        <?php if ($this->countModules('top') && $hideByEdit == false): ?>
        <!-- Top -->
        <div id="top-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="top" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="top" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <!-- Header -->
        <?php if ($hideByEdit == false): ?>
        <div id="header-row" data-stellar-background-ratio="0.5">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <header>
                <div class="<?php echo $rowClass; ?>">
                    <!-- Logo -->
                    <div id="logo" class="span<?php echo $this->params->get('logoBlockWidth'); ?>">
                    <?php //echo JURI::base().'media/com_iproperty/logo/logo.jpg'; ?>
                      <a href="<?php echo JURI::base(); ?>">
                        <?php if(isset($logo)) : ?>
                        <img src="<?php echo JURI::base().'media/com_iproperty/logo/logo.jpg';?>" alt="<?php echo $sitename; ?>">
                        <h1><?php echo $sitename; ?></h1>
                        <?php else : ?><h1><?php echo wrap_chars_with_span($sitename); ?></h1><?php endif; ?>
                      </a>
                    </div>
                    <jdoc:include type="modules" name="header" style="themeHtml5" />
                </div>
              </header>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('navigation') && $hideByEdit == false): ?>
        <!-- Navigation -->
        <div id="navigation-row" role="navigation">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="navigation" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('showcase') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Showcase -->
        <div id="showcase-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="<?php echo $rowClass; ?>">
                  <jdoc:include type="modules" name="showcase" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('feature') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Feature -->
        <div id="feature-row"<?php if( !$detect->isMobile() && !$detect->isTablet() && ((int)$detect->version('IE') == '' || (int)$detect->version('IE') > 8 )){ ?> data-stellar-background-ratio="0.5"<?php } ?>>
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="<?php echo $rowClass; ?>">
                  <jdoc:include type="modules" name="feature" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('maintop') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Maintop -->
        <div id="maintop-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="maintop" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="maintop" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <jdoc:include type="message" />
        <!-- Main Content row -->
        <div id="content-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="content-inner <?php echo $rowClass; ?>">   
                <?php if ($this->countModules('aside-left') && ($hideByOption) == false && $view !== 'form' && $hideByEdit == false): ?>     
                <!-- Left sidebar -->
                <div id="aside-left" class="span<?php echo $asideLeftWidth; ?>">
                  <aside role="complementary">
                    <jdoc:include type="modules" name="aside-left" style="html5nosize" />
                  </aside>
                </div>
                <?php endif; ?>        
                <div id="component" class="span<?php echo $mainContentWidth; ?>">
                  <main role="main">
                    <?php if ($this->countModules('breadcrumbs') && $layout !== 'edit'): ?>
                    <!-- Breadcrumbs -->
                    <div id="breadcrumbs-row">
                      <div id="breadcrumbs">
                        <jdoc:include type="modules" name="breadcrumbs" style="html5nosize" />
                      </div>
                    </div>
                    <?php endif; ?>       
                    <?php if ($this->countModules('content-top') && $hideByView == false && $hideByEdit == false): ?> 
                    <!-- Content-top -->
                    <div id="content-top-row" class="<?php echo $rowClass; ?>">
                      <div id="content-top">
                        <jdoc:include type="modules" name="content-top" style="themeHtml5" />
                      </div>
                    </div>
                    <?php endif; ?>        
                    <jdoc:include type="component" />   
                    <?php if ($this->countModules('content-bottom') && $hideByView == false && $hideByEdit == false): ?>     
                    <!-- Content-bottom -->
                    <div id="content-bottom-row" class="<?php echo $rowClass; ?>">
                      <div id="content-bottom">
                        <jdoc:include type="modules" name="content-bottom" style="themeHtml5" />
                      </div>
                    </div>
                    <?php endif; ?>
                  </main>
                </div>        
                <?php if ($this->countModules('aside-right') && ($hideByOption) == false && $view !== 'form' && $hideByEdit == false): ?>
                <!-- Right sidebar -->
                <div id="aside-right" class="span<?php echo $asideRightWidth; ?>">
                  <aside role="complementary">
                    <jdoc:include type="modules" name="aside-right" style="html5nosize" />
                  </aside>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <?php if ($this->countModules('video') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Video -->
        <div id="video-row">
          <jdoc:include type="modules" name="video" style="html5nosize" />
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('mainbottom') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Mainbottom -->
        <div id="mainbottom-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="mainbottom" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="mainbottom" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('bottom') && $hideByView == false && $hideByEdit == false): ?>
        <!-- Bottom -->
        <div id="bottom-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="bottom" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="bottom" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('footer')): ?>
        <!-- Footer -->
        <div id="footer-row"<?php if( !$detect->isMobile() && !$detect->isTablet() && ((int)$detect->version('IE') == '' || (int)$detect->version('IE') > 8 )){ ?> data-stellar-background-ratio="0.5"<?php } ?>>
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="footer" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="footer" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>  
        <div id="push"></div>
      </div>
    </div>
    <?php if ($hideByEdit == false): ?>
    <div id="footer-wrapper">
      <div class="footer-wrapper-inner">    
        <!-- Copyright -->
        <div id="copyright-row" role="contentinfo">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="<?php echo $rowClass; ?>">
                <div id="copyright" class="span<?php echo $this->params->get('footerWidth'); ?>">
                  <?php if($this->params->get('footerLogo') == 1) : ?>
                  <!-- Footer Logo -->
                  <a class="footer_logo" href="<?php echo $this->baseurl; ?>"><img src="<?php echo $footerLogo;?>" alt="<?php echo $sitename; ?>" /></a>
    					    <?php else: ?>
                  <span class="siteName"><?php echo $sitename; ?></span>
    					    <?php endif; ?>
  					      <?php if($this->params->get('footerCopy') == 1) echo '<span class="copy">&copy;</span>'; ?>
  					      <?php if($this->params->get('footerYear') == 1) echo '<span class="year">'.date('Y').'</span>'; ?>
                  <?php if($this->params->get('footerText') == 1) echo '<span class="copyText">All rights reserved. Terms of use</span>'; ?>
                  <?php if($this->params->get('privacyLink') == 1) :?>
                  <a class="privacy_link" rel="license" href="<?php echo $privacy_link_url; ?>"><?php echo $this->params->get('privacy_link_title'); ?></a>
    					    <?php endif; ?>
                  <?php if($this->params->get('termsLink') == 1) :?>
                  <a class="terms_link" href="<?php echo $terms_link_url; ?>"><?php echo $this->params->get('terms_link_title'); ?></a>
    					    <?php endif; ?>
                </div>
                <jdoc:include type="modules" name="copyright" style="themeHtml5" />
                <?php if($this->params->get('todesktop') && ($detect->isMobile() || $detect->isTablet()) && !$detect->isiPad()): ?>
                <div class="span12" id="to-desktop">
                  <a href="#">
                  <?php if(isset($_COOKIE['disableMobile'])){ ?>
                    <?php if($_COOKIE['disableMobile']=='false'){ ?>
                      <span class="to_desktop"><?php echo $this->params->get('todesktop_text') ?></span>
                    <?php }
                    else{ ?>
                      <span class="to_mobile"><?php echo $this->params->get('tomobile_text') ?></span>
                    <?php }
                  }
                  else{ ?>
                    <span class="to_desktop"><?php echo $this->params->get('todesktop_text') ?></span>
                  <?php } ?>
                  </a>
                </div>
                <?php endif; ?>
                More <a  rel='nofollow' href='http://www.templatemonster.com/category/real-estate-agency-joomla-templates/' target='_blank'>Real Estate Agency Joomla Templates at TemplateMonster.com</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if($this->params->get('totop')): ?>
    <div id="back-top">
      <a href="#"><span></span><?php echo $this->params->get('totop_text') ?></a>
    </div>
    <?php endif; ?>
    <?php if ($this->countModules('modal')): ?>
    <div id="modal" class="modal hide fade loginPopup">
      <button type="button" class="close modalClose">×</button>
      <jdoc:include type="modules" name="modal" style="modal" />
    </div>
    <?php endif; ?>
    <jdoc:include type="modules" name="debug" style="none"/>
    <?php if ($this->countModules('modal')){ ?>
    <script src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/jquery.centerIn.js'; ?>"></script>
    <script>
      jQuery(function($) {
        $('.modal.loginPopup').alwaysCenterIn(window);
      });
    </script>
    <?php } ?>
    <?php if( $detect->isiPad() || $detect->isiPod()){
      if(isset($_COOKIE['disableMobile'])){ ?>
        <?php if($_COOKIE['disableMobile']=='false'){ ?>
          <script src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/ios-orientationchange-fix.js'; ?>"></script>
        <?php }
      }
      else { ?>
        <script src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/ios-orientationchange-fix.js'; ?>"></script>
      <?php }
    }
    if( $detect->isMobile() || $detect->isTablet() ){ ?>
    <script src="<?php echo $this->baseurl.'/templates/'.$this->template.'/js/desktop-mobile.js'; ?>"></script>
    <?php } ?>
    <script src="<?php echo JURI::base().'/templates/'.$this->template.'/js/jquery.modernizr.min.js'; ?>"></script>
    <?php if( !$detect->isMobile() && !$detect->isTablet() && ((int)$detect->version('IE') == '' || (int)$detect->version('IE') > 8 )){ ?>
    <script src="<?php echo JURI::base().'/templates/'.$this->template.'/js/jquery.stellar.min.js'; ?>"></script>
    <script>
      jQuery(function($) {
        if (!Modernizr.touch) {
          $(window).load(function(){
            $.stellar({responsive: true,horizontalScrolling: false});
          });
        }
      });
    </script>
    <?php }
    if( !$detect->isMobile() && !$detect->isTablet() && ((int)$detect->version('IE') == '' || (int)$detect->version('IE') > 8 ) && !$detect->version('iOS')){ ?>
    <script src="<?php echo JURI::base().'/templates/'.$this->template.'/js/jquery.simplr.smoothscroll.min.js'; ?>"></script>
    <script>
      jQuery(function($) {
        if (!Modernizr.touch) {
          $.srSmoothscroll({ease: 'easeOutQuart'});
        }
      });
    </script>
    <?php }
    if($this->params->get('blackandwhite')): ?>
    <script src="<?php echo JURI::base().'/templates/'.$this->template.'/js/jquery.BlackAndWhite.min.js'; ?>"></script>
    <script>
      ;(function($, undefined) {
      $.fn.BlackAndWhite_init = function () {
        var selector = $(this);
        selector.find('img').not(".slide-img").parent().BlackAndWhite({
          invertHoverEffect: ".$this->params->get('invertHoverEffect').",
          intensity: 1,
          responsive: true,
          speed: {
              fadeIn: ".$this->params->get('fadeIn').",
              fadeOut: ".$this->params->get('fadeOut')." 
          }
        });
      }
      })(jQuery);
      jQuery(window).load(function($){
        jQuery('.item_img a').find('img').not('.lazy').parent().BlackAndWhite_init();
      });
    </script>
    <?php endif; ?>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.fancybox.pack.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.fancybox-buttons.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.fancybox-media.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.fancybox-thumbs.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.pep.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/jquery.vide.min.js'; ?>"></script>
    <script src="<?php echo JURI::base().'templates/'.$this->template.'/js/scripts.js'; ?>"></script>
    <?php endif; ?>
  </body>
</html>