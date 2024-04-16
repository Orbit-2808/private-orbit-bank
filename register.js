// run when document ready
$(document).ready(function() {
    // hide input
    $('#lbl_kabupaten').hide();
    $('#register_kabupaten').hide();
    $('#lbl_kecamatan').hide();
    $('#register_kecamatan').hide();
    $('#lbl_kelurahan').hide();
    $('#register_kelurahan').hide();

    // append provinces
    $('#register_provinsi').append('<option value="">Pilih</option>');
    $.ajax({
        url: 'provinces.php',
        type: 'GET',
        datatype: 'json',
        success: function(response) {
            console.log(response);
            for (let i = 0; i < response.length; i++) {
                $('#register_provinsi').append('<option value="' + response[i].province_id + '">' + response[i].province_name + '</option>');
            }
        }
    });

    // when province get change val, change regency selection
    $('#register_provinsi').change(function () {
        $('#lbl_kabupaten').slideDown();
        $('#register_kabupaten').slideDown();
        $('#lbl_kecamatan').slideDown();
        $('#register_kecamatan').slideDown();
        $('#lbl_kelurahan').slideDown();
        $('#register_kelurahan').slideDown();

        let province_id = $('#register_provinsi').val();

        $("#register_kabupaten").html('');
        $.ajax({
            url: 'regencies.php?province_id=' + province_id,
            type: 'GET',
            datatype: 'json',
            success: function(response) {
                for (let i = 0; i < response.length; i++) {
                    $('#register_kabupaten').append('<option value="' + response[i].regency_id + '">' + response[i].regency_name + '</option>');
                }
            }
        });
    });
});