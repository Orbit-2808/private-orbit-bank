// function
function getDatas(urlReference) {
    let datas;
    $.ajax({
        url: urlReference,
        type: 'GET',
        datatype: 'json',
        success: function(ajax_result) {
            datas = ajax_result;            
        }
    });
    return datas;
}

// main
// run when document ready
$(document).ready(function() {
    var parentURL = window.parent.location.href;
    console.log(parentURL);

    $('#kabupaten').hide();
    $('#kecamatan').hide();
    $('#kelurahan').hide();
    $('#jalan').hide();

    $('#provinsi').append('<option value="">Pilih</option>');
    provinces = getDatas(parentURL + '/provinces.php');
    for (let i = 0; i < provinces.length; i++) {
        $('#provinsi').append('<option value="' + provinces[i].id + '">' + provinces[i].name + '</option>');
    }

    $('#provinsi').change(function () {
        $('#kabupaten').slideDown();
        let prov_id = $('#provinsi').val();

        $("#kabupaten").html('');
        let regency = getDatas(parentURL + 'regencies.php');
        for (let i = 0; i < provinces.length; i++) {
            $('#provinsi').append('<option value="' + provinces[i].id + '">' + provinces[i].name + '</option>');
        }
    });
});