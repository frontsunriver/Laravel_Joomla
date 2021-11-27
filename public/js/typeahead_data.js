$('#demo4').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "/cities/list",
            data: 'query=' + query,            
            dataType: "json",
            type: "GET",
            success: function (data) {
                result($.map(data, function (item) {
                    return item;
                }));
            }
        });
    }
});

// $("#demo4").on('focus', function(){
//     $.ajax({
//         url: '/cities/list',
//         method: 'GET',
//         data: {
//             query: $("#demo4").val()
//         },
//         success: function(res){
//             $("#demo4").typeahead({
//                 source: function(){
//                     return res;
//                 }
//             });
//         }
//     });
// });

$('#demo5').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "/cities/list",
            data: 'query=' + query,            
            dataType: "json",
            type: "GET",
            success: function (data) {
                result($.map(data, function (item) {
                    return item;
                }));
            }
        });
    }
});

var checkStatus = function(label, val){
    var checkboxes = $("#"+label+ " :input");
    var result = '';
    for(var i = 0; i < checkboxes.length; i++){
        if(checkboxes[i].checked == true){
            result += checkboxes[i].defaultValue + ",";
        }
    }
    result = result.slice(0, result.length-1);
    if(label == 'Home-Amenity'){
        $("#amenity_val").val(result);
    }else if(label == 'Home-Type'){
        $("#hometype_val").val(result);
    }
}

var checkFacility = function(){
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

    $("#facility_val").val(JSON.stringify(makeJson));

}

var checkSearchFacility = function() {
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

    $('input[name="home-facilities"]').val(JSON.stringify(makeJson));
}