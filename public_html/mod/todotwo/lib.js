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
    $(trigger).find('.todotwo_item_title input[type=text]').each(function(index, input_element) {
      if ($.trim(input_element.value) == '') {
        //console.log("NOT valid");
        return valid = false;
      }       
    });
    //console.log("valid!");
    return valid;
}


function delete_item(data, trigger) {
    $('#todotwo_container').replaceWith(data);
    return;
    restore_original();
    $('#todotwo_new_item').focus();
    $(trigger).parents('li:first').remove();
    $('#scripts').append(data);
  // body
}


function decrease_counter() {
    var total = parseInt($('#todotwo_total').text());
    $('#todotwo_total').text(total - 1);
}


function edit_item(data, trigger) {
    //console.log(data);
    $(trigger).parent().append(data);
    return;
    restore_original();
    var id = $(trigger).parent().attr('id');
    clone[id] = $(trigger).parent().clone(true);
    $(trigger).parent().replaceWith(data);
    //$('#' + id + ' input').focus();
}


function restore_original() {
    for (var id in clone) {
      $('#' + id).replaceWith(clone[id]);
    }
    clone = [];
}


function save_item(data) {
    clone = [];
    $('#todotwo_container').replaceWith(data);
}


function toggle_note_for_item_id(item_id, trigger) {
    $('#todotwo_item_note_' + item_id).toggle();
    $(trigger).find('span').toggleClass('todotwo_note_collapse');
}


function submit_item(trigger) {    
    var form = $(trigger).parents('form:first');
    form.submit();
    form.dialog('close');
    return false;
}
