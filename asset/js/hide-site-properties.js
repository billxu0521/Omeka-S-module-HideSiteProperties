(function ($) {
    $(document).ready(function() {
        $('#property-selector li.selector-child').on('click', function(e) {
            e.stopPropagation();
            //looks like a stopPropagation on the selector-parent forces
            //me to bind the event lower down the DOM, then work back
            //up to the li
            var targetLi = $(e.target).closest('li.selector-child');
            hideProperty(this);
        });

        
        $('#hide-site-properties-properties').on('click', '.remove-hidden-property', function (e) {
            e.preventDefault();
            $(this).closest('.hidden-property').remove();
        });

        console.log(hiddenProperties);
        hiddenProperties.forEach(initProperties);
        
    });

    function initProperties(property, index, array) {
        var propertyLi = $('#property-selector li.selector-child[data-property-term ="' + property + '"]');
        if (propertyLi.length !== 0) {
            hideProperty(propertyLi);
        }
    }
    
    function hideProperty(propertySelectorChild) {
        var term = $(propertySelectorChild).data('propertyTerm');
        var label = $(propertySelectorChild).data('childSearch');
        addToHiddenProperties(term, label);
    }
    
    function addToHiddenProperties(term, label) {
        var id = 'hidden-property-' + term;
        if (document.getElementById(id)) {
            return;
        }
        var hiddenPropertyRow = $('<div class="hidden-property row"></div>');
        hiddenPropertyRow.attr('id', id);
        hiddenPropertyRow.append($('<span>', {'class': 'property-label', 'text': label}));
        hiddenPropertyRow.append($('<ul class="actions"><li><a class="o-icon-delete remove-hidden-property" href="#"></a></li></ul>'));
        hiddenPropertyRow.append($('<input>', {'type': 'hidden', 'name': 'propertyLabel[]', 'value': term}));
        $('#hide-site-properties-properties').append(hiddenPropertyRow);
    }

})(jQuery);
