let ajaxCountLoginHome = function (url){
    plantilla = ''
    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        success: function (response) {
            
            let datos = response.data
            if (response.success == true) {

                $('#count').html('')
                plantilla = `
                            <div class="row">
                                <div class="col">
                                    <h3 class="mb-2 fw-semibold">${datos.totalLogins || 0}</h3>
                                    <p class="text-muted fs-13 mb-0">TOTAL INICIO SESSION</p>
                                </div>
                                <div class="col col-auto top-icn dash">
                                    <div class="counter-icon bg-danger-gradient box-shadow-danger">
                                        <i class="fe fe-trending-up text-white"></i>
                                    </div>
                                </div>
                            </div>
                                `
                $('#count').append(plantilla)
            }
            
            // Preparar datos para la gráfica
            let loginPerDay = [];
            if (datos.loginStats && datos.loginStats.months && datos.loginStats.monthlyCounts) {
                loginPerDay = datos.loginStats.months.map((month, index) => ({
                    month: month,
                    total: datos.loginStats.monthlyCounts[index]
                }));
            }
            chartMostConsultedActions(loginPerDay)
        },
        error: function (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Algo fallo con la respuesta!',
            })
            console.error(error);
        }
    })
}

let chartMostConsultedActions = function (login_per_day) {
    anychart.onDocumentReady(function() {

        document.getElementById('containerActionHome').innerHTML = '';

        const formattedData = login_per_day.map(item => [item.month, item.total]);

        var chartData = {
            // title: 'Numero de inicio de sesion por mes',
            rows: formattedData
          };

          // create column chart
          var chart = anychart.column();

          // set chart data
          chart.data(chartData);

          // brand color (Tractocar orange)
          chart.palette(['#e8791a']);
          chart.hovered().fill('#b85e0f');

          // turn on chart animation
          chart.animation(true);

          chart.yAxis().labels().format('{%Value}{groupsSeparator: }');

          // set titles for Y-axis
        //   chart.yAxis().title('Revenue');

          chart
            .labels()
            .enabled(true)
            .position('center')
            .anchor('center-bottom')
            .format('{%Value}{groupsSeparator: }');
          chart.hovered().labels(false);

          // interactivity settings and tooltip position
          chart.interactivity().hoverMode('single');

          chart
            .tooltip()
            .positionMode('point')
            .position('center-top')
            .anchor('center-bottom')
            .offsetX(0)
            .offsetY(5)
            .titleFormat('{%X}')
            .format('{%SeriesName} : {%Value}{groupsSeparator: }');

          // set container id for the chart
          chart.container('containerActionHome');

          // initiate chart drawing
          chart.draw();

      });
}
