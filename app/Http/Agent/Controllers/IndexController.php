<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/27
 * Time: 10:54
 */

namespace App\Http\Agent\Controllers;


use App\Services\OrdersService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderDayCountService;
use App\Services\StatisticalService;

class IndexController extends Controller
{
    protected $ordersService;
    protected $userService;
    protected $orderDayCountService;
    protected $statisticalService;

    /**
     * IndexController constructor.
     * @param OrdersService $ordersService
     * @param OrderDayCountService $orderDayCountService
     */
    public function __construct(OrdersService $ordersService, OrderDayCountService $orderDayCountService, StatisticalService $statisticalService)
    {
        $this->ordersService = $ordersService;
        $this->orderDayCountService = $orderDayCountService;
        $this->statisticalService = $statisticalService;
    }


    /**
     * 后台主页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $title='主页';
        $query = $request->input();
        $query['user_id'] = Auth::user()->id;

        $user_day_count   = $this->orderDayCountService->findDayAndUserCount($query['user_id']);
        $order_day_count = json_encode(convert_arr_key($this->orderDayCountService->getOrderUserSevenDaysCount($query), 'tm'));
        return view('Agent.Index.index', compact('title','user_day_count', 'order_day_count'));
    }


    /**
     * 商户费率
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rate()
    {
        return view('Agent.Index.memberRate');
    }

}