<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\CountryRepository;

 class CountryService extends ServiceBase{
     public function __construct(public CountryRepository $repos){}
 }