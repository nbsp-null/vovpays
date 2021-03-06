<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/21
 * Time: 15:32
 */

namespace App\Http\User\Controllers;


use App\Services\AccountPhoneService;
use App\Services\CheckUniqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccountPhoneRequest;
use App\Services\DelPhoneRedisService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AccountPhoneController extends Controller
{
    protected $accountPhoneService;
    protected $checkUniqueService;

    public function __construct(AccountPhoneService $accountPhoneService, CheckUniqueService $checkUniqueService)
    {
        $this->accountPhoneService = $accountPhoneService;
        $this->checkUniqueService = $checkUniqueService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $data['user_id'] = Auth::user()->id;
        if ($request->type == '0') {
            $data['accountType'] = 'wechat';
        } elseif ($request->type == '1') {
            $data['accountType'] = 'alipay';
        }else if($request->type == '2'){
            $data['accountType'] = 'cloudpay';
        }
        $list = $this->accountPhoneService->searchPhoneStastic($data, 10);
        $channel_payment= DB::table('channel_payments')->where('channel_id',1)->get();
        Redis::select(1);
        foreach ($list as $k=>$v){
            // 加上账号状态检测显示
            $key = $v->phone_id.$data['accountType'];
            if(Redis::exists($key)){
                $params = Redis::hGetAll($key);
                if(strtotime($params['update']) + 50 < time()){
                    $list[$k]['phone_status'] = 0;
                }else{
                    $list[$k]['phone_status'] = 1;
                }
            }else{
                $list[$k]['phone_status'] = 0;
            }
        }

        $module='User';
        $query = $request->query();
        return view("Common.{$data['accountType']}", compact('list','module','query','channel_payment'));

    }

    /**
     * 添加
     * @param AccountPhoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountPhoneRequest $request)
    {
        $id = $request->id?:'';
        if (!empty($id)) {
            $data['qrcode']= $data['qrcode']??'0';
            $result = $this->accountPhoneService->update($id, auth()->user()->id, $request->input());
            if ($result) {
                return ajaxSuccess('编辑成功！');
            } else {
                return ajaxError('编辑失败！');
            }
        } else {
            $request->merge(['user_id' => auth()->user()->id]);
            $result = $this->accountPhoneService->add($request->input());
            if ($result) {
                return ajaxSuccess('账号添加成功！');
            } else {
                return ajaxError('账号添加失败！');
            }
        }

    }

    /**
     * 检测唯一性
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUnique(Request $request)
    {
        $result = $this->checkUniqueService->check('account_phones', $request->type, $request->value, $request->id, $request->name);

        if ($result) {
            return response()->json(array("valid" => "true"));
        } else {
            return response()->json(array("valid" => "false"));
        }
    }

    /**
     * 编辑状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(AccountPhoneRequest $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';

        $result = $this->accountPhoneService->update($request->id, auth()->user()->id, $data);
        if ($result) {
            return ajaxSuccess('修改成功！');
        } else {
            return ajaxError('修改失败！');
        }
    }

    /**
     * 编辑
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $result = $this->accountPhoneService->findIdAndUserId($request->id, auth()->user()->id);
        if ($result) {
            return ajaxSuccess('获取成功！', $result->toArray());
        } else {
            return ajaxError('获取失败！');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $accountPhone = $this->accountPhoneService->findIdAndUserId($request->id, auth()->user()->id);
        $result = $this->accountPhoneService->del($request->id, auth()->user()->id);
        if ($result) {
            if($accountPhone)
            {
                $delPhoneRedisService = app(DelPhoneRedisService::class);
                $delPhoneRedisService->del($accountPhone->phone_id,$accountPhone->accountType);
            }
            return ajaxSuccess('账号已删除！');
        } else {
            return ajaxError('删除失败！');
        }
    }

}