
function update_ganda_custom(tugas_id, pertanyaan_id, pilihan_id) {
    $.ajax({
        type : "POST",
        url  : site_url + "/ajax/post_data/update_jawaban_ganda",
        data : "tugas_id=" + tugas_id + "&pertanyaan_id=" + pertanyaan_id + "&pilihan_id=" + pilihan_id,
        success: function(data) {
            $("#no-pertanyaan-" + pertanyaan_id).addClass("success");

            if ($("#ragu-" + pertanyaan_id).length == 0) {
                /* tambah checkbox ragu */
                $("#pilihan-" + pertanyaan_id + " table tr:last").after('<tr id="ragu-' + pertanyaan_id + '" class="warning"><td colspan="2"><label class="checkbox"><input type="checkbox" name="ragu" onclick="update_ragu(this.checked, ' + tugas_id + ', ' + pertanyaan_id + ')"> <b>Masih ragu-ragu</b></label></td></tr>');
            }
        },
        async: false
    });
}

function update_ragu(is_checked, tugas_id, pertanyaan_id) {
    if (is_checked) {
        $.ajax({
            type : "POST",
            url  : site_url + "/plugins/custom_tugas/ajax/update_ragu",
            data : "act=add&tugas_id=" + tugas_id + "&pertanyaan_id=" + pertanyaan_id,
            success: function(data) {
                $("#no-pertanyaan-" + pertanyaan_id).removeClass("success");
                $("#no-pertanyaan-" + pertanyaan_id).addClass("ragu");
            },
            async: false
        });
    } else {
        $.ajax({
            type : "POST",
            url  : site_url + "/plugins/custom_tugas/ajax/update_ragu",
            data : "act=remove&tugas_id=" + tugas_id + "&pertanyaan_id=" + pertanyaan_id,
            success: function(data) {
                $("#no-pertanyaan-" + pertanyaan_id).removeClass("ragu");
                $("#no-pertanyaan-" + pertanyaan_id).addClass("success");
            },
            async: false
        });
    }
}

$(".pagination > ul > li > a").on('click', function(event) {
    event.preventDefault();

    $("#info-ajax-href").html("<center><span class='text-info'>Proses menyimpan jawaban...</span></center><br>");

    var result = simpanJawaban($("#tugas_id").val());
    if (result) {
        window.location.href = $(this).attr("href");
    }
});
