$(function() {
    $(document).ready(function() {
        var $elem = $('#list_provincias')
        var elem_loader = '#li_loader_list_prov'
        $.ajax({
            type: "POST",
            url: url_lp,
            dataType: 'json',
            success: function(data_json) {
                //console.log(data_json)
                $.each(data_json, function(k, prov) {
                    var $a = $('<a/>', {
                        href: prov.url
                    }).append('<i class="icon-barcode"></i>').append(prov.label)
                    var $li = $('<li/>').append($a)
                    if (slug_act === prov.slug) {
                        $li.attr('class', 'active')
                    }
                    $elem.append($li)
                })
                $(elem_loader).remove()
            },
            error: function() {
                console.log('errorrrrrrrrrrrr CONEXXION')
                $(elem_loader).empty()
                $(elem_loader).append('<h3>Error inesperado :(</h3>')
            },
            complete: function() {
            }
        })
    })
})