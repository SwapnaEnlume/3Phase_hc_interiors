<!-- PPSASCRUM-287: start -->
<?php

echo "<meta charset=\"utf-8\" /><h2>Add Commonly Used Global Note</h2>";

echo "<hr>";

echo "<div class=\"form-body\">";

echo $this->Form->create(false);

echo "<div class=\"global-note-selector\">";
echo $this->Form->input('common-global-note', ['label' => '', 'placeholder' => 'Type Global Note..', 'onchange' => 'onGlobalNoteSelectorChange(event);']);
echo $this->Form->button('⌵', ['type' => 'button', 'id' => 'toggle-multi-select-dropdown']);
echo "</div>";

echo "<div class=\"global-notes-selected-list-box\">
<ul id=\"global-notes-selected-list\"></ul>
</div>";

echo "<div class=\"input radio\" name=\"quote-pdf-display-check\"><h4>Should this note appear on Quote PDF?</h4>";
echo $this->Form->radio('appear_on_pdf', ['0' => 'No', '1' => 'Yes'], ['label' => 'Should this note appear on Quote PDF?', 'required' => true]);
echo "</div>";

echo "</div>";

echo "<br><br>";

echo $this->Form->submit('Submit', ['disabled' => true]);

echo "<button type=\"button\" id=\"cancelbutton\">Cancel</button>";

echo $this->Form->input('global-notes-selection', ['type' => 'text', 'label' => '']);

echo $this->Form->end();

?>

<div class="multi-select-dropdown">
	<table id="multi-select-table" width="100%">
		<thead>
			<th id="public">Public</th>
			<th id="internal">Internal Only</th>
			<th id="category"></th>
		</thead>
		<tbody></tbody>
	</table>
</div>


<div style="clear:both;"></div>

<style>
	body {
		font-family: 'Helvetica', Arial, sans-serif;
	}

	div.form-body {
		background-color: white;
		width: 100%;
		height: 490px;
	}

	div.global-note-selector {
		display: flex;
		flex-direction: row;
		width: 100%;
	}

	div.global-note-selector>button {
		justify-content: flex-end;
	}

	input[name=common-global-note] {
		height: 1.5rem;
		padding: 3px;
		width: 700px;
		line-height: 15px;
		font-size: 15px;
	}

	ul#ui-id-1 {
		width: 97%;
	}

	div[name=quote-pdf-display-check] {
		display: none;
		position: absolute;
		left: 10px;
		bottom: 70px;
	}

	form h4 {
		margin: 0;
		display: inline-block;
		font-size: 14px;
	}

	div.input.radio {
		margin: 20px 0;
	}

	div.input.radio input {
		margin-left: 15px;
	}

	div.submit>input {
		position: absolute;
		bottom: 5px;
		right: 10px;
	}

	div.submit>input[type=submit] {
		background: #26337A;
		color: #FFF;
		padding: 10px 15px;
		font-weight: bold;
		border: 1px solid #000;
		font-size: 14px;
		cursor: pointer;
	}

	div.submit>input[type=submit]:disabled,
	div.submit>input[type=submit][disabled] {
		cursor: not-allowed !important;
		background: #444 !important;
		color: #CCC !important;
	}

	#cancelbutton {
		position: absolute;
		bottom: 5px;
		height: 38px;
		width: 79.4453px;
		font-family: Arial;
		font-size: 14px;
		font-style: normal;
		font-stretch: 100%;
		line-height: normal;
	}

	input#global-notes-selection {
		display: none;
	}

	div.multi-select-dropdown {
		display: none;
		width: 98%;
		height: auto;
		background-color: white;
		position: absolute;
		top: 112px;
		border: solid #000;
		overflow-x: hidden;
		overflow-y: scroll;
		max-height: 70%;
		z-index: 1;
	}

	table#multi-select-table {
		border: 1px solid #000;
		border-collapse: collapse;
	}

	table#multi-select-table th#public,
	th#internal {
		width: 0px;
		border-spacing: 10px;
		padding: 10px;
		align-content: flex-end;
		font-weight: normal;
	}

	table#multi-select-table th#category {
		align-content: flex-end;
	}

	td#public_check,
	td#internal_check {
		align-content: flex-start;
	}

	table th#category {
		text-align: left;
	}
</style>

