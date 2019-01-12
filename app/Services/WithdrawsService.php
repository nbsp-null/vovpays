<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/20
 * Time: 10:12
 */

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\WithdrawsRepository;

class WithdrawsService
{
    protected $withdrawsService;
    protected $userRateService;

    public function __construct(WithdrawsRepository $withdrawsRepository, UserRateService $userRateService)
    {
        $this->withdrawsService = $withdrawsRepository;
        $this->userRateService = $userRateService;
    }


    public function add(array $data)
    {
        //校验支付密码

        // 去掉无用数据
        $data = array_except($data, ['_token', 'payPassword']);
        $data['user_id']=Auth::user()->id;

        //获取费率
        $userRate =5;

        //提现手续费
        $data['withdrawRate'] = $userRate;
        //到账金额
        $data['toAmount'] = $data['withdrawAmount'] - $data['withdrawRate'];

        return $this->withdrawsService->add($data);
    }


    /**
     * 搜索分页
     * @param array $data
     * @param int $page
     * @return mixed
     */
//    public function searchPage(array $data, int $page)
//    {
//        $sql = '1=1';
//        $time=explode(" - ",$data['orderTime']);
//
//        if (isset($data['user_id']) && $data['user_id']) {
//            $sql .= ' and user_id = ?';
//            $where['user_id'] = $data['user_id'];
//        }
//
//        if (isset($time[0]) && $time[0]) {
//            $sql .= ' and created_at >= ?';
//            $where['created_at'] = $time[0];
//        }
//
//        if (isset($time[1]) && $time[1]) {
//            $sql .= ' and created_at <= ?';
//            $where['updated_at'] = $time[1];
//        }
//
//        if (isset($data['status']) && $data['status'] != '-1') {
//            $sql .= ' and status = ?';
//            $where['status'] = $data['status'];
//        }
//        return $this->withdrawsService->searchPage($sql, $where, $page);
//    }

    /**
     * 结算记录
     * @param int $id
     * @param int $page
     * @return mixed
     */
    public function getAllPage(int $page)
    {
        return $this->withdrawsService->searchPage($page);
    }
}