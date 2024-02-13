<?php

namespace App\Repositories\Farmer\LanguageFarmer\RepoInterface;

use App\Http\Requests\Farmer\LanguageFarmer\LanguageFarmerRequest;

interface LanguageFarmerRepoInterface
{
    public function add(string $s3url, LanguageFarmerRequest $data);

    public function addMany(LanguageFarmerRequest $data);

     public function findAll(string $on = "", string $search = "", int $rowsPerPage = 50);

    public function findById(int $id);

    public function update(LanguageFarmerRequest $data,int $id);

    public function delete();

    public function getTableFields();

    public function updateStatusReject(array $id);

    public function updateStatusFinalize(array $id);

    public function updateStatusApprove(array $id);

}
