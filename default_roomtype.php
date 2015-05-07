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

$dayMapping = array('0' => JText::_('SUN'), '1' => JText::_('MON'), '2' => JText::_('TUE'), '3' => JText::_('WED'), '4' => JText::_('THU'), '5' => JText::_('FRI'), '6' => JText::_('SAT') );
$tariffBreakDownNetOrGross = $this->showTaxIncl == 1 ? 'net' : 'gross';
?>

<h3><?php echo JText::_('SR_AVAILABILITY') ?></h3>

<?php if (isset($this->checkin) && $this->checkout) : ?>
	<div class="coupon">
		<input type="text" name="coupon_code" class="span12" id="coupon_code" placeholder="<?php echo JText::_('SR_COUPON_ENTER') ?>"/>
		<?php if (isset($this->coupon)) : ?>
			<?php echo JText::_('SR_APPLIED_COUPON') ?>
			<span class="label label-success">
			<?php echo $this->coupon['coupon_name']	?>
		</span>&nbsp;
			<a id="sr-remove-coupon" href="javascript:void(0)" data-couponid="<?php echo $this->coupon['coupon_id'] ?>">
				<?php echo JText::_('SR_REMOVE') ?>
			</a>
		<?php endif ?>
	</div>
<?php endif ?>

<div class="wizard">
	<ul class="steps">
		<li data-target="#step1" class="active reservation-tab reservation-tab-room span4"><span class="badge badge-info">1</span><?php echo JText::_('SR_STEP_ROOM_AND_RATE') ?><span class="chevron"></span></li>
		<li data-target="#step2" class="reservation-tab reservation-tab-guestinfo span4"><span class="badge">2</span><?php echo JText::_('SR_STEP_GUEST_INFO_AND_PAYMENT') ?><span class="chevron"></span></li>
		<li data-target="#step3" class="reservation-tab reservation-tab-confirmation span4"><span class="badge">3</span><?php echo JText::_('SR_STEP_CONFIRMATION') ?><!--<span class="chevron"></span>--></li>
	</ul>
