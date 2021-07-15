@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            @foreach($labels as $key => $value)
                <div class="col s12 m4">
                    <div class="card wg_shadow">
                        <div class="card-content">
                            <label>{{ $key }}</label>
                            <h4>{{ $value }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <div class="row">
            <div class="col s12 m6">
                <canvas id="earnsDaily"  height="100"></canvas>
            </div>
            <div class="col s12 m6">
                <canvas id="earnsMonthly" height="100"></canvas>
            </div>
        </div>
        <script>
            $(function () {
                $("#loadingModal").remove();
                request(null, {address: "{{ route('admin.insight') }}", data: {last_x: 15, format: 'Y-m-d'}}, cp_user_days);
                request(null, {address: "{{ route('admin.insight') }}", data: {last_x: 7, format: 'Y-m'}},cp_user_months);
            });

            function cp_user_days(xhr) {
                let colors = generate_random_rgba(xhr.dates.length);
                var ctx = document.getElementById('earnsDaily').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {labels: xhr.dates, datasets: [{label: '# Kullanıcı Günlük Kazanç', data: xhr.earns,backgroundColor:colors,borderColor:colors}]},
                    options: {scales: {yAxes: [{ticks: {beginAtZero: true}}]}}
                });
            }

            function cp_user_months(xhr) {
                let colors = generate_random_rgba(xhr.dates.length);
                var ctx = document.getElementById('earnsMonthly').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {labels: xhr.dates, datasets: [{label: '# Kullanıcı Aylık Kazanç', data: xhr.earns,backgroundColor:colors,borderColor:colors}]},
                    options: {scales: {yAxes: [{ticks: {beginAtZero: true}}]}}
                });
            }


        </script>

    </div>
@endsection
