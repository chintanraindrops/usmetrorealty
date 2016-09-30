<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
$colspan    = ($this->print) ? 12 : 12;
?>

<div class="row-fluid ">
    <div class="span12">
        <div class="span<?php echo $colspan; ?> pull-left ip-desc-wrapper">
            <?php
                echo JHTML::_('content.prepare', $this->p->description );
                echo '<hr />';
                echo '<div class="row-fluid"><h4>'. JText::_("COM_IPROPERTY_AMENITIES").'</div></h4>';
                if($this->amenities)
                {
                    $amenities = array(
                        'general' => array(),
                        'interior' => array(),
                        'exterior' => array(),
						'accessibility' => array(),
						'green' => array(),
						'security' => array(),
						'landscape' => array(),
						'community' => array(),
						'appliance' => array()
                    );
                    foreach ($this->amenities as $amen)
                    {
                        switch ($amen->cat)
                        {
                            case 0:
                                $amenities['general'][] = $amen;
                                break;
                            case 1:
                                $amenities['interior'][] = $amen;
                                break;
                            case 2:
                                $amenities['exterior'][] = $amen;
                                break;
							case 3:
                                $amenities['accessiblity'][] = $amen;
                                break;
							case 4:
                                $amenities['green'][] = $amen;
                                break;
							case 5:
                                $amenities['security'][] = $amen;
                                break;
							case 6:
                                $amenities['landscape'][] = $amen;
                                break;
							case 7:
                                $amenities['community'][] = $amen;
                                break;
							case 8:
                                $amenities['appliance'][] = $amen;
                                break;
                            default:
                                $amenities['general'][] = $amen;
                                break;
                        }
                    }

                    foreach($amenities as $k => $a)
                    {
                        $amen_n     = (count($a));
                        if($amen_n > 0) 
                        {
                            switch($k)
                            {
                                case 'general':
                                    $amen_label = JText::_('COM_IPROPERTY_GENERAL_AMENITIES');
                                    break;
                                case 'interior':
                                    $amen_label = JText::_('COM_IPROPERTY_INTERIOR_AMENITIES');
                                    break;
                                case 'exterior':
                                    $amen_label = JText::_('COM_IPROPERTY_EXTERIOR_AMENITIES');
                                    break;
								case 'accessiblity':
                                    $amen_label = JText::_('COM_IPROPERTY_ACCESSIBILITY_AMENITIES');
                                    break;
								case 'community':
                                    $amen_label = JText::_('COM_IPROPERTY_COMMUNITY_AMENITIES');
                                    break;
								case 'landscape':
                                    $amen_label = JText::_('COM_IPROPERTY_LANDSCAPE_AMENITIES');
                                    break;
								case 'green':
                                    $amen_label = JText::_('COM_IPROPERTY_GREEN_AMENITIES');
                                    break;
								case 'security':
                                    $amen_label = JText::_('COM_IPROPERTY_SECURITY_AMENITIES');
                                    break;
								case 'appliance':
                                    $amen_label = JText::_('COM_IPROPERTY_APPLIANCE_AMENITIES');
                                    break;
                            }
                            $amen_left  = '';
                            $amen_right = '';

                            for ($i = 0; $i < $amen_n; $i++)
                            {
                                /*if ($i < ($amen_n/2))
                                {
                                    $amen_left  = $amen_left.'<li class="ip_checklist"><span class="icon-ok"></span> '.$a[$i]->title.'</li>';
                                }
                                elseif ($i >= ($amen_n/2))
                                {
                                    $amen_right = $amen_right.'<li class="ip_checklist"><span class="icon-ok"></span> '.$a[$i]->title.'</li>';
                                }*/
                                 $amen_left  = $amen_left.'<ul class="span4"><li class="ip-bg-white"><span class="fa fa-chevron-circle-right blue-color"></span> '.$a[$i]->title.'</li></ul>';
                            }


                            echo '
                                <div class="row-fluid well">
                                    <h5><strong>'.$amen_label.'</strong></h5>
                                    <div class="span12">                                        
                                       
                                           '.$amen_left.'
                                       
                                    </div>
                                </div>';
                        }
                    }
                }
                if($this->p->terms) echo '<div class="ip-terms">'.$this->p->terms.'</div>';
            ?>
        </div>
        <?php if(!$this->print): ?>
     
        <?php endif; ?>
    </div>
</div>