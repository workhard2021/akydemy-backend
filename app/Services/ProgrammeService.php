<?php 
namespace App\Services;

use App\Contracts\ServiceBase;
use App\Repositories\ProgrammeRepository;

 class ProgrammeService extends ServiceBase{

     public function __construct(public ProgrammeRepository $repos){}
 }