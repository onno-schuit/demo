var clone = Array();

function add_item(data, trigger, target) {
    $('#todotwo_new_item').attr('value','');
    $('#todotwo_new_item').focus();
    $(target).prepend(data);
    var total = parseInt($('#todotwo_total').text());
    $('#todotwo_total').text(total + 1);
}


function validate_item(trigger) {
    var valid = true;
    $(trigger).find('input[type=text]').each(function(index, input_element) {
      if ($.trim(input_element.value) == '') {
        return valid = false;
      }       
    });
    return valid;
}


function delete_item(data, trigger) {
    restore_original();
    $('#todotwo_new_item').focus();
    $(trigger).parents('li:first').remove();
    var total = parseInt($('#todotwo_total').text());
    $('#todotwo_total').text(total - 1);
  // body
}


function edit_item(data, trigger) {
    //console.log(data);
    restore_original();
    var id = $(trigger).parent().attr('id');
    clone[id] = $(trigger).parent().clone(true);
    $(trigger).parent().replaceWith(data);
    $('#' + id + ' input').focus();
}


function restore_original() {
    //if (clone.length == 0) return;
    for (var id in clone) {
      console.log('id = ' + id);
      console.log(clone[id]);
      $('#' + id).replaceWith(clone[id]);
    }
    clone = [];
}


function save_item(data, trigger) {
    clone = [];
    $('#todotwo_container').replaceWith(data);
    $('#todotwo_new_item').focus();
}


function toggle_note_for_item_id(item_id) {
    $('#todotwo_item_note_' + item_id).toggle();
}
