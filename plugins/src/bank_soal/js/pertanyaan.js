$(".btn-tambah-pertanyaan").on('click', function() {
    $(this).attr('disabled', 'disabled');
    $(".disable-on-add-pertanyaan").attr('disabled', 'disabled');
    var field = '<div class="box-area-pertanyaan">';
    field += '<div class="row-fluid">';
    field += '<div class="span12"><p><a class="pull-right btn btn-small btn-default" href="javascript:void(0)" onclick="remove_box_pertanyaan()"><i class="icon icon-remove-sign"></i> Batal</a><b>Pertanyaan</b></p><textarea class="texteditor" name="pertanyaan" id="textarea-pertanyaan"></textarea><br></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><div class="box-pilihan"><p><b>Pilihan A</b><label class="radio inline pull-right"><input type="radio" name="kunci" value="a" checked><b>Jadikan Kunci</b></label></p><textarea class="texteditor" name="pilihan[a]" id="textarea-pilihan-a"></textarea></div></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><div class="box-pilihan"><p><b>Pilihan B</b><label class="radio inline pull-right"><input type="radio" name="kunci" value="b"><b>Jadikan Kunci</b></label></p><textarea class="texteditor" name="pilihan[b]" id="textarea-pilihan-b"></textarea></div></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><div class="box-pilihan"><p><b>Pilihan C</b><label class="radio inline pull-right"><input type="radio" name="kunci" value="c"><b>Jadikan Kunci</b></label></p><textarea class="texteditor" name="pilihan[c]" id="textarea-pilihan-c"></textarea></div></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><div class="box-pilihan"><p><b>Pilihan D</b><label class="radio inline pull-right"><input type="radio" name="kunci" value="d"><b>Jadikan Kunci</b></label></p><textarea class="texteditor" name="pilihan[d]" id="textarea-pilihan-d"></textarea></div></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><div class="box-pilihan"><p><b>Pilihan E</b><label class="radio inline pull-right"><input type="radio" name="kunci" value="e"><b>Jadikan Kunci</b></label></p><textarea class="texteditor" name="pilihan[e]" id="textarea-pilihan-e"></textarea></div></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><br><a class="pull-right btn btn-small btn-default" href="javascript:void(0)" onclick="remove_box_pertanyaan()"><i class="icon icon-remove-sign"></i> Batal</a><button class="btn btn-primary">Simpan Pertanyaan</button></div>';
    field += '</div>';
    field += '</div>';

    $(".box-new-soal").append(field);

    load_texteditor();
});

$(".btn-tambah-pertanyaan-essay").on('click', function() {
    $(this).attr('disabled', 'disabled');
    $(".disable-on-add-pertanyaan").attr('disabled', 'disabled');
    var field = '<div class="box-area-pertanyaan">';
    field += '<div class="row-fluid">';
    field += '<div class="span12"><p><a class="pull-right btn btn-small btn-default" href="javascript:void(0)" onclick="remove_box_pertanyaan()"><i class="icon icon-remove-sign"></i> Batal</a><b>Pertanyaan</b></p><textarea class="texteditor" name="pertanyaan" id="textarea-pertanyaan"></textarea></div>';
    field += '</div>';

    field += '<div class="row-fluid">';
    field += '<div class="span12"><br><button class="btn btn-primary">Simpan Pertanyaan</button></div>';
    field += '</div>';
    field += '</div>';

    $(".box-new-soal").append(field);

    load_texteditor();
});

$("#form-tambah-pertanyaan").submit(function(event) {
    var content = CKEDITOR.instances['textarea-pertanyaan'].getData();
    if (content == '') {
        event.preventDefault();
        alert("Pertanyaan tidak boleh kosong!");
    } else {
        $(".disable-on-add-pertanyaan").removeAttr('disabled');
    }
});

function remove_box_pertanyaan() {
    conf = confirm("Anda yakin ingin membatalkan?");
    if (conf) {
        $(".disable-on-add-pertanyaan").removeAttr("disabled");
        $(".box-new-soal").html("");
    }
}
