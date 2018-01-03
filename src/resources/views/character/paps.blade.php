@extends('web::character.layouts.view', ['viewname' => 'paps'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('calendar::seat.paps'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('calendar::seat.paps'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">{{ trans('calendar::seat.paps') }}</h3>
    </div>
    <div class="panel-body">
        <h4>My participation per month</h4>
        <div class="chart">
            <canvas id="papPerMonth" height="150" width="1000"></canvas>
        </div>
        <h4>My participation per ship type</h4>
        <div class="chart">
            <canvas id="papPerType" height="150" width="1000"></canvas>
        </div>
        <h4>All of fame</h4>
        <div class="col-md-4">
            <h5>This week</h5>
            <table class="table table-striped" id="weekly-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Character</th>
                        <th>Paps</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weeklyRanking->take(10) as $top)
                    <tr data-attr="{{ $top->character_id }}">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{!! img('character', $top->character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if(is_null($top->character))
                            Unknown
                            @else
                            {{ $top->character->name }}
                            @endif
                        </td>
                        <td>{{ $top->qty }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Be the first to be PAP this week !</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(!$weeklyRanking->where('character_id', $character_id)->isEmpty())
                <tfoot class="hidden">
                    <tr>
                        <td>{{ $weeklyRanking->where('character_id', $character_id)->keys()->first() + 1 }}.</td>
                        <td>{!! img('character',
                                $weeklyRanking->where('character_id', $character_id)->first()->character_id, 32,
                                ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if(is_null($weeklyRanking->where('character_id', $character_id)->first()->character))
                            Unknown
                            @else
                            {{ $weeklyRanking->where('character_id', $character_id)->first()->character->name }}
                            @endif
                        </td>
                        <td>{{ $weeklyRanking->where('character_id', $character_id)->first()->qty }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        <div class="col-md-4">
            <h5>This month</h5>
            <table class="table table-striped" id="monthly-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Character</th>
                        <th>Paps</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyRanking->take(10) as $top)
                    <tr data-attr="{{ $top->character_id }}">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{!! img('character', $top->character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if (is_null($top->character))
                            Unknown
                            @else
                            {{ $top->character->name }}
                            @endif
                        </td>
                        <td>{{ $top->qty }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Be the first to be PAP this month !</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(!$monthlyRanking->where('character_id', $character_id)->isEmpty())
                <tfoot class="hidden">
                    <tr>
                        <td>{{ $monthlyRanking->where('character_id', $character_id)->keys()->first() + 1 }}.</td>
                        <td>{!! img('character',
                                    $monthlyRanking->where('character_id', $character_id)->first()->character_id, 32,
                                    ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if(is_null($monthlyRanking->where('character_id', $character_id)->first()->character))
                            Unknown
                            @else
                            {{ $monthlyRanking->where('character_id', $character_id)->first()->character->name }}
                            @endif
                        </td>
                        <td>{{ $monthlyRanking->where('character_id', $character_id)->first()->qty }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        <div class="col-md-4">
            <h5>This year</h5>
            <table class="table table-striped" id="yearly-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Character</th>
                        <th>Paps</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($yearlyRanking->take(10) as $top)
                    <tr data-attr="{{ $top->character_id }}">
                        <td>{{ $loop->iteration }}.</td>
                        <td>{!! img('character', $top->character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if (is_null($top->character))
                            Unknown
                            @else
                            {{ $top->character->name }}
                            @endif
                        </td>
                        <td>{{ $top->qty }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Be the first to be PAP this year !</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(!$yearlyRanking->where('character_id', $character_id)->isEmpty())
                <tfoot class="hidden">
                    <tr>
                        <td>{{ $yearlyRanking->where('character_id', $character_id)->keys()->first() + 1 }}.</td>
                        <td>{!! img('character',
                                    $yearlyRanking->where('character_id', $character_id)->first()->character_id, 32,
                                    ['class' => 'img-circle eve-icon small-icon']) !!}
                            @if(is_null($yearlyRanking->where('character_id', $character_id)->first()->character))
                            Unknown
                            @else
                            {{ $yearlyRanking->where('character_id', $character_id)->first()->character->name }}
                            @endif
                        </td>
                        <td>{{ $yearlyRanking->where('character_id', $character_id)->first()->qty }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('web/js/rainbowvis.js') }}"></script>
<script type="text/javascript">
    $(function() {
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
        monthlyData.push({x: "{{ $pap->year }}-{{ $pap->month }}", y:{{ $pap->qty }}});
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

        var tops = $('#weekly-top, #monthly-top, #yearly-top');

        tops.each(function(){
            var found = false;
            var children = $(this).find('tr');
            children.each(function(){
                if ($(this).attr('data-attr') == {{ $character_id }}) {
                    $(this).addClass('bg-' + getActiveThemeColor() + '-gradient');
                    found = true;
                }
            });

            if (!found)
                $(this)
                    .find('tfoot')
                    .removeClass('hidden')
                    .addClass('bg-' + getActiveThemeColor() + '-gradient');
        });

        function getActiveThemeColor() {
            var bodyClass = new RegExp(/skin-([a-z0-9_]+)(-light)?/, 'gi').exec($('body').attr('class'));
            if (bodyClass.length > 0)
                return bodyClass[1];

            return '';
        }

        function rgb2hex(rgb) {
            try {
                rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
                return (rgb && rgb.length === 4) ? "#" +
                    ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
                    ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
                    ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
            } catch (e) {
                return rgb;
            }
        }
    });
</script>
@endpush