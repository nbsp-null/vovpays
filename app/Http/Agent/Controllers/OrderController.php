<?php

namespace App\Http\Agent\Controllers;

use App\Services\ChannelPaymentsService;
use App\Services\ChannelService;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    protected $ordersService;
    protected $channelService;
    protected $channelPaymentsService;

    public function __construct( OrdersService $ordersService, ChannelService $channelService, ChannelPaymentsService $channelPaymentsService)
    {
        $this->ordersService  = $ordersService;
        $this->channelService = $channelService;
        $this->channelPaymentsService = $channelPaymentsService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $title='交易管理';
        $uid = Auth::user()->id;
        $query = $request->input();
        $query['agent_id'] = $uid;

        $search = $this->ordersService->searchPage($query, 10);
        $list = $search['list'];
        $orderInfoSum = $search['info'];


        $chanel_list = $this->channelService->getAll();
        $payments_list = $this->channelPaymentsService->getAll();

        unset($query['_token']);
        unset($query['agent_id']);

        return view('Agent.Order.order', compact('title','list', 'query', 'chanel_list', 'payments_list', 'orderInfoSum'));

    }


    /**
     * 详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $rule =$this->ordersService->findId($id);
        if ($rule['agent_id']==Auth::user()->id && !empty($rule)){
            return ajaxSuccess('获取成功',$rule);
        }else{
            return ajaxError('获取失败');
        }


    }

    /**
     * 状态变更
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status;
        $result = $this->ordersService->updateStatus($request->id, $data);

        if($result)
        {
            return ajaxSuccess('修改成功！');
        }else{
            return ajaxError('修改失败！');
        }
    }
}
