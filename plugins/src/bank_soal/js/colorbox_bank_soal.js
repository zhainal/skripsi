$(document).ready(function(){
    $(".iframe-copy-soal-tugas").colorbox({
        iframe:true,
        width:"900",
        height:"600",
        fixed:true,
        overlayClose: false,
        onClosed : function() {
            location.reload();
        }
    });
});
