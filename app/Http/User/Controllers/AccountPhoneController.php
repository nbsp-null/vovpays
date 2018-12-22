<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/21
 * Time: 15:32
 */

namespace App\Http\User\Controllers;


use App\Services\AccountPhoneService;
use Illuminate\Http\Request;

class AccountPhoneController extends Controller
{
    protected $accountPhoneService;

    public function __construct(AccountPhoneService $accountPhoneService)
    {
        $this->accountPhoneService = $accountPhoneService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $request->input();
        $data['accountType'] = substr($request->url(), 20);
        $list = $this->accountPhoneService->getAllPage($data, 10);
        return view("User.AccountPhone.{$data['accountType']}", compact('list'));
    }

    /**
     * 添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $id = $request->id ?? '';

        if (!empty($id)) {
            $result = $this->accountPhoneService->updata($id, $request->input());
            if ($result) {
                return ajaxSuccess('编辑成功！');
            } else {
                return ajaxError('编辑失败！');
            }
        } else {
            $result = $this->accountPhoneService->add($request->input());
            if ($result) {
                return ajaxSuccess('账号添加成功！');
            } else {
                return ajaxError('账号添加失败！');
            }
        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveStatus(Request $request)
    {
        $data['status'] = $request->status == 'true' ? '1' : '0';
        $result = $this->accountPhoneService->updateStatus($request->id, $data);
        if ($result) {
            return ajaxSuccess('修改成功！');
        } else {
            return ajaxError('修改失败！');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $result = $this->accountPhoneService->findId($request->id);
        if ($result) {
            return ajaxSuccess('获取成功！', $result->toArray());
        } else {
            return ajaxError('获取失败！');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $result = $this->accountPhoneService->del($request->id);
        if ($result) {
            return ajaxSuccess('账号已删除！');
        } else {
            return ajaxError('删除失败！');
        }
    }

}