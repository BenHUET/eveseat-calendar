@extends('web::character.layouts.view', ['viewname' => 'paps'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('calendar::seat.paps'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('calendar::seat.paps'))

@inject('request', Illuminate\Http\Request)

@section('character_content')
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">{{ trans('calendar::seat.paps') }}</h3>
    </div>
    <div class="panel-body">
        <h4>My participation per month</h4>
        <div class="chart">
            <canvas id="papPerMonth" height="250" width="1000"></canvas>
        </div>
        <h4>My participation per ship type</h4>
        <div class="chart">
            <canvas id="papPerType" height="250" width="1000"></canvas>
        </div>
    </div>
</div>
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('web/js/rainbowvis.js') }}"></script>
<script type="text/javascript">
    var rainbow = new Rainbow();
    var themeColor = rgb2hex($('nav.navbar').css('backgroundColor'));
    var monthlyData = [];
    var shipTypeData = [];
    var shipTypeLabels = [];
    var shipTypeColors = [];

    // just in case we're on white paper, reverse color
    if (themeColor.substr(4) === rgb2hex($('.panel').css('backgroundColor')).substr(4))
        themeColor = '#000000';

    rainbow.setSpectrum('#dddddd', themeColor, '#8e8e8e');
    rainbow.setNumberRange(0, {{ $shipTypePaps->count() }});

    @foreach($monthlyPaps as $pap)
    monthlyData.push({x:"{{ $pap->year }}-{{ $pap->month }}", y:{{ $pap->qty }}});
    @endforeach

    @foreach($shipTypePaps as $pap)
    shipTypeData.push({{ $pap->qty }});
    shipTypeLabels.push("{{ $pap->groupName }}");
    shipTypeColors.push('#' + rainbow.colourAt({{ $loop->index }}));
    @endforeach

    new Chart(document.getElementById('papPerMonth').getContext('2d'), {
        type: 'line',
        data: {
            datasets: [{
                label: '# participation',
                data: monthlyData,
                borderColor: themeColor
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    time: {
                        unit: 'month',
                        displayFormats: {
                            month: 'MMM YYYY'
                        }
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Timeline'
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    new Chart(document.getElementById('papPerType').getContext('2d'), {
        type: 'bar',
        data: {
            labels: shipTypeLabels,
            datasets: [{
                label: '# participation',
                data: shipTypeData,
                backgroundColor: shipTypeColors
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    function rgb2hex(rgb){
        try {
            rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
            return (rgb && rgb.length === 4) ? "#" +
                ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
                ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
        } catch(e) {
            return rgb;
        }
    }

</script>
@endpush