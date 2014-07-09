/**
 * Creado por Yandry Pozo <ypozoka@gamil.com>
 * Listado con paginación vía Ajax
 * Se requiere Twitter-Bootstrap 2 para una mejor visualización
 **/

(function($)
{
    $.fn.listWithPagination = function(options, callback)
    {
        var $this = $(this).empty() // !!
        if (typeof (options) != 'object')
            return

        options = $.extend($.fn.listWithPagination.defaults, options);

        addErrors = function(errors) {
            var $ul = $('<ul/>')
            $.each(errors, function(k, error) {
                $ul.append($('<li/>').html(error))
            })
            $(options.error_div)
                    .empty()
                    .append($ul)
                    .show('slow')
                    .fadeIn('slow')
                    .removeClass('hidden')
        }
        createPagination = function(start, end, page) {
            var $ul = $('<ul>')
            for (var i = start; i <= end; i++) {
                var $li = $('<li/>').append($('<a/>', {'href': '#'}).html(i))
                        .data('index', i)
                if (i == page) {
                    $li.addClass('active')
                }
                $li.click(function(e) {
                    var $self = $(this)
                    if ($self.hasClass('active')) {
                        return false
                    }
                    options.page = parseInt($self.data('index'))
                    eachAction()
                    return false
                })
                $ul.append($li)
            }

            $this.append($('<div/>', {
                'class': 'pagination'
            })
            .append($ul))
        }
        send = function($self) {
            // if there aren't form don't find nothing
            $(options.form_search).find('input').each(function(k, input) {
                var $self = $(input)
                if ($self.attr('name'))
                    options.data[$self.attr('name')] = $self.val()
            })
            options.data['page'] = options.page // !! the pagination

            $.ajax({
                type: "POST",
                url: options.url,
                data: options.data,
                dataType: 'json',
                success: function(data_json) {
                    $self.empty()
                    $(options.error_div).hide('normal').empty()

                    if (data_json.errors.length > 0) {
                        addErrors(data_json.errors)
                        return
                    }

                    $.each(data_json.list, function(k, valor) {
                        var $a = $('<a/>', {
                            href: valor.url
                        }).append(valor.label)

                        var $li = $('<li/>').append($a)
                        $li.hide()
                        $self.append($li)
                        $li.fadeIn('slow')
                    })
                    createPagination(data_json['start_index'], data_json['end_index'], data_json['page'])
                },
                error: function() {
                    $self.empty()
                    console.log(options.msj_error)
                    addErrors([options.msj_error])
                },
                complete: function() {
                    $.isFunction(callback) && callback()
                }
            })
        }
        eachAction = function() {
            $this.empty()

            var $ulist = $('<ul/>', {
                'class': "nav nav-tabs nav-stacked listado_ofertas"
            })
            if (options.url_img_load != '') {
                $ulist.append($('<li/>').append($('<img />', {
                    'src': options.url_img_load
                })).css('text-align', 'center'))
            }
            else {
                $ulist.append($('<li/>').append("Loading ...") )
            }

            $this.append($ulist)
            send($ulist)
        }
        return this.each(function() {
            eachAction()
        });
    }

    $.fn.listWithPagination.defaults = {
        'data': {},
        'url': '',
        'url_img_load': '',
        'error_div': '',
        'form_search': '',
        'page': 0,
        'msj_error': 'Error on conexion. Please try again.'
    };
})(jQuery);