</div>
<div class="step-content">
	<div class="step-pane active" id="step1">
	<!-- Tab 1 -->
	<div class="reservation-single-step-holder room">
	<form enctype="multipart/form-data"
		  id="sr-reservation-form-room"
		  class="sr-reservation-form"
		  action="<?php echo JRoute::_('index.php?option=com_solidres&task=reservation.process&step=room&format=json') ?>"
		  method="POST">
	<?php if(count($this->item->roomTypes) > 0) : ?>
		<?php if (isset($this->checkin) && $this->checkout) : ?>
			<div class="row-fluid button-row button-row-top">
				<div class="span9">
					<div class="inner">
						<p><?php echo JText::_('SR_ROOMINFO_STEP_NOTICE_MESSAGE') ?></p>
					</div>
				</div>
				<div class="span3">
					<div class="inner">
						<div class="btn-group">
							<button data-step="room" type="submit" class="btn btn-success">
								<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i> <?php echo JText::_('SR_NEXT') ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>

		<?php
		$count = 1;
		foreach($this->item->roomTypes as $roomType ) :
			if (isset($roomType->defaultTariffBreakDown)) :
				$defaultTariffBreakDownHtml = '<table class=\"tariff-break-down\">';
				foreach ($roomType->defaultTariffBreakDown as $key => $breakDownDetails ) :
					if ($key % 7 == 0 && $key == 0) :
						$defaultTariffBreakDownHtml .= '<tr>';
					elseif ($key % 7 == 0) :
						$defaultTariffBreakDownHtml .= '</tr><tr>';
					endif;
					$tmpKey = key($breakDownDetails);
					$defaultTariffBreakDownHtml .= '<td><p>'.$dayMapping[$tmpKey].'</p><span class=\"'.$tariffBreakDownNetOrGross.'\">'.$breakDownDetails[$tmpKey][$tariffBreakDownNetOrGross]->format().'</span>';
				endforeach;
				$defaultTariffBreakDownHtml .= '</tr></table>';

			$this->document->addScriptDeclaration('
				Solidres.jQuery(function($){
					$(".default_tariff_break_down_'.$roomType->id.'").popover({
						html: true,
						content: "'.$defaultTariffBreakDownHtml.'",
						title: "'.JText::_('SR_TARIFF_BREAK_DOWN').'",
						placement: "bottom",
						trigger: "click"
					});
				});
			');
			endif;

			if (isset($roomType->complexTariffBreakDown)) :
				$complexTariffBreakDownHtml = '<table class=\"tariff-break-down\">';
				foreach ($roomType->complexTariffBreakDown as $key => $breakDownDetails ) :
					if ($key % 7 == 0 && $key == 0) :
						$complexTariffBreakDownHtml .= '<tr>';
					elseif ($key % 7 == 0) :
						$complexTariffBreakDownHtml .= '</tr><tr>';
					endif;
					$tmpKey = key($breakDownDetails);
					$complexTariffBreakDownHtml .= '<td><p>'.$dayMapping[$tmpKey].'</p><span class=\"'.$tariffBreakDownNetOrGross.'\">'.$breakDownDetails[$tmpKey][$tariffBreakDownNetOrGross]->format().'</span>';
				endforeach;

				$complexTariffBreakDownHtml .= '</tr></table>';
				$this->document->addScriptDeclaration('
					Solidres.jQuery(function($){
						$(".complex_tariff_break_down_'.$roomType->id.'").popover({
							html: true,
							content: "'.$complexTariffBreakDownHtml.'",
							title: "'.JText::_('SR_TARIFF_BREAK_DOWN').'",
							placement: "bottom",
							trigger: "click"
						});
					});
				');
			endif;

			$this->document->addScriptDeclaration('
				Solidres.jQuery(function($){
					$(".sr-photo-'.$roomType->id.'").colorbox({rel:"sr-photo-'.$roomType->id.'", transition:"fade"});
				});
			');

			$rowCSSClass = ($count % 2) ? ' even' : ' odd';
			$rowCSSClass .= $roomType->featured == 1 ? ' featured' : '';
			$currentSelectedRoomTypeCount = 0;
			if ( isset($this->selectedRoomTypes['room_types'][$roomType->id]))
			{
				$currentSelectedRoomTypeCount = count($this->selectedRoomTypes['room_types'][$roomType->id]);
			}

			?>
			<div class="row-fluid">
				<div class="span6">
					<div class="inner">
						<h4 class="roomtype_name">
							<a href="#" class="room_type_details" id="room_type_details_handler_<?php echo $roomType->id ?>">
								<?php echo $roomType->name; ?>
							</a>
							<?php if ($roomType->featured == 1) : ?>
								<span class="label label-success"><?php echo JText::_('SR_FEATURED_ROOM_TYPE') ?></span>
							<?php endif ?>
						</h4>

						<?php if ( !empty($roomType->media) ) : ?>
							<a class="room_type_details sr-photo-<?php echo $roomType->id ?>" href="<?php echo SRURI_MEDIA.'/assets/images/system/'.$roomType->media[0]->value; ?>">
								<img src="<?php echo SRURI_MEDIA.'/assets/images/system/thumbnails/2/'.$roomType->media[0]->value; ?>"
									 alt="<?php echo $roomType->media[0]->name ?>"/>
							</a>
						<?php endif ?>

						<div class="roomtype_desc">
							<ul class="unstyled">
								<li>
									<?php
									if ( isset($roomType->roomtype_custom_fields['free_cancellation']) && $roomType->roomtype_custom_fields['free_cancellation']== 1) :
										echo JText::_('SR_FREE_CANCELLATION');
									else :
										echo JText::_('SR_NON_REFUNDABLE');
									endif;
									?>
								</li>
								<li>
									<?php
									if ( isset($roomType->roomtype_custom_fields['breakfast_included']) && $roomType->roomtype_custom_fields['breakfast_included']== 1) :
										echo JText::_('SR_BREAKFAST_INCLUDED');
									else :
										echo JText::_('SR_BREAKFAST_EXCLUDED');
									endif;
									?>
								</li>
								<li>
									<?php echo JText::_('SR_ROOM_OCCUPANCY') ?>
									<?php for ($i = 0, $n = ((int)$roomType->occupancy_adult + (int)$roomType->occupancy_child); $i < $n; $i++) : ?>
										<i class="icon-user uk-icon-user fa-user"></i>
									<?php endfor; ?>
								</li>
							</ul>
						</div>

					</div>
				</div>

				<div class="span3">
					<div class="inner align-right">
						<?php
						if (isset($roomType->complexTariff) || isset($roomType->defaultTariff)) :
							if (!isset($roomType->complexTariffType)) :
								$roomType->complexTariffType = 0;
							endif;
						?>
							<?php if ($roomType->complexTariffType != 1 ) : ?>
								<p>
									<?php
									$defaultTariffCSSClass = 'default-tariff';
									if (!empty($roomType->complexTariff) && $roomType->complexTariff->getValue() != $roomType->defaultTariff->getValue())
										$defaultTariffCSSClass .= ' line-through';
									else if ($roomType->defaultTariffIsAppliedCoupon)
										$defaultTariffCSSClass .= ' is-applied-coupon';
									?>
									<span class="<?php echo $defaultTariffCSSClass ?>">
									<?php echo $roomType->defaultTariff->format() ?>
									</span>
									<i class="icon-help fa-question-circle uk-icon-question-circle default_tariff_break_down_<?php echo $roomType->id ?>"></i>
								</p>
							<?php endif ?>

							<p>
								<?php
								$customTariffCSSClass = 'custom-tariff complex_tariff_break_down_'.$roomType->id;
								if ($roomType->complexTariffType == 1 ) :
									echo JText::_('SR_TARIFF_IS_FOR_PER_PERSON_PER_NIGHT');
								else :
									if (!empty($roomType->complexTariff)
										&& $roomType->complexTariff->getValue() != $roomType->defaultTariff->getValue()) :
										if ($roomType->complexTariffIsAppliedCoupon) :
											$customTariffCSSClass .= ' is-applied-coupon';
										endif;
										?>
										<span class="<?php echo $customTariffCSSClass ?>" id="custom-tariff-<?php echo $roomType->id ?>">
											<?php if (!empty($roomType->complexTariff)) echo $roomType->complexTariff->format(); ?>
										</span>
										<i class="icon-help fa-question-circle uk-icon-question-circle complex_tariff_break_down_<?php echo $roomType->id ?>"></i>
									<?php
									endif;
								endif
								?>
							</p>
						<?php endif ?>
					</div>
				</div>

				<div class="span3">
					<div class="inner">
						<?php
						if (isset ($roomType->totalAvailableRoom)) :
							if ($roomType->totalAvailableRoom == 0) :
								echo JText::_('SR_NO_ROOM_AVAILABLE');
							else :
								if (empty($roomType->complexTariff) && empty($roomType->defaultTariff)) :
									echo JText::_('SR_NO_TARIFF_AVAILABLE');
								else :
									if (!empty($roomType->complexTariff)) :
										$currencyValue = $roomType->complexTariff->getValue();
									else :
										$currencyValue = $roomType->defaultTariff->getValue();
									endif;
									?>
									<select data-raid="<?php echo $this->item->id ?>"
											data-rtid="<?php echo $roomType->id ?>"
											class="span12 roomtype-quantity-selection quantity_<?php echo $roomType->id ?>">
										<option value="0"><?php echo JText::_('SR_ROOMTYPE_QUANTITY') ?></option>
										<?php
										for($i = 1; $i <= $roomType->totalAvailableRoom; $i ++) :
											$selected = ($i == $currentSelectedRoomTypeCount) ? 'selected="selected"': '';
											echo '<option '.$selected.' value="'.$i.'">'. $i . '</option>';
										endfor;
										?>
									</select>
									<div class="processing" style="display: none"></div>
								<?php
								endif;
							endif;
						endif; ?>
					</div>
				</div>
			</div>

			<div id="room-type-form-<?php echo $roomType->id ?>" class="room-type-form">
				<div class="row-fluid processing-placeholder"></div>
			</div>

			<!-- Room type details section -->
			<div class="row-fluid <?php echo $rowCSSClass ?> room_type_details hidden room_type_details_handler_<?php echo $roomType->id ?>">
				<div class="span12">
					<div class="inner">
						<?php
						if( !empty($roomType->media) ) :
							$count2 = 0;
							foreach ($roomType->media as $media) :
								if ($count2 != 0) :
									?>
									<a class="sr-photo-<?php echo $roomType->id ?>" href="<?php echo SRURI_MEDIA.'/assets/images/system/'.$media->value; ?>">
										<img src="<?php echo SRURI_MEDIA.'/assets/images/system/thumbnails/2/'.$media->value; ?>"
											 alt="<?php echo $media->name ?>"/>
									</a>
								<?php
								endif;
								$count2 ++;
							endforeach;
						endif;
						?>
						<p>
							<?php echo $roomType->description ?>
						</p>
						<ul>
							<?php
							if (!empty($roomType->roomtype_custom_fields['room_facilities'])) :
								echo '<li>'. JText::_('SR_ROOM_FACILITIES') .': '.  $roomType->roomtype_custom_fields['room_facilities'] .'</li>';
							endif;

							if (!empty($roomType->roomtype_custom_fields['room_size'])) :
								echo '<li>'. JText::_('SR_ROOM_SIZE') .': '.  $roomType->roomtype_custom_fields['room_size'] .'</li>';
							endif;

							if (!empty($roomType->roomtype_custom_fields['bed_size'])) :
								echo '<li>'. JText::_('SR_BED_SIZE') .': '.  $roomType->roomtype_custom_fields['bed_size'] .'</li>';
							endif;

							if (!empty($roomType->roomtype_custom_fields['taxes'])) :
								echo '<li>'. JText::_('SR_TAXES') .': '.  $roomType->roomtype_custom_fields['taxes'] .'</li>';
							endif;

							if (!empty($roomType->roomtype_custom_fields['prepayment'])) :
								echo '<li>'. JText::_('SR_PREPAYMENT') .': '.  $roomType->roomtype_custom_fields['prepayment'] .'</li>';
							endif;
							?>
						</ul>

						<?php if ($this->config->get('availability_calendar_enable', 1)) : ?>
							<div class="processing nodisplay"></div>
							<button type="button" data-roomtypeid="<?php echo $roomType->id ?>" class="btn load-calendar">
								<i class="icon-calendar"></i> <?php echo JText::_('SR_AVAILABILITY_CALENDAR_VIEW') ?>
							</button>
							<div id="availability-calendar-<?php echo $roomType->id ?>" class="availability-calendar"></div>
						<?php endif ?>
					</div>
				</div>
			</div>
			<!-- /Room type details section -->
			<?php
			$count ++;
		endforeach
		?>
	<?php endif ?>

	<?php if (isset($this->checkin) && $this->checkout) : ?>
		<div class="row-fluid button-row button-row-bottom">
			<div class="span9">
				<div class="inner">
					<p><?php echo JText::_('SR_ROOMINFO_STEP_NOTICE_MESSAGE') ?></p>
				</div>
			</div>
			<div class="span3">
				<div class="inner">
					<div class="btn-group">
						<button data-step="room" type="submit" class="btn btn-success">
							<i class="icon-arrow-right uk-icon-arrow-right fa-arrow-right"></i> <?php echo JText::_('SR_NEXT') ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>

	<input type="hidden" name="jform[customer_id]" value="" />
	<input type="hidden" name="jform[raid]" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="jform[state]" value="0" />
	<input type="hidden" name="jform[next_step]" value="guestinfo" />
	<input type="hidden" name="jform[bookingconditions]" value="<?php echo $this->item->params['termsofuse'] ?>" />
	<input type="hidden" name="jform[privacypolicy]" value="<?php echo $this->item->params['privacypolicy'] ?>" />

	<?php echo JHtml::_('form.token'); ?>
	</form>
	</div>
	<!-- /Tab 1 -->

	</div>

	<div class="step-pane" id="step2">
		<!-- Tab 2 -->
		<div class="reservation-single-step-holder guestinfo nodisplay">
		</div>
		<!-- /Tab 2 -->
	</div>

	<div class="step-pane" id="step3">
		<!-- Tab 3 -->
		<div class="reservation-single-step-holder confirmation nodisplay">
		</div>
		<!-- /Tab 3 -->
	</div>

</div>
