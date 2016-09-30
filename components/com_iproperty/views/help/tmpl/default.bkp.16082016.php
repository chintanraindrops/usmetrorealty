<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
echo $this->loadTemplate('toolbar');
?>
<h1><?php echo $this->msg; ?></h1>
<form action="index.php?option=com_iproperty&view=help" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('help.save')">Save</button>
                    <a href="index.php?option=com_iproperty&view=help" class="btn btn-primary">Cancel</a>
                </div>
            </div>
        <div class="panel panel-default" style="width:100%; padding: 10px; margin: 10px">
        <div id="Tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#information" aria-controls="personal" role="tab" data-toggle="tab">
                    Question</a></li>
            </ul>
            <div class="tab-content" style="padding-top: 20px">
            <div role="tabpanel" class="tab-pane active" id="information">
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('subject'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('subject'); ?>
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('listing_no'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('listing_no'); ?>
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('question'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('question'); ?>
                        </div>
                </div>
            </div>
            </div>
        </div>
        </div>

            <input type="hidden" name="task" value="help.save">
</form>