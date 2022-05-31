
<div id="ehrepeat" class="sb-box">
	<h4><?php echo __('Repeatable'); ?></h4>
	<?php echo form::checkbox('event_repeatable',1,$event_repeatable,'',3).' '.__('Repeatable event'); ?>
	<fieldset title ="<?php echo __('Click to edit repeat'); ?>">
		<?php echo form::hidden('rpt_freq',$rpt_freq); ?>
		<?php echo form::hidden('rpt_id',$rpt_id); ?>
		<label id="rpt_freq_desc"><?php echo $rpt_freq_desc; ?></label>
		<ul id="rpt_dates" > </ul>
		<?php echo form::hidden('rpt_dates_num',$core->blog->settings->ehRepeat->rpt_duration) ?>
	</fieldset>
</div>
<div id="ehrepeatmodal">
	<div>
		<fieldset class="ehr_line">
			<label><?php echo __('Every'); ?></label>
			<select id="ehr_wom" size="8" multiple="multiple">
				<option value="*"><?php echo __('All');?></option>
				<option value="1"><?php echo __('1st');?></option>
				<option value="2"><?php echo __('2nd');?></option>
				<option value="3"><?php echo __('3rd');?></option>
				<option value="4"><?php echo __('4th');?></option>
				<option value="5"><?php echo __('5th');?></option>
			</select>
			<select id="ehr_wday" size="8" multiple="multiple">
				<option value="*"><?php echo __('All');?></option>
				<option value="1"><?php echo __('Monday');?></option>
				<option value="2"><?php echo __('Tuesday');?></option>
				<option value="3"><?php echo __('Wednesday');?></option>
				<option value="4"><?php echo __('Thursday');?></option>
				<option value="5"><?php echo __('Friday');?></option>
				<option value="6"><?php echo __('Saturday');?></option>
				<option value="7"><?php echo __('Sunday');?></option>
			</select>
			<label><?php echo __('of the month');?></label>
			<button type="button" id="ehr_validate"><?php echo __('Ok');?></button>
			<button type="button" id="ehr_reset"><?php echo __('Reset');?></button><br/>
			<label class="maximal"><?php echo __('Use CTRL key to select several items in lists');?></label>
		</fieldset>
	</div>
</div>