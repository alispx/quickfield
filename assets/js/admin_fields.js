/*
 * Core Fields js functions  
 * 
 * @author vutuan.sw
 * @since 1.0.0
 */

jQuery(function ($) {

    'use strict';

    $.fn.qfImagePicker = function () {

        var file_frames = {};
        $(document).on('click', '.quickfield-image_picker .add_images', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $field = $this.closest('.quickfield-image_picker');
            var $input = $field.find('input[type="hidden"]');
            if (file_frames[$field.attr('id')]) {
                file_frames[$field.attr('id')].open();
                return;
            }

            file_frames[$field.attr('id')] = wp.media.frames.file_frame = wp.media({
                title: 'Add Images',
                button: {
                    text: 'Add Images'
                },
                library: {
                    type: 'image'
                },
                multiple: $field.data('multiple')
            });
            file_frames[$field.attr('id')].on('open', function () {

                var ids, selection;
                ids = $input.val();
                if ('' != ids) {
                    selection = file_frames[$field.attr('id')].state().get('selection');
                    ids = ids.split(',');
                    $(ids).each(function (index, element) {
                        var attachment;
                        attachment = wp.media.attachment(element);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });
            file_frames[$field.attr('id')].on('select', function () {

                var result, selection;
                result = [];
                selection = file_frames[$field.attr('id')].state().get('selection');
                var ids = $input.val();
                if (ids != '') {
                    ids = ids.split(',');
                }

                var item = '';
                selection.map(function (attachment) {

                    attachment = attachment.toJSON();
                    var src = typeof attachment.sizes.thumbnail.url == 'string' ? attachment.sizes.thumbnail.url : attachment.url;
                    if (ids == '' || $.inArray(attachment.id.toString(), ids) === -1) {
                        item += '<li class="added" data-id="' + attachment.id + '">\n\
                                    <div class="inner">\n\
                                        <img alt="' + attachment.title + '" src="' + src + '"/>\n\
                                    </div>\n\
                                    <a href="#" class="remove"></a>\n\
                                </li>';
                        result.push(attachment.id);
                    }


                });
                if (result.length > 0) {
                    if ($field.data('multiple')) {
                        if (ids != '') {
                            result = ids.concat(result);
                        }
                        $field.find('.image_list').append(item);
                    } else {
                        $field.find('.image_list').html(item);
                    }

                    $input.val(result);
                }
            });
            file_frames[$field.attr('id')].open();
        });
        $(document).on('click', '.quickfield-image_picker .remove', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('.quickfield-image_picker').find('input.image_picker');
            var ids = $input.val();
            var index = $this.closest('li').index();
            if (ids != '') {
                ids = ids.split(',');
                delete ids[index];
                ids = ids.filter(function (val) {
                    return val;
                });
            }

            $input.val(ids);
            $this.closest('li').remove();
        });
        if ($.fn.sortable) {
            $('.quickfield-image_picker .image_list').sortable({
                stop: function (e, ui) {
                    var ids = [];
                    var $list = $(ui.item[0]).parent();
                    $list.find('li').each(function () {
                        ids.push($(this).attr('data-id'));
                    });
                    $list.closest('.quickfield-image_picker').find('input.image_picker').val(ids);
                }
            });
        }
    }

    $.fn.qfImageBackground = function () {

        var file_frames = {};
        $(document).on('click', '.quickfield-image_background .add_image', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $field = $this.closest('.quickfield-image_background');
            var $input = $field.find('input.image_background');
            if (file_frames[$field.attr('id')]) {
                file_frames[$field.attr('id')].open();
                return;
            }

            file_frames[$field.attr('id')] = wp.media.frames.file_frame = wp.media({
                title: 'Set Background Image',
                button: {
                    text: 'Set Background Image'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });
            file_frames[$field.attr('id')].on('open', function () {

                var ids, selection;
                ids = $input.val();
                if ('' != ids) {
                    selection = file_frames[$field.attr('id')].state().get('selection');
                    ids = ids.split(',');
                    $(ids).each(function (index, element) {
                        var attachment;
                        attachment = wp.media.attachment(element);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });
            file_frames[$field.attr('id')].on('select', function () {

                var result, selection;
                result = [];
                selection = file_frames[$field.attr('id')].state().get('selection');
                selection.map(function (attachment) {

                    attachment = attachment.toJSON();
                    $field.find('.attachment-media-view').addClass('added').css('background-image', 'url(' + attachment.url + ')');
                    $input.val(attachment.id);
                });
            });
            file_frames[$field.attr('id')].open();
        });

        $(document).on('click', '.quickfield-image_background .remove', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('.quickfield-image_background').find('input.image_background');
            $input.val('');
            $this.parent().removeClass('added');
        });
    }

    $.fn.qfLink = function () {

        $(document).on('click', '.quickfield-link .link_button', function (e) {
            e.preventDefault();

            var $block, $input, $url_label, $title_label, value_object, $link_submit, $qf_link_submit, $qf_link_nofollow, dialog;

            $block = $(this).closest(".quickfield-link");

            $input = $block.find("input.link");
            $url_label = $block.find(".url-label");
            $title_label = $block.find(".title-label");
            value_object = $input.data("json");
            $link_submit = $("#wp-link-submit");
            $qf_link_submit = $('<input type="button" name="qf_link-submit" id="qf_link-submit" class="button-primary" value="Set Link">');
            $link_submit.hide();
            $("#qf_link-submit").remove();
            $qf_link_submit.insertBefore($link_submit);
            $qf_link_nofollow = $('<div class="link-target qf-link-nofollow"><label><span></span> <input type="checkbox" id="qf-link-nofollow"> Add nofollow option to link</label></div>');
            $("#link-options .qf-link-nofollow").remove();
            $qf_link_nofollow.insertAfter($("#link-options .link-target"));

            setTimeout(function () {
                var currentHeight = $("#most-recent-results").css("top");
                $("#most-recent-results").css("top", parseInt(currentHeight) + $qf_link_nofollow.height())
            }, 200);

            dialog = window.wpLink;

            dialog.open('content');

            _.isString(value_object.url) && ($("#wp-link-url").length ? $("#wp-link-url").val(value_object.url) : $("#url-field").val(value_object.url)), _.isString(value_object.title) && ($("#wp-link-text").length ? $("#wp-link-text").val(value_object.title) : $("#link-title-field").val(value_object.title)), $("#wp-link-target").length ? $("#wp-link-target").prop("checked", !_.isEmpty(value_object.target)) : $("#link-target-checkbox").prop("checked", !_.isEmpty(value_object.target)), $("#qf-link-nofollow").length && $("#qf-link-nofollow").prop("checked", !_.isEmpty(value_object.rel));

            $qf_link_submit.unbind("click.qfLink").bind("click.qfLink", function (e) {

                e.preventDefault();
                e.stopImmediatePropagation();

                var string, options = {};
                options.url = $("#wp-link-url").length ? $("#wp-link-url").val() : $("#url-field").val();
                options.title = $("#wp-link-text").length ? $("#wp-link-text").val() : $("#link-title-field").val();
                var $checkbox = $($("#wp-link-target").length ? "#wp-link-target" : "#link-target-checkbox");
                options.target = $checkbox[0].checked ? " _blank" : "";
                options.rel = $("#qf-link-nofollow")[0].checked ? "nofollow" : "";

                string = _.map(options, function (value, key) {
                    return _.isString(value) && 0 < value.length ? key + ":" + encodeURIComponent(value) : void 0
                }).join("|");

                $input.val(string);
                $input.data("json", options);
                $url_label.html(options.url + options.target);
                $title_label.html(options.title);


                dialog.close('noReset');
                window.wpLink.textarea = "";

                $link_submit.show();
                $qf_link_submit.unbind("click.qfLink");
                $qf_link_submit.remove();
                $("#wp-link-cancel").unbind("click.qfLink");

                $checkbox.attr("checked", false);
                $("#most-recent-results").css("top", "");
                $("#qf-link-nofollow").attr("checked", false);
                return false;
            });

            $("#wp-link-cancel").unbind("click.qfLink").bind("click.qfLink", function (e) {
                e.preventDefault();
                dialog.close('noReset');
                $qf_link_submit.unbind("click.qfLink");
                $qf_link_submit.remove();
                $("#wp-link-cancel").unbind("click.qfLink");
                $("#wp-link-close").unbind("click.qfCloseLink");
                window.wpLink.textarea = "";
                return false;

            });

            $('#wp-link-close').unbind('click').bind('click.qfCloseLink', function (e) {
                e.preventDefault();
                dialog.close('noReset');
                $qf_link_submit.unbind("click.qfLink");
                $qf_link_submit.remove();
                $("#wp-link-cancel").unbind("click.qfLink");
                $("#wp-link-close").unbind("click.qfCloseLink");
                window.wpLink.textarea = "";
                return false;
            });

        });

    }

    $.fn.qfMap = function () {
        return this.each(function (index, item) {
            if (!item.id.includes('__i__')) {

                var $this = $(this);

                var map = {};

                map.zoom = 14;

                map.map = new google.maps.Map($this.find('.map_canvas')[0], {
                    zoom: 4,
                    center: new google.maps.LatLng(40.590377, -97.726872),
                });

                map.marker = null;

                map.overideMap = function (center) {

                    if (map.marker != null) {
                        map.marker.setMap(null);
                        map.marker = null;
                    }

                    map.marker = new google.maps.Marker({
                        position: center,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        icon: map.iconMarker
                    });

                    map.map.setCenter(center);
                    map.map.setZoom(map.zoom);
                    map.marker.setMap(map.map);
                    google.maps.event.addListener(map.marker, 'dragend', map.onDragMarker);
                    google.maps.event.addListener(map.map, 'zoom_changed', map.onZoomChanged);
                }

                map.onDragMarker = function (res) {
                    var latlng = res.latLng;
                    var string = latlng.lat() + ',' + latlng.lng() + '|' + map.zoom;
                    $this.find('input.map').val(string);
                }

                map.onZoomChanged = function (res) {

                    map.zoom = map.map.getZoom();

                    var data = $this.find('input.map').val();
                    if ($.trim(data) != '') {
                        data = data.split('|');

                        var string = data[0] + '|' + map.zoom;
                        $this.find('input.map').val(string);
                    }
                }

                map.onLoad = function () {
                    var data = $this.find('input.map').val();
                    if ($.trim(data) != '') {
                        data = data.split('|');
                        var latlng = data[0].split(',');
                        latlng = new google.maps.LatLng($.trim(latlng[0]), $.trim(latlng[1]));
                        map.zoom = $.trim(data[1]) != '' ? parseInt(data[1]) : 14;
                        map.overideMap(latlng);
                    }
                }

                $this.find('.js-map_search').geocomplete()
                        .bind("geocode:result", function (event, result) {

                            var latlng = result.geometry.location;

                            var string = latlng.lat() + ',' + latlng.lng() + '|' + map.zoom;

                            $this.find('input.map').val(string);
                            map.overideMap(latlng);
                        });

                setTimeout(map.onLoad, 500);

            }
        });
    };

});

jQuery(function ($) {
    /**
     * Field Color
     */
    if (document.getElementsByClassName('quickfield-color').length) {
        $('.quickfield-color').wpColorPicker();
    }

    if (document.getElementsByClassName('quickfield-image_picker').length) {
        $('.quickfield-image_picker').qfImagePicker();
    }

    if (document.getElementsByClassName('quickfield-image_background').length) {
        $('.quickfield-image_background').qfImageBackground();
    }

    if (document.getElementsByClassName('quickfield-icon_picker').length) {
        $('.quickfield-icon_picker select').fontIconPicker();
    }

    if (document.getElementsByClassName('quickfield-link').length) {
        $('.quickfield-link').qfLink();
    }

    if (typeof pagenow == 'string' && pagenow != 'widgets' && document.getElementsByClassName('quickfield-map').length) {
        $('.quickfield-map').qfMap();
    }


    $(document).on('widget-updated', function (e, $widgetRoot) {
        var $map = $widgetRoot.find('.quickfield-map');
        if ($map.length) {
            $map.qfMap().addClass('map_loaded');
        }

        var $color = $widgetRoot.find('.quickfield-color');
        if ($color.length) {
            $color.wpColorPicker();
        }
    });


    $(document).on('click', '.widget-title', function (e) {

        var $widget = $(this).closest('.open');
        var $map = $widget.find('.quickfield-map');
        if ($map.length && !$map.hasClass('map_loaded')) {
            $map.qfMap().addClass('map_loaded');
        }

        e.preventDefault();
    });
});