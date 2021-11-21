var updateSubname = function(){
    var current = $('#currentNum_update').val();
    var key = Number(current) + 1;
    var html = '<input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation" id="sub_name_update_'+key+'" name="sub_name_update_'+key+'" placeholder="">';
    $('#subnameGroup_update').append(html);
    $('#currentNum_update').val(key);
}

var addSubname = function(){
    var current = $('#currentNum').val();
    var key = Number(current) + 1;
    var html = '<input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation" id="sub_name_add_'+key+'" name="sub_name_add_'+key+'" placeholder="">';
    $('#subnameGroup_add').append(html);
    $('#currentNum').val(key);
}

var showFacilitiesModal = function() {
    $('#currentNum').val(1);
    var html = `<a href="javascript:addSubname();" class="btn btn-info float-right mb-3">Add</a>
    <input type="hidden" id="currentNum" name="currentNum" value="1">
    <input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation"
        id="sub_name_add_1" name="sub_name_add_1"
        placeholder="">`;
    $('#subnameGroup_add').html(html);
    $("#hh-add-new-term-modal").modal('show');
}

$('input[type=radio][name=type_of_bulk]').change(function() {
    if (this.value == 'days_of_custom') {
        $("#setting-month_bulk").hide();
        $("#setting-year_bulk").hide();
        $("#bulk_price").hide();
    }else{
        $("#setting-month_bulk").show();
        $("#setting-year_bulk").show();
        $("#bulk_price").show();
    }
})