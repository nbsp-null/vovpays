@extends('Admin.Layouts.layout')

@section("css")
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="row">
        <!-- ./col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">结算总额</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$withdrawInfoSum[0]['amountSum'] ?: 0}} 元</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-pie-chart"></i></span>
                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">手续费 </span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$withdrawInfoSum[0]['withdrawRateSum'] ?: 0}} 元</span>
                </div>
            </div>
        </div>


        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-rmb"></i></span>
                <div class="info-box-content">
                    <span class="progress-description" style="padding-top: 10px;">实际结算</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$withdrawInfoSum[0]['toAmountSum'] ?: 0}}元</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-rmb"></i></span>

                <div class="info-box-content">

                    <span class="progress-description" style="padding-top: 10px;">结算笔数</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="info-box-number">{{$withdrawInfoSum[0]['withdrawCount'] ?: 0}} 笔</span>
                </div>
            </div>
        </div>
        <!-- ./col -->

        <div class="col-xs-12">
            <div class="box">
                <a href="{{ route('withdraws.index') }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                <div class="box-body">
                    <form action="{{ route('withdraws.index') }}" method="get">
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="系统订单号" name="orderId"
                                       @if(isset($query['orderId'])) value="{{ $query['orderId'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="商户订单号" name="outOrderId"
                                       @if(isset($query['outOrderId'])) value="{{ $query['outOrderId'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="商户号" name="user_id"
                                       @if(isset($query['user_id'])) value="{{ $query['user_id'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" style="min-width:300px;" id="daterange-btn"
                                       placeholder="创建时间" name="created_at"
                                       @if(isset($query['created_at'])) value="{{ $query['created_at'] }}" @endif />
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="channelId" name="channelCode">
                                    <option value="-1">选择通道</option>
                                    @foreach($chanel_list as $v )
                                        <option value="{{ $v['channelCode'] }}">{{ $v['channelName'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="status" name="status">
                                    <option value="-1"
                                            @if(!isset($query['status']) || $query['status'] =='-1') selected @endif >
                                        结算状态
                                    </option>
                                    <option value="0"
                                            @if(isset($query['status']) && $query['status'] =='0') selected @endif>未处理
                                    </option>
                                    <option value="1"
                                            @if(isset($query['status']) && $query['status'] =='1') selected @endif >处理中
                                    </option>
                                    <option value="2"
                                            @if(isset($query['status']) && $query['status'] =='2') selected @endif>结算成功
                                    </option>
                                    <option value="3"
                                            @if(isset($query['status']) && $query['status'] =='3') selected @endif>结算异常
                                    </option>
                                    <option value="4"
                                            @if(isset($query['status']) && $query['status'] =='4') selected @endif>取消结算
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btnSearch">查询</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-body">
                    <table id="example2"
                           class="table table-striped table-condensed table-bordered table-hover dataTable">
                        <thead>
                        <tr>
                            {{--<th>#</th>--}}
                            <th>商户号</th>
                            <th>系统订单号</th>
                            <th>商户订单号</th>
                            <th>结算金额</th>
                            <th>手续费</th>
                            <th>实际结算</th>
                            <th>结算通道</th>
                            <th>结算银行</th>
                            <th>开户名</th>
                            <th>银行账号</th>
                            <th>申请时间</th>
                            <th>处理时间</th>
                            <th>结算备注</th>
                            <th>结算状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $v)
                            <tr>
                                {{--<td>{{ $v['id'] }}</td>--}}
                                <td>{{ $v['user_id'] }}</td>
                                <td>{{ $v['orderId'] }}</td>
                                <td>{{ $v['outOrderId'] }}</td>
                                <td>{{ $v['withdrawAmount'] }}</td>
                                <td>{{ $v['withdrawRate'] }}</td>
                                <td>{{ $v['toAmount'] }}</td>
                                <td>{{ $v['channelCode'] }}</td>
                                <td>{{ $v['bankName'] }}</td>
                                <td>{{ $v['accountName'] }}</td>
                                <td>{{ $v['bankCardNo'] }}</td>

                                <td>{{ $v['created_at'] }}</td>
                                <td>{{ $v['updated_at'] }}</td>
                                <td>{{ $v['comment'] }}</td>
                                <td>{{ $v['status'] }}</td>
                                <td>
                                    @if($v['status']=='未处理')

                                        <button type="button" class="btn btn-success btn-sm"
                                                onclick="common('普通结算',{{ $v['id'] }})">普通
                                        </button>
                                        {{--<button type="button" class="btn btn-success btn-sm"--}}
                                                {{--onclick="quick('代付结算',{{ $v['id'] }})">代付--}}
                                        {{--</button>--}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @include('Admin.Commons._page')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="usersForm" action="{{route('withdraws.update')}}" class="form-horizontal" role="form">
                        <input type="hidden" name="id" id="withdrawid">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">结算通道</label>
                            <div class="col-xs-9">
                                <select class="form-control" name="channelCode" id="channelCode">

                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">结算操作</label>
                            <div class="col-xs-9">
                                <select class="form-control" name="action" id="action">

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection('content')
@section("scripts")
    <script src="{{ asset('AdminLTE/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            $('#daterange-btn').val();
            // moment().startOf('day').format('YYYY-MM-DD HH:mm:ss') + ' - ' + moment().format('YYYY-MM-DD HH:mm:ss')

            $('#daterange-btn').daterangepicker(
                {
                    dateLimit: {days: 30},
                    timePicker: false,
                    timePicker24Hour: false,
                    linkedCalendars: false,
                    autoUpdateInput: false,
                    ranges: {

                        '今日': [moment().startOf('day'), moment()],
                        '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '最近7天': [moment().subtract(6, 'days'), moment()],
                        '最近30天': [moment().subtract(29, 'days'), moment()],
                        '本月': [moment().startOf('month'), moment().endOf('month')],
                        '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens: 'right', //日期选择框的弹出位置
                    format: 'YYYY-MM-DD HH:mm:ss', //控件中from和to 显示的日期格式
                    locale: {
                        applyLabel: '确定',
                        cancelLabel: '取消',
                        fromLabel: '起始时间',
                        toLabel: '结束时间',
                        customRangeLabel: '自定义',
                        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                        firstDay: 1,
                        endDate: moment(),
                        format: 'YYYY-MM-DD HH:mm:ss',
                    },
                    startDate: moment().startOf('day'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#daterange-btn').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'))
                });
        })


        /**
         * 普通结算
         * @param id
         * @param title
         */
        function common(title, id) {
            $("#withdrawid").empty();
            $.ajax({
                type: 'get',
                url: '/admin/withdraws/' + id + '/manage',
                data:'type=1',
                // dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        //清空option
                        $("#channelCode").empty();
                        $("#action").empty();
                        //添加option
                        for(i=0;i<result.data.length;i++){
                            $("#channelCode").append("<option value='"+result.data[i].channelCode+"'>"+result.data[i].channelName+"</option>");
                        }

                        $("#action").append("<option value='2'>已经结算</option>");
                        $("#action").append("<option value='3'>结算异常</option>");
                        $("#action").append("<option value='4'>取消结算</option>");


                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }

        /**
         * 代付结算
         * @param id
         * @param title
         */
        function quick(title, id) {
            $.ajax({
                type: 'get',
                url: '/admin/withdraws/' + id + '/manage',
                data:'type=2',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        //添加option
                        for(i=0;i<result.data.length;i++){
                            $("#channelCode").append("<option value='"+result.data[i].channelCode+"'>"+result.data[i].channelName+"</option>");
                        }

                        $("#action").append("<option value='2'>同意代付</option>");
                        $("#action").append("<option value='4'>取消结算</option>");



                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }


    </script>
@endsection



