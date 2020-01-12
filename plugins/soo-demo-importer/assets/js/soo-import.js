jQuery(document).ready(function ($) {
    "use strict";

    var soodi = {
        init: function () {
            this.$progress = $('#soo-demo-import-progress');
            this.$log = $('#soo-demo-import-log');
            this.$importer = $('#soo-demo-importer');
            this.steps = ['content', 'customizer', 'widgets', 'sliders'];

            this.tabs();

            if (!this.$importer.length) {
                return;
            }

            /**
             * The first step: download content file
             */
            this.download(soodi.steps.shift());
        },

        tabs: function () {
            var $tabs = $('.martfury-tabs'),
                $el = $tabs.find('.tabs-nav a'),
                $panels = $tabs.find('.tabs-panel');

            $el.on('click', function (e) {
                e.preventDefault();

                var $tab = $(this),
                    index = $tab.parent().index();

                if ($tab.hasClass('active')) {
                    return;
                }

                $tabs.find('.tabs-nav a').removeClass('active');
                $tab.addClass('active');
                $panels.removeClass('active');
                $panels.filter(':eq(' + index + ')').addClass('active');
            });
        },
        download: function (type) {
            soodi.log('Downloading ' + type + ' file');

            $.get(
                ajaxurl,
                {
                    action: 'soodi_download_file',
                    type: type,
                    demo: soodi.$importer.find('input[name="demo"]').val(),
                    _wpnonce: soodi.$importer.find('input[name="_wpnonce"]').val()
                },
                function (response) {
                    if (response.success) {
                        soodi.import(type);
                    } else {
                        soodi.log(response.data);

                        if (soodi.steps.length) {
                            soodi.download(soodi.steps.shift());
                        } else {
                            soodi.configTheme();
                        }
                    }
                }
            ).fail(function () {
                soodi.log('Failed');
            });
        },

        import: function (type) {
            soodi.log('Importing ' + type);

            var data = {
                action: 'soodi_import',
                type: type,
                _wpnonce: soodi.$importer.find('input[name="_wpnonce"]').val()
            };
            var url = ajaxurl + '?' + $.param(data);
            var evtSource = new EventSource(url);

            evtSource.addEventListener('message', function (message) {
                var data = JSON.parse(message.data);

                switch (data.action) {
                    case 'updateTotal':
                        console.log(data.delta);
                        break;

                    case 'updateDelta':
                        console.log(data.delta);
                        break;

                    case 'complete':
                        evtSource.close();
                        soodi.log(type + ' has been imported successfully!');

                        if (soodi.steps.length) {
                            soodi.download(soodi.steps.shift());
                        } else {
                            soodi.configTheme();
                        }

                        break;
                }
            });

            evtSource.addEventListener('log', function (message) {
                var data = JSON.parse(message.data);
                soodi.log(data.message);
            });
        },

        configTheme: function () {
            $.get(
                ajaxurl,
                {
                    action: 'soodi_config_theme',
                    demo: soodi.$importer.find('input[name="demo"]').val(),
                    _wpnonce: soodi.$importer.find('input[name="_wpnonce"]').val()
                },
                function (response) {
                    if (response.success) {
                        soodi.generateImages();
                    }

                    soodi.log(response.data);
                }
            ).fail(function () {
                soodi.log('Failed');
            });
        },

        generateImages: function () {
            $.get(
                ajaxurl,
                {
                    action: 'soodi_get_images',
                    _wpnonce: soodi.$importer.find('input[name="_wpnonce"]').val()
                },
                function (response) {
                    if (!response.success) {
                        soodi.log(response.data);
                        soodi.log('Import completed!');
                        soodi.$progress.find('.spinner').hide();
                        return;
                    } else {
                        var ids = response.data;

                        if (!ids.length) {
                            soodi.log('Import completed!');
                            soodi.$progress.find('.spinner').hide();
                        }

                        soodi.log('Starting generate ' + ids.length + ' images');

                        soodi.generateSingleImage(ids);
                    }
                }
            );
        },

        generateSingleImage: function (ids) {
            if (!ids.length) {
                soodi.log('Import completed!');
                soodi.$progress.find('.spinner').hide();
                return;
            }

            var id = ids.shift();

            $.get(
                ajaxurl,
                {
                    action: 'soodi_generate_image',
                    id: id,
                    _wpnonce: soodi.$importer.find('input[name="_wpnonce"]').val()
                },
                function (response) {
                    soodi.log(response.data + ' (' + ids.length + ' images left)');

                    soodi.generateSingleImage(ids);
                }
            );
        },

        log: function (message) {
            soodi.$progress.find('.text').text(message);
            soodi.$log.prepend('<p>' + message + '</p>');
        }
    };


    soodi.init();
});
