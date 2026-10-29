/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:
     config.language = 'es';
     config.height = '800px';
    // config.uiColor = '#AADC6E';
    config.font_defaultLabel = 'Arial';
    config.fontSize_defaultLabel = '16px';
    config.table_height = '100%';
    config.filebrowserUploadMethod = 'form'; // Added for file browser
    config.chart_height = 400;
    config.chart_width = 1000;
    config.chart_maxItems = 6;
    config.chart_colors = {
        // Colors for Bar/Line chart.
        fillColor: 'rgba(151,187,205,0.5)',
        strokeColor: 'rgba(151,187,205,0.8)',
        highlightFill: 'rgba(151,187,205,0.75)',
        highlightStroke: 'rgba(151,187,205,1)',
        // Colors for Doughnut/Pie/PolarArea charts.
        data: ['#B33131', '#B66F2D', '#B6B330', '#71B232', '#33B22D', '#31B272', '#2DB5B5', '#3172B6', '#3232B6', '#6E31B2', '#B434AF', '#B53071']
    };
    config.chart_configBar = { animation: false };
    config.chart_configDoughnut = { animateRotate: false };
    config.chart_configLine = { animation: true };
    config.chart_configLineBasic = { animation: false };
    config.chart_configPie = { animateRotate: false };
    config.chart_configPie = { animateRotate: false };
    config.newpage_html = '<p>Escribe tu texto aquí.</p>';
};

CKEDITOR.on('dialogDefinition', function(ev) {
    // Take the dialog name and its definition from the event data.
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;
    // Check if the definition is from the dialog we're
    // interested in (the "Table" dialog).
    if (dialogName == 'table') {
        // Get a reference to the "Table Info" tab.
        var infoTab = dialogDefinition.getContents('info');
        txtWidth = infoTab.get('txtWidth');
        txtWidth['default'] = '100%';
    }
});

CKEDITOR.on('instanceReady', function(event) {
    event.editor.on('paste', function(pasteEvent) {
        var items = pasteEvent.data.dataValue;
        console.log(JSON.stringify(items));
    });
});