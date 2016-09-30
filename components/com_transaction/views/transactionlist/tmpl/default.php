<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
$document->addScript('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
echo $this->loadTemplate('toolbar');

?>
<h1><?php echo $this->msg; ?></h1>
<div class="container"> 
  <div class="btn-toolbar">
              <div class="btn-group">
                  <a href="index.php?option=com_transaction&view=transaction" class="btn btn-primary">ADD</button>
                  <a href="index.php?option=com_iproperty&view=manage&layout=dashboard" class="btn btn-primary">Back to dashboard</a>
              </div>
  </div>        
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Status</th>
        <th>MLS # </th>
        <th>Listing Price</th>
        <th>Listing Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($this->data as $value) { ?>
      <tr>
        <td><?php echo $value->status;?></td>
        <td><?php echo $value->MLS;?></td>
        <td><?php echo $value->listing_price;?></td>
        <td><?php echo $value->listing_date;?></td>
        <td><a href="index.php?option=com_transaction&view=transactionlist&layout=mytransactionlist&id=<?php echo $value->id?>" class="btn btn-primary">Details</a>
        <a href="index.php?option=com_transaction&view=transaction&layout=edit&id=<?php echo $value->id?>" class="btn btn-primary">Edit</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<script>
//modalToggle
jQuery(".modalToggle").click(function(){
    jQuery('#modal').removeClass('hide');
    });

</script>

       