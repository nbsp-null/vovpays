<?php

namespace App\Services;
use App\Repositories\AdminsRepository;

class AdminsService
{
    protected $adminsRepository;

    public function __construct(AdminsRepository $adminsRepository)
    {
        $this->adminsRepository = $adminsRepository;
    }

    /**
     * 所有
     * @return mixed
     */
    public function getAll()
    {
        return $this->adminsRepository->getAll();
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $data = array_except($data, ['id','_token','password_confirmation']);
        $exists = $this->adminsRepository->findIdPasswordExists($id,$data['password']);
        if($exists)
        {
            $data = array_except($data, 'password');
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        $this->adminsRepository->syncUpdateAdminsRole($id,[$data['role_id']]);

        $data = array_except($data, 'role_id');
        return $this->adminsRepository->update($id, $data);
    }

    /**
     * 状态变更
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateStatus(int $id, array $data)
    {
        return $this->adminsRepository->update($id,$data);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $role_id = $data['role_id'];
        $data = array_except($data,['password_confirmation','role_id']);
        $data['password'] = bcrypt($data['password']);

        $admins = $this->adminsRepository->add($data);
        $this->adminsRepository->syncUpdateAdminsRole($admins->id,[$role_id]);
        return $admins;
    }

    /**
     * 根据id获取
     * @param string $id
     * @return array
     */
    public function findId(string $id)
    {
        $rule = $this->adminsRepository->findId($id);
        return $rule->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->adminsRepository->syncUpdateAdminsRole($id,[]);
        return $this->adminsRepository->del($id);
    }
}