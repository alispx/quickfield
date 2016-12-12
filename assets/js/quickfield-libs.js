/*
 * Quickfield library
 * 
 * @license: GPLv3
 * @author: vutuansw
 */

var Qf_Repeater_Item = function ($list, $item, isAdd) {
    'use strict';

    var self = this;

    this.list = $list;

    this.container = $item;

    this.control = $list.prev('.qf_value');

    this.container.on('keyup change', '.qf_value', function (e) {
        self.setValues();
    });

    this.setValues = function () {

        var values = [];

        self.list.find('[data-repeater-item]').each(function () {

            var fields = {};

            jQuery(this).find('.qf_value').each(function () {
                var $this = jQuery(this);
                if ($this.attr('type') != 'radio' || ($this.attr('type') == 'radio' && $this.is(':checked'))) {
                    var key = jQuery(this).attr('name').match(/\[([^\]]*)(\]|\]\[\])$/)[1];
                    fields[key] = jQuery(this).val();
                }
            });

            values.push(fields);
        });

        self.control.val(JSON.stringify(values)).trigger('change');
    }

    if (isAdd) {
        self.setValues();
    }
}


jQuery(function ($) {

    'use strict';
    $.fn.qfImagePicker = function () {

        var file_frames = {};

        var get_ids = function (input_value) {
            var ids = [];
            if (input_value != '') {
                var arr = input_value.split(',');
                for (var i in arr) {
                    var obj = arr[i].split('|');
                    ids.push(obj[0]);
                }
            }
            return ids;
        }

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
                ids = get_ids($input.val());
                if ('' != ids) {
                    selection = file_frames[$field.attr('id')].state().get('selection');
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
                var ids = get_ids($input.val());

                var item = '';
                selection.map(function (attachment) {

                    attachment = attachment.toJSON();
                    var src = attachment.sizes.hasOwnProperty('thumbnail') ? attachment.sizes.thumbnail.url : attachment.url;
                    if (ids == '' || $.inArray(attachment.id.toString(), ids) === -1) {
                        item += '<li class="added" data-id="' + attachment.id + '">\n\
                                    <div class="inner">\n\
                                        <img alt="' + attachment.title + '" src="' + src + '"/>\n\
                                    </div>\n\
                                    <a href="#" class="remove"></a>\n\
                                </li>';
                        src = src.replace(quickfield_var.upload_url, '');
                        result.push(attachment.id + '|' + encodeURIComponent(src));
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

                    $input.val(result).change();
                }
            });

            file_frames[$field.attr('id')].open();
        });


        $(document).on('click', '.quickfield-image_picker .remove', function (e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('.quickfield-image_picker').find('input[type="hidden"]');
            var ids = $input.val();
            var index = $this.closest('li').index();
            if (ids != '') {
                ids = ids.split(',');
                delete ids[index];
                ids = ids.filter(function (val) {
                    return val;
                });
            }

            $input.val(ids).change();
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
                    $list.closest('.quickfield-image_picker').find('input[type="hidden"]').val(ids);
                }
            });
        }
    }

    $.fn.qfLink = function () {

        $(document).on('click', '.quickfield-link .link_button', function (e) {

            e.preventDefault();
            var $block, $input, $url_label, $title_label, value_object, $link_submit, $qf_link_submit, $qf_link_nofollow, dialog;
            $block = $(this).closest(".quickfield-link");
            $input = $block.find("input.qf_value");
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
                $input.val(string).change();
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
        if (window.hasOwnProperty('google')) {
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
                        $this.find('input.qf_value').val(string).change();
                    }

                    map.onZoomChanged = function (res) {

                        map.zoom = map.map.getZoom();
                        var data = $this.find('input.qf_value').val();
                        if ($.trim(data) != '') {
                            data = data.split('|');
                            var string = data[0] + '|' + map.zoom;
                            $this.find('input.qf_value').val(string).change();
                        }
                    }

                    map.onLoad = function () {
                        var data = $this.find('input.qf_value').val();
                        $this.addClass('map_loaded');
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
                                $this.find('input.qf_value').val(string).change();
                                map.overideMap(latlng);
                            });
                    setTimeout(map.onLoad, 500);
                }
            });
        }
    };

    $.fn.qfRepeater = function () {

        var $repeater = $(this).repeater({
            defaultValues: {},
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function (setIndexes) {

            },
            render: {
                image_picker: function ($field, name, val) {

                    var attach_images = val.split(',');

                    if (attach_images.length > 0) {

                        var image_item = '';

                        $.each(attach_images, function (index, image) {

                            image = image.split('|');
                            if (image.length === 2) {
                                image_item += '<li class="added" data-id="' + image[0] + '">\n\
                                                    <div class="inner">\n\
                                                        <img alt="" src="' + quickfield_var.upload_url + decodeURIComponent(image[1]) + '"/>\n\
                                                    </div>\n\
                                                    <a href="#" class="remove"></a>\n\
                                                </li>';

                            }
                        });

                        $field.parent().find('.image_list').append(image_item);
                    }
                },
                color_picker: function ($field, name, val) {
                    $field.val(val);
                    $field.wpColorPicker({
                        change: function (e, ui) {
                            $(e.target).val(ui.color.toString()).change();
                        }
                    });

                },
                icon_picker: function ($field, name, val) {
                    $field.val(val).change();
                    $field.fontIconPicker();
                },
                checkbox: function ($field, name, val) {

                    if (val != '') {
                        val = val.split(',');
                        var $checboxes = $field.next();
                        for (var i in val) {
                            $checboxes.find('input[value="' + val[i] + '"]').attr('checked', 'checked');
                        }
                    }
                },
                select: function ($field, name, val) {

                    var $select = $field.parent().find('select');

                    if (val != '') {

                        val = val.split(',');
                        for (var i in val) {
                            $select.find('option[value="' + val[i] + '"]').attr('selected', 'selected');
                        }
                        $select.change();
                    }

                    if (typeof $select.attr('multiple') != 'undefined') {
                        $select.selectize({
                            plugins: ['remove_button', 'drag_drop']
                        });
                    }

                },
                link: function ($field, name, val) {
                    var arr = val.split('|');
                    var data = {};
                    if (arr.length > 1) {
                        for (var i in arr) {
                            var child = arr[i].split(':');
                            data[child[0]] = decodeURIComponent(child[1]);
                        }
                        $field.data('json', data);
                        $field.parent().find('.url-label').html(data.url + data.target);
                        $field.parent().find('.title-label').html(data.title);
                    }
                },
                datetime: function ($field, name, val) {
                    $field.datetimepicker($field.data());
                }
            }
        });

        var data = $repeater.data('value');

        if (typeof data == 'object') {
            $repeater.setList(data);
        }

        /**
         *Edit
         */
        $(document).on('click', '.quickfield-repeater [data-repeater-edit], .quickfield-repeater .qf-widget-title h4', function (e) {

            var $parent = $(this).closest(".qf-widget");

            if ($parent.hasClass('open')) {
                $parent.find('.qf-widget-inside').slideUp('fast', function () {
                    $parent.removeClass('open');
                });

            } else {

                $parent.find('.qf-widget-inside').slideDown('fast', function () {
                    $parent.addClass('open');
                    $(document).trigger('quickfield-repeater-item-opened', [$parent]);
                });
            }

            e.preventDefault();
        });
    };
});