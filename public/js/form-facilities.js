var updateSubname = function(){
    var current = $('#currentNum_update').val();
    var key = Number(current) + 1;
    var html = '<input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation" id="sub_name_update_'+key+'" name="sub_name_update_'+key+'" placeholder="Value name">';
    $('#subnameGroup_update').append(html);
    $('#currentNum_update').val(key);
}

var addSubname = function(){
    var current = $('#currentNum').val();
    var key = Number(current) + 1;
    var html = '<input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation" id="sub_name_add_'+key+'" name="sub_name_add_'+key+'" placeholder="Value name">';
    $('#subnameGroup_add').append(html);
    $('#currentNum').val(key);
}

var showFacilitiesModal = function() {
    $('#currentNum').val(1);
    var html = `
    <input type="hidden" id="currentNum" name="currentNum" value="1">
    <input type="text" class="form-control hh-icon-input mb-3 has-validation has-translation"
        id="sub_name_add_1" name="sub_name_add_1"
        placeholder="Value name">`;
    $('#subnameGroup_add').html(html);
    $("#hh-add-new-term-modal").modal('show');
}

$('input[type=radio][name=type_of_bulk]').change(function() {
    if (this.value == 'days_of_custom' || this.value == 'days_of_discount') {
        $("#setting-month_bulk").hide();
        $("#setting-year_bulk").hide();
        $("#bulk_price").hide();
    }else{
        $("#setting-month_bulk").show();
        $("#setting-year_bulk").show();
        $("#bulk_price").show();
    }

    if(this.value == 'days_of_discount') {
        $("#bulk_first_minute").show();
        $("#bulk_last_minute").show();
    }else {
        $("#bulk_first_minute").hide();
        $("#bulk_last_minute").hide();
    }
});

var checkFacility = function(val){
    var checkboxes = $("#home-facilities :checked");
    var tmpName = "";
    var makeJson = {};
    var tmpArray = new Array();
    for(var i = 0; i < checkboxes.length; i++){
        if(checkboxes[i].checked){
            var id = checkboxes[i].id;
            var f_name = id.split('_');
            f_name = f_name[0].replace("-", " ");
            if(tmpName != f_name) {
                if(tmpName != ""){
                    makeJson[tmpName] = tmpArray;
                }
                tmpName = f_name;
                tmpArray = new Array();
            }
            tmpArray.push(checkboxes[i].defaultValue);
        }
    }
    makeJson[tmpName] = tmpArray;
    console.log(makeJson);

    if(val == 'add'){
        $("#facility_val").val(JSON.stringify(makeJson));
    }else {
        $("#facility_val_1").val(JSON.stringify(makeJson));
    }
    

}