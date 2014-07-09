$(function() {
    $(document).ready(function() {
        load_elem_listados(
                '#ul_timas_ofertas',
                URL_ULTIMAS_OFERTAS,
                '#li_loader_a_borrar', 'icon-chevron-right')
    })
})

function load_elem_listados(tag, url, elem_loader, icon_class, data) {
    if (data === undefined)
        data = {}
    var $elem = $(tag)
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: data,
        success: function(data_json) {
            //console.log(data_json)
            $.each(data_json, function(k, valor) { //.html('<i class="icon-chevron-right"></i> ' + valor.label)
                var $a = $('<a/>', {
                    href: valor.url
                }).append('<i class="' + icon_class + '"></i>').append(valor.label)

                $elem.append($('<li/>').append($a))
            })
            $(elem_loader).remove()
        },
        error: function() {
            console.log('errorrrrrr')
            $(elem_loader).empty()
            $(elem_loader).append('<h3>Error de coneccion :(</h3>')
        },
        complete: function() {
        }
    })
}