<script>
	var selectedGlobalNote;
	var multiSelectedGlobalNotes = {};

	$(function() {
		$('input[name=common-global-note]').autocomplete({
			source: function(request, response) {
				$.ajax({
					'url': '/quotes/searchcommonglobalnotes/' + request.term,
					'dataType': 'json',
					success: function(data) {
						response(data);
					}
				});
			},
			minLength: 2,
			select: function(event, ui) {
				let commonGlobalNoteSelected = ui.item.value;
				if (commonGlobalNoteSelected.toString().trim().length > 0) {
					selectedGlobalNote = commonGlobalNoteSelected;
					$('div[name=quote-pdf-display-check]').css('display', 'block');
				} else {
					$('div[name=quote-pdf-display-check]').css('display', 'none');
				}
			}
		});

		$('input#appear-on-pdf-0,input#appear-on-pdf-1').change(function() {
			if (($('div[name=quote-pdf-display-check]').is(':visible') &&
					($('input#appear-on-pdf-0').is(':checked') || $('input#appear-on-pdf-1').is(':checked'))) ||
				Object.keys(multiSelectedGlobalNotes).length !== 0) {
				$('div.submit > input[type=submit]').prop('disabled', false);
				let displayStatus;
				if ($('input#appear-on-pdf-1').is(':checked')) {
					displayStatus = "public_check";
					if (Object.keys(multiSelectedGlobalNotes).includes('internal_check')) {
						delete multiSelectedGlobalNotes['internal_check'];
						// TODO: remove internal check from UL
					}
				}
				if ($('input#appear-on-pdf-0').is(':checked')) {
					displayStatus = "internal_check";
					if (Object.keys(multiSelectedGlobalNotes).includes('public_check')) {
						delete multiSelectedGlobalNotes['public_check'];
						// TODO: remove public check from UL
					}
				}
				multiSelectedGlobalNotes[displayStatus] = $('input[name=common-global-note]').val();
				// TODO: add displayStatus to UL
				console.log('UL content:', $('ul#global-notes-selected-list'));
			} else {
				$('div.submit > input[type=submit]').prop('disabled', true);
				delete multiSelectedGlobalNotes['public_check'];
				delete multiSelectedGlobalNotes['internal_check'];
				// TODO: remove internal check from UL
				// TODO: remove public check from UL
			}
			console.log('search-mode:', multiSelectedGlobalNotes);
			$('input#global-notes-selection').val(JSON.stringify(multiSelectedGlobalNotes));
		});

		$('#cancelbutton').click(function() {
			parent.$.fancybox.close();
		});

		$('form').submit(function() {
			$('div.submit input[type=submit]').prop('disabled', true).val('Processing...');
		});

		$('button#toggle-multi-select-dropdown').click(function() {
			if (!$('div.multi-select-dropdown').is(':visible')) {
				// $('div.submit > input[type=submit]').prop('disabled', true);
				$('div.multi-select-dropdown').css('display', 'block');
				$('input[name=common-global-note]').prop('disabled', true);
			} else {
				if (($('div[name=quote-pdf-display-check]').is(':visible') &&
						($('input#appear-on-pdf-0').is(':checked') || $('input#appear-on-pdf-1').is(':checked'))) ||
					Object.keys(multiSelectedGlobalNotes).length !== 0) {
					// $('div.submit > input[type=submit]').prop('disabled', false);
				}
				$('div.multi-select-dropdown').css('display', 'none');
				$('input[name=common-global-note]').prop('disabled', false);
			}
		});

		$(window).on('click', function(event) {
			if ($('div.multi-select-dropdown').is(':visible') && !$(event.target).closest(
					'button#toggle-multi-select-dropdown').length && !$(event.target).closest(
					'div.multi-select-dropdown').length) {
				if (($('div[name=quote-pdf-display-check]').is(':visible') &&
						($('input#appear-on-pdf-0').is(':checked') || $('input#appear-on-pdf-1').is(':checked'))) ||
					Object.keys(multiSelectedGlobalNotes).length !== 0) {
					$('div.submit > input[type=submit]').prop('disabled', false);
				}
				$('div.multi-select-dropdown').css('display', 'none');
				$('input[name=common-global-note]').prop('disabled', false);
			}
		});

		let categorizedGlobalNotes = <?php echo json_encode($categorizedGlobalNotes); ?>;

		const globalNoteCategories = Object.keys(categorizedGlobalNotes).sort((x, y) => x.localeCompare(y));

		let index = 0;

		for (let globalNoteCategory of globalNoteCategories) {
			let commonGlobalNotes = categorizedGlobalNotes[globalNoteCategory].sort((x, y) => x.localeCompare(y));
			if (index == 0) {
				$("table th#category").text(globalNoteCategory.toUpperCase());
				index++;
			} else {
				let categoryRow =
					"<tr><td align=\"center\"></td><td align=\"center\"></td><td><b>" +
					globalNoteCategory.toUpperCase() + "</b></td></tr>";
				$("table tbody").append(categoryRow);
			}
			let commonGlobalNotesRow = "";
			for (let commonGlobalNote of commonGlobalNotes) {
				commonGlobalNotesRow +=
					"<tr id=\"" + index + "\"><td id=\"public_check\" align=\"center\"><input type=\"checkbox\" /></td><td id=\"internal_check\" align=\"center\"><input type=\"checkbox\" /></td><td id=\"global-note-comment\">" +
					commonGlobalNote + "</td></tr> ";
				index++;
			}
			$("table tbody").append(commonGlobalNotesRow);
			$("table tbody").append(
				"<tr><td></td><td></td><td></td></tr> "
				.repeat(5));
		}

		$('table input[type=checkbox]').change(function(event) {
			const globalNotesRow = event.target.parentElement.parentElement;
			const globalNoteCheckboxes = Array.from(globalNotesRow.children).slice(0, 2);
			const globalNoteComment = $(Array.from(globalNotesRow.children)[2]).text();
			if ($(event.target).is(':checked')) {
				for (let checkboxParent of globalNoteCheckboxes) {
					if (event.target != checkboxParent.children[0]) {
						$(checkboxParent.children[0]).attr('checked', false);
						delete multiSelectedGlobalNotes[$(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id')];
						// TODO: remove $(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id') from UL
						$('ul#global-notes-selected-list').find("#" + $(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id')).remove();
					} else {
						multiSelectedGlobalNotes[$(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id')] = globalNoteComment;
						// TODO: add $(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id') to UL
						console.log('current key:', $(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id'));
						let visibility = $(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id');
						if (visibility.includes('public')) {
							$('ul#global-notes-selected-list').append(`<li style="margin-bottom: 15px;" id=${$(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id')}>${"<b>[PUBLIC]</b> " + globalNoteComment}</li>`);
						} else if (visibility.includes('internal')) {
							$('ul#global-notes-selected-list').append(`<li style="margin-bottom: 15px;" id=${$(event.target).parent().attr('id') + '-' + $(checkboxParent).parent().attr('id')}>${"<b>[INTERNAL]</b> " + globalNoteComment}</li>`);
						}
					}
				}
			} else {
				if (!globalNoteCheckboxes.map((checkboxParent) => checkboxParent.children[0]).filter((checkbox) => $(checkbox).is(':checked')).length) {
					globalNoteCheckboxes.forEach((checkboxParent) => {
						delete multiSelectedGlobalNotes[$(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id')];
						// TODO: remove $(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id') from UL
						$('ul#global-notes-selected-list').find("#" + $(checkboxParent).attr('id') + '-' + $(checkboxParent).parent().attr('id')).remove();
					});
				}
			}
			if (($('div[name=quote-pdf-display-check]').is(':visible') &&
					($('input#appear-on-pdf-0').is(':checked') || $('input#appear-on-pdf-1').is(':checked'))) ||
				Object.keys(multiSelectedGlobalNotes).length !== 0) {
				$('input[type=radio][name=appear_on_pdf]').prop('required',false);
				$('div.submit > input[type=submit]').prop('disabled', false);
			} else {
				$('input[type=radio][name=appear_on_pdf]').prop('required',true);
				$('div.submit > input[type=submit]').prop('disabled', true);
				delete multiSelectedGlobalNotes['public_check'];
				delete multiSelectedGlobalNotes['internal_check'];
				// TODO: remove public check from UL
				$('ul#global-notes-selected-list').find("#public_check").remove();
				// TODO: remove internal check from UL
				$('ul#global-notes-selected-list').find("#internal_check").remove();
			}
			console.log('multi-select-mode:', multiSelectedGlobalNotes);
			$('input#global-notes-selection').val(JSON.stringify(multiSelectedGlobalNotes));
		});
	});

	function onGlobalNoteSelectorChange(event) {
		let commonGlobalNoteSelected = event.target.value;
		if (selectedGlobalNote && selectedGlobalNote === commonGlobalNoteSelected) {
			$('div[name=quote-pdf-display-check]').css('display', 'block');
		} else {
			$('div[name=quote-pdf-display-check]').css('display', 'none');
			delete multiSelectedGlobalNotes['public_check'];
			delete multiSelectedGlobalNotes['internal_check'];
		}
		$('input#global-notes-selection').val(JSON.stringify(multiSelectedGlobalNotes));
	}
</script>
<!-- PPSASCRUM-287: end -->