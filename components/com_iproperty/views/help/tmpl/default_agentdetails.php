<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->result);
defined( '_JEXEC' ) or die( 'Restricted access');
echo $this->loadTemplate('toolbar');
$pspan = ($this->settings->show_propupdate) ? 6 : 5;
?>
<h3><?php echo JText::_($this->result->subject."-	"."question"); ?></h3>
<div class="span12">
	<div class="span3 pull-left">
		<span><strong><?php echo JText::_('Subject'); ?></strong></span>
	</div>
	<div class="span3">	
		<span><?php echo $this->result->subject; ?></span>
	</div>
</div>
<div class="span12">
	<div class="span3 pull-left">
		<span><strong><?php echo JText::_('Ticket Reference Number'); ?></strong></span>
	</div>
	<div class="span3">	
		<span><?php echo $this->result->tc_ref_no; ?></span>
	</div>
</div>
<div class="span12">
	<div class="span3 pull-left">
		<span><strong><?php echo JText::_('Email Address'); ?></strong></span>
	</div>
	<div class="span3">	
		<span><?php echo $this->result->agent_email; ?></span>
	</div>
</div>
<div class="span12">
	<div class="span3 pull-left">
		<span><strong><?php echo JText::_('Posted On'); ?></strong></span>
	</div>
	<div class="span3">	
		<span><?php echo $this->result->post_date; ?></span>
	</div>
</div>
<div class="span12">
	<div class="span3 pull-left">
		<span><strong><?php echo JText::_('Status'); ?></strong></span>
	</div>
	<div class="span3">	
		<span><?php echo $this->result->status; ?></span>
	</div>
</div>
<div class="span12" style="border:1px solid black">
	<!-- <div style="height:70px;width:0px;border:4px solid black"></div> -->
	<div class="span12">
	<?php
	$a=strtotime($this->result->post_date);
	//$date=date('dS F Y - H:i:s A',$a);
	$date=date('l,F d, Y - H:i:s A',$a);
	//echo $date;
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
		->select('*')
	    ->from('#__iproperty_helpdesk_answer')
	    ->where($db->quoteName('question_id')."=".$this->result->id)
	    ->where($db->quoteName('agent_id')."=".$this->result->agent_id);
	    $db->setQuery($query);
	    //echo $query; exit;
	    $res = $db->loadObjectlist();
	    //echo "<pre>"; print_r($res);

	    
	?>
		<div class="span12">
			<div class="span12">
				<span><strong style="font-size:25px">1</strong>   Message by <strong> Buyer</strong> Posted on <?php echo $date?></span>
			</div>
			<div class="span12">
				<span><?php echo $this->result->question?></span>
			</div>
		</div>
		<?php
			$i = 2;
			foreach ($res as $value) { 
				$a1=strtotime($value->post_date);
				//$date=date('dS F Y - H:i:s A',$a);
				$date1=date('l,F d, Y - H:i:s A',$a1);
				?>
				<div class="span12" style="margin-top:2%;border: 1px solid #000000;border-bottom-left-radius: 2em;width:80%" id="answer">
					<div class="span12">
						<span><strong style="font-size:25px"><?php echo $i;?></strong> Message by <strong>you</strong> Posted on <?php echo $date1?></span>
					</div>
					<div class="span12">
						<span><?php echo $value->answer?></span>
					</div>
				</div>
			<?php $i++; } ?>
	</div>
	
	<div class="span8">
	<form action="index.php?option=com_iproperty&view=agentdetails" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
		<div><strong>post reply</strong></div>
		<div class="control-group">
            <div class="controls">
                <?php echo $this->form->getInput('answer'); ?>
            </div>
        </div>

		<!-- <div><textarea rows="5" cols="35" name="reply" style="height:auto;width:auto"></textarea></div> -->

		<div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('help.reply')">Submit</button>
                    <a href="index.php?option=com_iproperty&view=help" class="btn btn-primary">Cancel</a>
                </div>
        </div>
        		
        <div><input type="hidden" name="task" value="help.reply">
        		<input type="hidden" name="question_id" value="<?php echo $this->result->id; ?>">
        		<input type="hidden" name="agent_id" value="<?php echo $this->result->agent_id; ?>">
        		<input type="hidden" name="agent_email" value="<?php echo $this->result->agent_email; ?>">
        		<input type="hidden" name="buyer_email" value="<?php echo $this->result->buyer_email; ?>">
        </div>
	</form>
	</div>
</div>
<style type="text/css">
#jform_answer{height:auto;width:auto;}
div #answer:hover:nth-child(odd)
{
    background: #D3D3D3;
}
div #answer:hover:nth-child(even)
{
    background: #A9A9A9;
}
</style>


