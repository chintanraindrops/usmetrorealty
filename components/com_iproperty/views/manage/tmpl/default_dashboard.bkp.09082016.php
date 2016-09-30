<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::_('behavior.keepalive');

echo $this->loadTemplate('toolbar');

echo 'Dashboard';

//echo "<pre>";print_r($this->agent->agent_type); exit;
?>
<div class="span<?php echo $agent_span; ?> ip-agent-overview-desc">
            <?php /*if($this->ipauth->canEditAgent($this->agent->id)) echo '<div class="iplistaction">'.JHtml::_('icon.edit', $this->agent, 'agent').'</div>'; */ ?>
            <a href="<?php echo $agentlink; ?>"><b><span itemprop="name"><?php echo ipropertyHTML::getAgentName($this->agent->id); ?></span></b></a>
            <?php if($this->agent->companyid): ?>
                <?php echo $divider; ?><a href="<?php echo $companylink; ?>"><em><?php echo ipropertyHTML::getCompanyName($this->agent->companyid); ?></em></a>
            <?php endif; ?>
            <?php if($this->agent->agent_type): 
                if($this->agent->agent_type==1){ ?>
                    <em>(Agent)</em>
               <?php } else if($this->agent->agent_type==2){ ?>
                          <em>(Buyer)</em>
                <?php } else if($this->agent->agent_type==3){ ?> 
                    <em>(Seller)</em>
               <?php  }
           endif; ?>
            <?php if($this->agent->title): ?>

                <br /><span class="ip-agent-title"><?php echo $this->agent->title; ?></span>
            <?php endif; ?>
            <ul class="nav nav-tabs ip-overview-tabs">
                <?php                        
                if($this->agent->email && $this->settings->agent_show_contact && JRequest::getVar('view') != 'contact') echo '<li class="ip-contact pull-right"><a href="' . $agentcontactlink . '"><span class="icon-envelope ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_CONTACT_AGENT' ).'"></span></a></li>';
                if(JRequest::getVar('view') != 'agentproperties') echo '<li class="ip-props pull-right"><a href="'.$agentlink.'"><span class="icon-search ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VIEW_PROPERTIES' ).'"></span></a></li>';
                if($this->agent->website && $this->settings->agent_show_website) echo '<li class="ip-website pull-right"><a href="'.$this->agent->website.'" target="_blank" itemprop="url"><span class="icon-home ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VISIT').'"></span></a></li>';
                if($this->agent->featured) echo '<li class="ip_featured pull-right"><a href="'.$agentlink.'"><span class="icon-star ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_FEATURED').'"></span></a></li>';
                ?>
            </ul>
            <div class="row-fluid">
                <div class="span12">                    
                    <div class="span4 ip-agent-details">                
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->phone && $this->settings->agent_show_phone) echo '<li class="ip-phone"><b><abbr title="'.JText::_('COM_IPROPERTY_PHONE').'">P:</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agent->phone . '</span></li>';
                            if($this->agent->mobile && $this->settings->agent_show_mobile) echo '<li class="ip-cell"><b><abbr title="'.JText::_('COM_IPROPERTY_MOBILE' ).'">M:</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agent->mobile . '</span></li>';
                            if($this->agent->fax && $this->settings->agent_show_fax) echo '<li class="ip-fax"><b><abbr title="'.JText::_('COM_IPROPERTY_FAX' ).'">F:</abbr></b> <span itemprop="faxNumber">' . $this->agent->fax . '</span></li>';
                            if($this->agent->email && $this->settings->agent_show_email) echo '<li class="ip-email"><b><abbr title="'.JText::_('COM_IPROPERTY_EMAIL' ).'">E:</abbr></b> <span itemprop="email">'.JHTML::_('email.cloak', $this->agent->email).'</span></li>';                                                
                            if($this->agent->alicense && $this->settings->agent_show_license) echo '<li class="ip-license"><b><abbr title="'.JText::_('COM_IPROPERTY_LICENSE' ).'">L:</abbr></b> '.$this->agent->alicense . '</li>';
                            ?>
                        </ul>
                    </div>
                    <?php if ($this->settings->agent_show_social): ?>
                    <div class="span4 ip-social">
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->msn) {
								if(filter_var($this->agent->msn, FILTER_VALIDATE_URL)){ 
									$this->agent->msn = '<a href="'.$this->agent->msn.'" target="_blank">'.$this->agent->msn.'</a>';
								}
								echo '<li class="ip_msn"><b><abbr title="'.JText::_('COM_IPROPERTY_MSN' ).'">MSN:</abbr></b> ' . $this->agent->msn . '</li>';
							}
                            if($this->agent->skype) {
								if(filter_var($this->agent->skype, FILTER_VALIDATE_URL)){ 
									$this->agent->skype = '<a href="'.$this->agent->skype.'" target="_blank">'.$this->agent->skype.'</a>';
								}
								echo '<li class="ip-skype"><b><abbr title="'.JText::_('COM_IPROPERTY_SKYPE' ).'">S:</abbr></b><a href="skype:' . $this->agent->skype . '?call"> ' . $this->agent->skype . '</a></li>';
							}
                            if($this->agent->gtalk) {
								if(filter_var($this->agent->gtalk, FILTER_VALIDATE_URL)){ 
									$this->agent->gtalk = '<a href="'.$this->agent->gtalk.'" target="_blank">'.$this->agent->gtalk.'</a>';
								}
								echo '<li class="ip-gtalk"><b><abbr title="'.JText::_('COM_IPROPERTY_GTALK' ).'">G:</abbr></b> ' . $this->agent->gtalk . '</li>';
							}
                            if($this->agent->linkedin) {
								if(filter_var($this->agent->linkedin, FILTER_VALIDATE_URL)){ 
									$this->agent->linkedin = '<a href="'.$this->agent->linkedin.'" target="_blank">'.$this->agent->linkedin.'</a>';
								}
								echo '<li class="ip-linkedin"><b><abbr title="'.JText::_('COM_IPROPERTY_LINKEDIN' ).'">LI:</abbr></b> ' . $this->agent->linkedin . '</li>';
							}
                            if($this->agent->twitter) {
								if(filter_var($this->agent->twitter, FILTER_VALIDATE_URL)){ 
									$this->agent->twitter = '<a href="'.$this->agent->twitter.'" target="_blank">'.$this->agent->twitter.'</a>';
								}
								echo '<li class="ip-twitter"><b><abbr title="'.JText::_('COM_IPROPERTY_TWITTER' ).'">T:</abbr></b> ' . $this->agent->twitter . '</li>';
							}
                            if($this->agent->facebook) {
								if(filter_var($this->agent->facebook, FILTER_VALIDATE_URL)){ 
									$this->agent->facebook = '<a href="'.$this->agent->facebook.'" target="_blank">'.$this->agent->facebook.'</a>';
								}
								echo '<li class="ip-facebook"><b><abbr title="'.JText::_('COM_IPROPERTY_FACEBOOK' ).'">F:</abbr></b> ' . $this->agent->facebook . '</li>';
							}
                            if($this->agent->social1) {
								if(filter_var($this->agent->social1, FILTER_VALIDATE_URL)){ 
									$this->agent->social1 = '<a href="'.$this->agent->social1.'" target="_blank">'.$this->agent->social1.'</a>';
								}
								echo '<li class="ip-social1"><b><abbr title="'.JText::_('COM_IPROPERTY_SOCIAL1').'">O:</abbr></b> ' . $this->agent->social1 . '</li>';
							}
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->settings->agent_show_address): ?>
                    <div class="span4 ip-agent-address">
                        <address>
                            <?php
                            if($this->agent->street || $this->agent->street2) echo '<strong><span itemprop="streetAddress">'.$this->agent->street.' '.$this->agent->street2.'</span></strong><br />';
                            if($this->agent->city) echo '<span itemprop="addressLocality">'.$this->agent->city.'</span>';
                            if($this->agent->locstate) echo ', <span itemprop="region">'.ipropertyHTML::getStateName($this->agent->locstate) . '</span> ';
                            if($this->agent->province) echo ', <span itemprop="region">'.$this->agent->province . '</span> ';
                            if($this->agent->postcode) echo '<span itemprop="postalCode">'.$this->agent->postcode.'</span>';
                            if($this->agent->country) echo '<br /><span itemprop="country">'.ipropertyHTML::getCountryName($this->agent->country).'</span>';
                            ?>
                        </address>
                    </div> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row-fluid custom-total-items-for-buyer">
                <div class="span12"> 
                    <div class="span4 ip-agent-details">
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->agent_type==2){
                                $searchCriNum = ipropertyHTML::getBuyerSearchCriteriaNum($this->agent->id);
                                if($searchCriNum){
                                    echo '<li><span itemprop="country"><b>Total Search Criteria</b> : '.$searchCriNum.'</span></li>';
                                }
                            }
                            ?>
                            <?php
                            $savedPropsNum = ipropertyHTML::getSavedPropsNum();
                            if($savedPropsNum){
                                echo '<li><span itemprop="country"><b>Total Saved Properties</b> : '.$savedPropsNum.'</span></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <?php
            if($this->agent->bio && JRequest::getVar('view') != 'agents' && JRequest::getVar('view') != 'companyagents'):
                echo '<div class="clearfix"></div>';
                echo '<div class="ip-agentbio">'.JHTML::_('content.prepare', $this->agent->bio).'</div>';
            endif;
            $this->dispatcher->trigger( 'onAfterRenderAgentList', array( &$this->agent, &$this->settings ) );
            ?>
        </div>