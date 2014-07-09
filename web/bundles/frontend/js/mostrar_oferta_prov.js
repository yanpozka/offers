$(function() {
    $(document).ready(function()
    {
        var opt = {
            'url': URL_GET_OFERTAS_PROV,
            'data': {
                'slug': SLUG_ACTUAL
            },
            'url_img_load': URL_IMG_LOAD,
            'form_search': '#form_buscar',
            'error_div': '#area_error',
            'page': 1,
            'msj_error': 'Ha introducido datos incorrectos.'
        }
        
        $('#boton_buscar').click(function(e) {
            var $self = $(this)
            $self.attr('disabled', 'disabled')
            $('#listado').listWithPagination(opt, function() {
                $self.removeAttr('disabled')
            })
            return false
        })
        .click()
    })
})