/*
 * Core Fields js functions  
 * 
 * @author vutuan.sw
 * @since 1.0.0
 */
jQuery(function ($) {

    /**
     * Field Color
     */
    if ($('.quickfield-color:not(.child-field)').length) {
        $('.quickfield-color:not(.child-field)').wpColorPicker();
    }

    if (document.getElementsByClassName('quickfield-image_picker').length) {
        $('.quickfield-image_picker').qfImagePicker();
    }

    if ($('.quickfield-icon_picker:not(.child-field)').length) {
        $('.quickfield-icon_picker:not(.child-field) select').fontIconPicker();
    }

    if (document.getElementsByClassName('quickfield-link').length) {
        $('.quickfield-link').qfLink();
    }

    if (window.hasOwnProperty('pagenow') && pagenow != 'widgets' && document.getElementsByClassName('quickfield-map').length) {
        $('.quickfield-map').qfMap();
    }

    if (window.hasOwnProperty('pagenow') && pagenow != 'widgets' && document.getElementsByClassName('quickfield-repeater').length) {
        $('.quickfield-repeater').qfRepeater();
    }

    if ($('.quickfield-select-multiple').length) {

        $('.quickfield-select-multiple:not(.child-field)').selectize({
            plugins: ['remove_button', 'drag_drop']
        });

        $(document).on('change', '.quickfield-select-multiple', function () {
            $(this).closest('div').find('.qf_value').val($(this).val()).trigger('change');
        });
    }

    if ($('.quickfield-checkboxes').length) {
        $(document).on(
                'change', '.quickfield-checkboxes input[type="checkbox"]',
                function () {

                    var checkbox_values = $(this).closest('ul').find('input[type="checkbox"]:checked').map(
                            function () {
                                return this.value;
                            }
                    ).get().join(',');

                    $(this).closest('ul').prev('input.qf_value').val(checkbox_values).trigger('change');
                }
        );
    }

    if (document.getElementsByClassName('quickfield-datetime').length) {
        $('.quickfield-datetime input').each(function () {
            var data = $(this).data();
            $(this).datetimepicker(data);
        });
    }


    $(document).on('widget-updated', function (e, $widgetRoot) {

        if (window.hasOwnProperty('google')) {
            var $map = $widgetRoot.find('.quickfield-map');
            if ($map.length) {
                $map.qfMap().addClass('map_loaded');
            }
        }

        var $color = $widgetRoot.find('.quickfield-color');
        if ($color.length) {
            $color.wpColorPicker();
        }


        var $icon_picker = $widgetRoot.find('.quickfield-icon_picker select');
        if ($icon_picker.length) {
            $icon_picker.fontIconPicker();
        }

        var $date_time = $widgetRoot.find('.quickfield-datetime input');
        if ($date_time.length) {
            $date_time.each(function () {
                var data = $(this).data();
                $(this).datetimepicker(data);
            });
        }

    });


    $(document).on('click', '#widgets-right .widget-title', function (e) {

        var $this = $(this);


        setTimeout(function () {
            var $widget = $this.closest('.open');

            if ($widget.length) {
                //Map
                var $map = $widget.find('.quickfield-map');
                if ($map.length && !$map.hasClass('map_loaded')) {
                    $map.qfMap();
                }

                //Repeater
                var $repeater = $widget.find('.quickfield-repeater');
                if ($repeater.length && !$repeater.hasClass('repeater_loaded')) {
                    $repeater.addClass('repeater_loaded').qfRepeater();
                }
            }

        }, 300);



        e.preventDefault();
    });

    $(document).on('quickfield-repeater-item-opened', function (e, $widget) {
        var $map = $widget.find('.quickfield-map');
        if ($map.length) {
            $map.qfMap();
        }
    });


    $(document).on('click', '.quickfield_group .group_nav a', function (e) {

        var $this = $(this);
        var id = $this.attr('href');

        $this.closest('ul').find('.active').removeClass('active');
        $this.addClass('active');

        $('.quickfield_group .group_item.active').removeClass('active');

        var $panel = $('.quickfield_group ' + id);
        $panel.addClass('active');

        if ($('.quickfield_group ' + id + ' .map_loaded').length) {
            if (!$panel.find('.quickfield-map').hasClass('map_refresh')) {
                $panel.find('.quickfield-map').qfMap().addClass('map_refresh');
            }
        }

        $(document).trigger('quickfield_group_active', [$panel]);

        e.preventDefault();
    });

});