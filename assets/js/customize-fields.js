/*
 * Customize fields js functions  
 * 
 * @author vutuan.sw
 * @since 1.0.0
 */

jQuery(function ($) {

    $('.customize-control .quickfield-checkboxes input[type="checkbox"]').on(
            'change',
            function () {

                var checkbox_values = $(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(
                        function () {
                            return this.value;
                        }
                ).get().join(',');

                $(this).parents('.customize-control').find('input.qf-holder_value').val(checkbox_values).trigger('change');
            }
    );


    if (document.getElementsByClassName('quickfield-icon_picker').length) {
        $('.customize-control .quickfield-icon_picker select').fontIconPicker();
    }

});