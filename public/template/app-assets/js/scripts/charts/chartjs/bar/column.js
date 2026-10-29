/*=========================================================================================
    File Name: column.js
    Description: Chartjs column chart
    ----------------------------------------------------------------------------------------
    Item Name: Chameleon Admin - Modern Bootstrap 4 WebApp & Dashboard HTML Template + UI Kit
    Version: 1.2
    Author: ThemeSelection
    Author URL: https://themeselection.com/
==========================================================================================*/

// Column chart
// ------------------------------
$(window).on("load", function() {

    //Get the context of the Chart canvas element we want to select
    var ctx = $("#column-chart-1");

    // Chart Options
    var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each bar to be 2px wide and green
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'bottom'
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
            position: 'top',
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Capturas'
                },
                ticks: {
                    beginAtZero: true,
                    steps: 1,
                    stepValue: 1,
                    max: 10
                }
            }]
        },
        title: {
            display: true,
            text: 'Tendencia de captura de roedores año 2021'
        }
    };

    // Chart Data
    var chartData = {
        labels: ["Enero", "Febrero", "Marzo", "Abril", "MAyo", "Junio", "Julio", "Agosto", 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [{
            label: "2021",
            data: [1, 0, 1, 2],
            backgroundColor: "#28D094",
            hoverBackgroundColor: "rgba(40,208,148,.9)",
            borderColor: "transparent"
        }]
    };

    var config = {
        type: 'bar',

        // Chart Options
        options: chartOptions,

        data: chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);




    // ------------------------------------



    //Get the context of the Chart canvas element we want to select
    var ctx = $("#column-chart-2");

    // Chart Options
    var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each bar to be 2px wide and green
        elements: {
            rectangle: {
                borderWidth: 2,
                borderColor: 'rgb(0, 255, 0)',
                borderSkipped: 'bottom'
            }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration: 500,
        legend: {
            position: 'top',
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: false,
                },
                scaleLabel: {
                    display: true,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Capturas'
                },
                ticks: {
                    beginAtZero: true,
                    steps: 1,
                    stepValue: 1,
                    max: 10
                }
            }]
        },
        title: {
            display: true,
            text: 'Tendencia de captura de roedores año 2021'
        }
    };

    // Chart Data
    var chartData = {
        labels: ["Enero", "Febrero", "Marzo", "Abril", "MAyo", "Junio", "Julio", "Agosto", 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [{
            label: "2021",
            data: [1, 0, 1, 2],
            backgroundColor: "#28D094",
            hoverBackgroundColor: "rgba(40,208,148,.9)",
            borderColor: "transparent"
        }]
    };

    var config = {
        type: 'bar',

        // Chart Options
        options: chartOptions,

        data: chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);
});