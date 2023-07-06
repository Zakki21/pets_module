<h2><?php echo $heading_title; ?></h2>
<div class="row">
	<div class="list-group">
		<?php if (!empty($all_user_pets)) {
			foreach ($all_user_pets as $kp=>$pets) { ?>
				<div id="pet_id_<?php echo $pets['pets_id']; ?>" class="list-group-item"><?php echo $pets['pet'] . ', ' . $pets['breed'] . ', ' . $pets['age'] . $text_age; ?><button type="button" class="close" onclick="remove(<?php echo $pets['pets_id'];?>)" data-dismiss="alert">×</button></div>
		<?php }
		} ?>
	</div>
</div>
<?php
if($logged) {?>
	<div class="row">
		<form id="add_pets" class="form-horizontal">
			<fieldset>
				<h3><?php echo $heading_title_form; ?></h3>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="animal"><?php echo $entry_pet; ?></label>
					<div class="col-sm-10 animal">
						<select name="animal" id="animal" class="form-control">
							<option value="-1"><?php echo $text_select;?></option>
							<?php foreach ($all_animals as $animal) { ?>
								<option value="<?php echo $animal['animal_type']; ?>"><?php echo $animal['name']; ?></option>
							<?php } ?>
						</select>
						<!--<div class="text-danger error_pet"></div>-->
					</div>
				</div>
				<div class="form-group" style="display: none;">
					<label class="col-sm-2 control-label" for="breeds"><?php echo $entry_breed; ?></label>
					<div class="col-sm-10 breed">
						<select name="breed" id="breeds" class="form-control"></select>
					</div>
				</div>
				<div class="form-group" style="display: none;">
					<label class="col-sm-2 control-label" for="gender"><?php echo $entry_gender; ?></label>
					<div class="col-sm-10 gender">
						<select name="gender" id="gender" class="form-control"></select>
					</div>
				</div>
				<div class="form-group" style="display: none;">
					<label class="col-sm-2 control-label" for="input_age"><?php echo $entry_age; ?></label>
					<div class="col-sm-10 age">
						<input type="number" name="age" id="input_age" class="form-control" />
					</div>
				</div>
				<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
			</fieldset>
			<div class="buttons">
				<div class="pull-right">
					<input class="btn btn-primary" type="submit" id="submit_add_pets" value="<?php echo $button_submit; ?>" />
				</div>
			</div>
		</form>
	</div>

	<script>
		$(document).ready(function() {
			$('#add_pets select').on('change', function () {
				let selected_id = $(this).attr('id'),
					selected_value = $(this).val();

				$.ajax({
					url: 'index.php?route=module/pets/selected',
					type: 'post',
					data: 'selected_id=' + selected_id + '&selected_value=' + selected_value,
					dataType: 'json',
					success: function(json) {
						if(json['next_step_breeds_values'] && json['next_step_breeds']) {
							$('#'+json['next_step_breeds']).parent().parent().show();
							let html_breeds = '';
							for (ib in json['next_step_breeds_values']) {
								html_breeds += '<option value="' + json['next_step_breeds_values'][ib].breed_id + '">' + json['next_step_breeds_values'][ib].name + '</option>'
							}
							$('#'+json['next_step_breeds']).html(html_breeds);

							let html_gender = '';
							if(json['next_step_gender_values'].length > 0) {
								$('#gender').parent().parent().show();
								for (ig in json['next_step_gender_values']) {
									html_gender += '<option value="' + json['next_step_gender_values'][ig].gender_id + '">' + json['next_step_gender_values'][ig].name + '</option>'
								}
							} else {
								$('#gender').parent().parent().hide();
							}

							$('#gender').html(html_gender);
							$('#input_age').parent().parent().show();
						}
						if(json['next_step_breeds'] == false) {
							$('#breeds').html();
							$('#breeds').parent().parent().hide();

							$('#gender').html();
							$('#gender').parent().parent().hide();

							$('#input_age').parent().parent().hide();
						}

						$('.text-danger').remove();
					},
					error: function(json) {
						//console.log(json);
					}
				});

			});

			$('#submit_add_pets').on('click', function (e) {
				e.preventDefault();
				$('.text-danger').remove();
				$.ajax({
					url: 'index.php?route=module/pets/add',
					type: 'post',
					data: $('#add_pets select, #add_pets input[type=\'number\'], #add_pets input[type=\'hidden\']'),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							$('.list-group').prepend('<div data-id="' + json['added_pet']['pets_id'] + '" class="list-group-item">' + json['added_pet']['pet'] + ', ' + json['added_pet']['breed'] + ', ' + json['added_pet']['age'] + '<?php echo $text_age; ?><button type="button" class="close" onclick="remove(' + json['added_pet']['pets_id'] + ')" data-dismiss="alert">×</button> </div>');
							$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
							$('html, body').animate({ scrollTop: 0 }, 'slow');
						}
						if (json['error']) {
							for (ier in json['error']) {
								$('.'+ier).after('<div class="text-danger">' + json['error'][ier] + '</div>')
							}
						}
					},
					error: function(json) {
						//console.log(json);
					}
				});
			})
		});

		function remove(id) {
			$.ajax({
				url: 'index.php?route=module/pets/delete',
				type: 'post',
				data: 'pets_id=' + id,
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						$('#pet_id_'+json['pets_id']).remove();
					}
				},
				error: function(json) {
					//console.log(json);
				}
			});
		}
	</script>
<?php
}
?>
