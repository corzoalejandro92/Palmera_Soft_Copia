/*=========================================================================================
    File Name: line.js
    Description: Chartjs simple line chart
    ----------------------------------------------------------------------------------------
    Item Name: Chameleon Admin - Modern Bootstrap 4 WebApp & Dashboard HTML Template + UI Kit
    Version: 1.2
    Author: ThemeSelection
    Author URL: https://themeselection.com/
==========================================================================================*/

// Line chart
// ------------------------------
$(window).on("load", function() {

    //Get the context of the Chart canvas element we want to select
    var ctx = $("#line-chart-2");

    // Chart Options
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
            mode: 'label'
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
                    labelString: 'Month'
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
            text: 'Tendencia de captura de roedores 2020 vs 2021'
        }
    };

    // Chart Data
    var chartData = {
        labels: ["Semana 1", "Semana 2", "Semana 3", "Semana 4"],
        datasets: [{
            label: "Abril",
            data: [0, 2, 0, 0],
            lineTension: 0,
            fill: false,
            borderColor: "#28afd0",
            pointBorderColor: "#28afd0",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }]
    };

    var config = {
        type: 'line',

        // Chart Options
        options: chartOptions,

        data: chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);




    //---------------------------------------------------------------


    //Get the context of the Chart canvas element we want to select
    var ctx = $("#line-chart-1");

    // Chart Options
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
            mode: 'label'
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
                    labelString: 'Month'
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
            text: 'Tendencia de captura de roedores Abril 2021'
        }
    };

    // Chart Data
    var chartData = {
        labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [{
            //     label: "My First dataset",
            //     data: [65, 59, 80, 81, 56, 55, 40],
            //     fill: false,
            //     borderDash: [5, 5],
            //     borderColor: "#9C27B0",
            //     pointBorderColor: "#9C27B0",
            //     pointBackgroundColor: "#FFF",
            //     pointBorderWidth: 2,
            //     pointHoverBorderWidth: 2,
            //     pointRadius: 4,
            // }, {
            //     label: "My Second dataset",
            //     data: [28, 48, 40, 19, 86, 27, 90],
            //     fill: false,
            //     borderDash: [5, 5],
            //     borderColor: "#00A5A8",
            //     pointBorderColor: "#00A5A8",
            //     pointBackgroundColor: "#FFF",
            //     pointBorderWidth: 2,
            //     pointHoverBorderWidth: 2,
            //     pointRadius: 4,
            // },

            label: "2020",
            data: [5, 5, 9, 5, 0, 3, 2, 2, 5, 0, 3, 2],
            lineTension: 0,
            fill: false,
            borderColor: "#28afd0",
            pointBorderColor: "#28afd0",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }, {
            label: "2021",
            data: [1, 0, 1, 2],
            lineTension: 0,
            fill: false,
            borderColor: "#FF7D4D",
            pointBorderColor: "#FF7D4D",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }]
    };

    var config = {
        type: 'line',

        // Chart Options
        options: chartOptions,

        data: chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);


});