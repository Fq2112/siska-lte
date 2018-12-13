@extends('layouts.mst')
@section('title', ''.Auth::guard('admin')->user()->name.'\'s Dashboard &ndash; '.env("APP_NAME").' Admins | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="row top_tiles">
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{route('table.agencies')}}" class="agency">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-user-tie"></i></div>
                            <div class="count">{{$newAgency}}</div>
                            <h3>New {{$newAgency > 1 ? 'Agencies' : 'Agency'}}</h3>
                            <p>Total: <strong>{{$agencies}}</strong> job agencies</p>
                        </div>
                    </a>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{route('table.users')}}" class="seeker">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-user-graduate"></i></div>
                            <div class="count">{{$newUser}}</div>
                            <h3>New {{$newUser > 1 ? 'Users' : 'User'}}</h3>
                            <p>Total: <strong>{{count($users)}}</strong> users (job seekers)</p>
                        </div>
                    </a>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{route('table.vacancies')}}" class="agency">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-briefcase"></i></div>
                            <div class="count">{{$newVacancy}}</div>
                            <h3>New {{$newVacancy > 1 ? 'Vacancies' : 'Vacancy'}}</h3>
                            <p>Total: <strong>{{$vacancies}}</strong> active vacancies</p>
                        </div>
                    </a>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{route('table.applications')}}" class="seeker">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-paper-plane"></i></div>
                            <div class="count">{{$newApp}}</div>
                            <h3>New {{$newApp > 1 ? 'Applications' : 'Application'}}</h3>
                            <p>Total: <strong>{{$applications}}</strong> applied applications</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Admins & Users
                                <small>Chart</small>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-times"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="users_chart" style="height:350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Agencies & Seekers
                                <small>Chart</small>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-times"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="seekers_chart" style="height:350px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Vacancy & Application
                                <small>Chart</small>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-times"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="applications_chart" style="height:350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        var theme = {
                color: [
                    '#00adb5', '#fa5555', '#BDC3C7', '#3498DB',
                    '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
                ],

                title: {
                    itemGap: 8,
                    textStyle: {
                        fontWeight: 'normal',
                        color: '#408829'
                    }
                },

                dataRange: {
                    color: ['#1f610a', '#97b58d']
                },

                toolbox: {
                    color: ['#408829', '#408829', '#408829', '#408829']
                },

                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.5)',
                    axisPointer: {
                        type: 'line',
                        lineStyle: {
                            color: '#408829',
                            type: 'dashed'
                        },
                        crossStyle: {
                            color: '#408829'
                        },
                        shadowStyle: {
                            color: 'rgba(200,200,200,0.3)'
                        }
                    }
                },

                dataZoom: {
                    dataBackgroundColor: '#eee',
                    fillerColor: 'rgba(64,136,41,0.2)',
                    handleColor: '#408829'
                },
                grid: {
                    borderWidth: 0
                },

                categoryAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },

                valueAxis: {
                    axisLine: {
                        lineStyle: {
                            color: '#408829'
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                },
                timeline: {
                    lineStyle: {
                        color: '#408829'
                    },
                    controlStyle: {
                        normal: {color: '#408829'},
                        emphasis: {color: '#408829'}
                    }
                },

                k: {
                    itemStyle: {
                        normal: {
                            color: '#68a54a',
                            color0: '#a9cba2',
                            lineStyle: {
                                width: 1,
                                color: '#408829',
                                color0: '#86b379'
                            }
                        }
                    }
                },
                map: {
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                color: '#ddd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        },
                        emphasis: {
                            areaStyle: {
                                color: '#99d2dd'
                            },
                            label: {
                                textStyle: {
                                    color: '#c12e34'
                                }
                            }
                        }
                    }
                },
                force: {
                    itemStyle: {
                        normal: {
                            linkStyle: {
                                strokeColor: '#408829'
                            }
                        }
                    }
                },
                chord: {
                    padding: 4,
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        },
                        emphasis: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            },
                            chordStyle: {
                                lineStyle: {
                                    width: 1,
                                    color: 'rgba(128, 128, 128, 0.5)'
                                }
                            }
                        }
                    }
                },
                gauge: {
                    startAngle: 225,
                    endAngle: -45,
                    axisLine: {
                        show: true,
                        lineStyle: {
                            color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                            width: 8
                        }
                    },
                    axisTick: {
                        splitNumber: 10,
                        length: 12,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    axisLabel: {
                        textStyle: {
                            color: 'auto'
                        }
                    },
                    splitLine: {
                        length: 18,
                        lineStyle: {
                            color: 'auto'
                        }
                    },
                    pointer: {
                        length: '90%',
                        color: 'auto'
                    },
                    title: {
                        textStyle: {
                            color: '#333'
                        }
                    },
                    detail: {
                        textStyle: {
                            color: 'auto'
                        }
                    }
                },
                textStyle: {
                    fontFamily: 'Arial, Verdana, sans-serif'
                }
            },
            users = echarts.init(document.getElementById('users_chart'), theme),
            seekers = echarts.init(document.getElementById('seekers_chart'), theme),
            applications = echarts.init(document.getElementById('applications_chart'), theme);

        users.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['Admin', 'User']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true,
                        title: "Restore"
                    },
                    saveAsImage: {
                        show: true,
                        title: "Save Image"
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'Total: {{count($admins) + count($users)}} persons',
                type: 'pie',
                radius: [25, 90],
                center: ['50%', 170],
                roseType: 'area',
                x: '50%',
                max: 40,
                sort: 'ascending',
                data: [{
                    value: '{{count($admins)}}',
                    name: 'Admin'
                }, {
                    value: '{{count($users)}}',
                    name: 'User'
                }]
            }]
        });

        seekers.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['Job Agency', 'Job Seeker']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true,
                        title: "Restore"
                    },
                    saveAsImage: {
                        show: true,
                        title: "Save Image"
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'Total: {{$agencies + count($users)}} persons',
                type: 'pie',
                radius: [25, 90],
                center: ['50%', 170],
                roseType: 'area',
                x: '50%',
                max: 40,
                sort: 'ascending',
                data: [{
                    value: '{{$agencies}}',
                    name: 'Job Agency'
                }, {
                    value: '{{count($users)}}',
                    name: 'Job Seeker'
                }]
            }]
        });

        applications.setOption({
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: ['Job Vacancy', 'Job Application']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true,
                        title: "Restore"
                    },
                    saveAsImage: {
                        show: true,
                        title: "Save Image"
                    }
                }
            },
            calculable: true,
            series: [{
                name: 'Total: {{$vacancies + $applications}} items',
                type: 'pie',
                radius: [25, 90],
                center: ['50%', 170],
                roseType: 'area',
                x: '50%',
                max: 40,
                sort: 'ascending',
                data: [{
                    value: '{{$vacancies}}',
                    name: 'Job Vacancy'
                }, {
                    value: '{{$applications}}',
                    name: 'Job Application'
                }]
            }]
        });
    </script>
@endpush
