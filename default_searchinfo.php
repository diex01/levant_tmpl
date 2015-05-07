<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking extension for Joomla
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2014 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

defined('_JEXEC') or die;

$dateCheckIn = JDate::getInstance();
$dateCheckOut = JDate::getInstance();
$showDateInfo = !empty($this->checkin) && !empty($this->checkout);

?>
<div class="alert alert-info availability-search-info" <?php echo ($showDateInfo) ? 'style="display: block"' : 'style="display: none"'?>>
	<?php
	if ($this->checkin && $this->checkout) :
		echo JText::sprintf('SR_ROOM_AVAILABLE_FROM_TO', $this->checkin, $this->checkout);
		echo ' <button class="btn" id="sr-change-date"><i class="icon-edit"></i> '.JText::_('SR_CHANGE').'</button>';
	endif;
	?>
</div>

<div class="alert alert-info availability-search-form" <?php echo ($showDateInfo) ? 'style="display: none"' : 'style="display: block"'?>>
	<form id="sr-checkavailability-form-component"
		  action="<?php echo JRoute::_('index.php', false)?>"
		  method="GET"
		  class="form-inline sr-validate"
		>
		<p>
			<?php echo JText::_('SR_ASK_FOR_CHECKIN_CHECKOUT') ?>
		</p>
		<input name="id" value="<?php echo $this->item->id ?>" type="hidden" />
		<input name="Itemid" value="<?php echo $this->itemid ?>" type="hidden" />

		<label for="checkin_component">
			<?php echo JText::_('SR_SEARCH_CHECKIN_DATE')?>
		</label>
		<input type="text"
			   name="checkin"
			   class="input-small checkin_component"
			   readonly="true"
			   value="<?php echo isset($this->checkin) ? $this->checkin : $dateCheckIn->add(new DateInterval('P'.($this->minDaysBookInAdvance).'D'))->setTimezone($this->timezone)->format('d-m-Y', true) ?>" required/>

		<label for="checkout_component">
			<?php echo JText::_('SR_SEARCH_CHECKOUT_DATE')?>
		</label>

		<input type="text"
			   name="checkout"
			   class="input-small checkout_component"
			   readonly="true"
			   value="<?php echo isset($this->checkout) ? $this->checkout : $dateCheckOut->add(new DateInterval('P'.($this->minDaysBookInAdvance + $this->minLengthOfStay).'D'))->setTimezone($this->timezone)->format('d-m-Y', true) ?>" required/>

		<button class="btn primary" type="submit"><i class="icon-search"></i> <?php echo JText::_('SR_SEARCH')?></button>
		<input type="hidden" name="option" value="com_solidres" />
		<input type="hidden" name="task" value="reservationasset.checkavailability" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>