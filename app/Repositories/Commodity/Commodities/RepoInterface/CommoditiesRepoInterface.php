<?php

namespace App\Repositories\Commodity\Commodities\RepoInterface;

use App\Http\Requests\Commodity\Commodities\CommoditiesRequest;

interface CommoditiesRepoInterface
{
    public function add(string $fileUrl,CommoditiesRequest $data);

    public function find(string $on = "", string $search = "", int $rowsPerPage = 50);

    public function all(string $on = "", string $search = "");

    public function findById(int $id);

    public function update(CommoditiesRequest $data,int $id);

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
