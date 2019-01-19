@extends("Agent.Commons.layout")
@section('title','支付宝账号')
@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('content')
    <div class="row" style="margin-top: 20px">
        <div class="col-xs-12 col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">账号列表</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" class="col-md-12">
                    <!-- ./col -->
                    <form class="navbar-form navbar-left" action="{{route('user.account',1)}}" method="get">
                        <div class="form-group">
                            <input type="text" class="form-control" name="account" placeholder="账号">
                        </div>
                        <button type="submit" class="btn btn-info">搜索</button>
                        <a onclick="showModel('添加账号')" class="btn btn-info">添加账号</a>
                    </form>
                    <div class="box-body" style="margin-top: 45px">
                        <table id="example2" class="table table-condensed table-bordered table-hover">
                            <tr style="color: #666666;background: #f5f6f9">
                                <th>手机标识</th>
                                <th>账号</th>
                                <th>账号类型</th>
                                {{--<th>备注</th>--}}
                                <th>单日交易额</th>
                                <th>今日订单量</th>
                                <th>今日成功订单量</th>
                                <th>今日成功率</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            @if(!isset($list[0]))
                                <tr>
                                    <td colspan="10" style="text-align: center">没有找到匹配数据</td>
                                </tr>
                            @else
                                @foreach($list as $v)
                                    <tr>
                                        <td>{{ $v->phone_id }}</td>
                                        <td style="color: red">{{ $v->account }}</td>
                                        <td style="color: #00c0ef">{{ $v->accountType }}</td>
                                        {{--<td>备注</td>--}}
                                        <td><span style="color: green">{{$v->account_amount}}</span></td>
                                        <td><span style="color: green">{{$v->account_order_count}}</span></td>
                                        <td><span style="color: green">{{$v->account_order_suc_count}}</span></td>
                                        <td><span style="color: green">{{$v->success_rate?$v->success_rate.'%':'---'}}</span></td>
                                        <td>
                                            <input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox"
                                                   @if($v['status'] == 1) checked @endif />
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm"
                                                    onclick="edit('编辑',{{$v->id}})">编辑
                                            </button>
                                            <button class="btn btn-primary btn-sm"
                                                    onclick="del($(this),{{$v->id}})">
                                                删除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                        {{$list->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--{{$orders->appends($data)->links()}}--}}


    {{--模态框--}}
    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="addForm" action="{{ route('user.accountAdd') }}" class="form-horizontal" role="form">
                        <input type="hidden" id="id" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">账号:</label>
                            <div class="col-xs-9">
                                <input type="text" id="account" class="form-control" name="account" placeholder="请输入账号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">账号实名:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="alipayusername" placeholder="请输入账号实名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">账号ID:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="alipayuserid" placeholder="请输入账号ID">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-7" style="margin-top:-10px;">
                                <a onclick="aliid('二维码')" style="margin-left: 150px">点击扫码，获取账号ID</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">手机标识:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="phone_id" name="phone_id" placeholder="请输入手机标识">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">限额:</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="dayQuota" placeholder="请输入当日限额">
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                        {{--<label for="" class="col-xs-3 control-label">备注:</label>--}}
                        {{--<div class="col-xs-9">--}}
                        {{--<input type="text" class="form-control" name="" placeholder="请填写备注">--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--二维码模态框--}}
    <div class="modal fade" id="aliidModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;text-align: center">
                    <img src="{{ asset('/AdminLTE/dist/img/user/aliewm.png') }}" alt="二维码获取失败！">
                    <br>
                    <br>
                    <br>
                    <p style="color: red;font-size: 20px">打开支付宝扫一扫，获取账号ID</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>

        $(function () {
            formValidator();
            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText: '启用',
                offText: '禁用',
                onColor: "primary",
                offColor: "danger",
                size: "small",
                onSwitchChange: function (event, state) {
                    var id = $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/agent/account/saveStatus',
                        data: {'status': state, 'id': id},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result.status) {
                                toastr.success(result.msg);
                            } else {
                                $('#addModel').modal('hide');
                                toastr.error(result.msg);
                                window.location.href = window.location.href;
                            }
                        },
                        error: function (XMLHttpRequest, textStatus) {
                            toastr.error('通信失败');
                        }
                    })
                }
            })

            // 模态关闭
            $('#addModel').on('hidden.bs.modal', function () {
                $("#addForm").data('bootstrapValidator').destroy();
                $('#addForm').data('bootstrapValidator', null);
                $('#addForm').get(0).reset();
                $('#id').val('');
                formValidator();
            });

        })


        /**
         *提交
         */
        function save(_this) {
            // formValidator();
            $('#addForm').data('bootstrapValidator').validate();
            if (!$('#addForm').data('bootstrapValidator').isValid()) {
                return;
            }
            _this.removeAttr('onclick');

            var $form = $('#addForm');
            $.post($form.attr('action'), $form.serialize(), function (result) {
                if (result.status) {
                    $('#addModel').modal('hide');
                    setInterval(function () {
                        window.location.reload();
                    }, 1000);

                    toastr.success(result.msg);
                } else {
                    $('#addModel').modal('hide');
                    _this.attr("onclick", "save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }


        /*
         *表单验证
         */
        function formValidator() {
            $('#addForm').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    account: {
                        validators: {
                            notEmpty: {
                                message: '请输入账号!'
                            },
                        }
                    },
                    alipayusername: {
                        validators: {
                            notEmpty: {
                                message: '请输入账号实名!'
                            },
                        }
                    },
                    alipayuserid: {
                        validators: {
                            notEmpty: {
                                message: '请输入账号id!'
                            },
                        },
                    },
                    phone_id: {
                        validators: {
                            notEmpty: {
                                message: '请输入手机标识!'
                            },
                            remote: {
                                url: "{{route('agent.check')}}",
                                message: "该手机已添加过支付宝账号!",
                                type: "post",
                                data: function () { // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value": $("#phone_id").val().trim(),
                                        "type": 'phone_id',
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id": $('#id').val(),
                                        "name": '支付宝'
                                    };
                                },
                                delay: 500,
                            }
                        },
                    },
                    dayQuota: {
                        validators: {
                            notEmpty: {
                                message: '请输入当日限额!'
                            },
                            regexp: {
                                regexp: /^[1-9]\d*$/,
                                message: '请输入正确的数字限额'
                            }

                        },
                    },
                }
            })
        }

        /**
         * 显示模态框
         * @param title
         */
        function showModel(title) {
            $('#addModel .modal-title').html(title);
            $('#addModel').modal('show');
        }

        function aliid(title) {
            $('#aliidModel .modal-title').html(title);
            $('#aliidModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id) {
            $.ajax({
                type: 'get',
                url: '/agent/account/' + id + '/edit',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        $("input[name='account']").val(result.data['account']);
                        $("input[name='alipayusername']").val(result.data['alipayusername']);
                        $("input[name='alipayuserid']").val(result.data['alipayuserid']);
                        $("input[name='phone_id']").val(result.data['phone_id']);
                        $("input[name='dayQuota']").val(result.data['dayQuota']);
                        $("input[name='id']").val(result.data['id']);
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
         * 删除
         * @param _this
         * @param id
         */
        function del(_this, id) {
            swal({
                title: "您确定要删除吗？",
                text: "删除后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'delete',
                    url: '/agent/account',
                    data: {'id': id},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        if (result.status) {
                            _this.parents('tr').empty();
                            swal(result.msg, "账号已删除。", "success")
                        } else {
                            swal(result.msg, "账号未删除。", "error")
                        }

                    },
                    error: function (XMLHttpRequest, textStatus) {
                        toastr.error('通信失败');
                    }
                })
            });
        }
    </script>
@endsection