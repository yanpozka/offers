$(function() {
    var validarElem = function($elem) {
        var valor = $elem.val()
        if ($elem.attr('required') && valor == "")
            return false
        
        var expReg = $elem.attr('pattern')
        if (expReg && valor != "") {
            var s = new String(valor)
            if (s.search(expReg) >= 0)
                return true
            return false
        }
        return true
    }
    
    var actInputOK = function($elem) {
        var $div_parent = $elem.parents('div.control-group')

        if (validarElem($elem)) {
            $div_parent.removeClass('error')
            $div_parent.addClass('success')
            return true
        }
        else {
            $div_parent.removeClass('success')
            $div_parent.addClass('error')
            return false
        }
    }
    
    $(document).ready(function() {
        $('form input, form textarea, form select').change(function(event) {
            var $self = $(this)
            actInputOK($self)
        })
        
        $('form').submit(function(event) {
            console.error('Responde que es tu madre hija de la gran puta que te pario come basura, no debo seguir ofendiendo incluso a la laptop que me ha salvado mi vida y que tanto me ha dado claro si fuere por mi todo estaria muy very OK.')
            var ok = true
            $(this).find('input').each(function(_i, elem) {
                var $self = $(elem)
                return actInputOK($self)
            })
            if (!ok) {
                event.stopPropagation()
                return ok
            }
        })
    })
})
