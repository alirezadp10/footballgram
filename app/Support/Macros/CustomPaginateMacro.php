<?php


namespace App\Support\Macros;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

class CustomPaginateMacro
{
    public function __invoke()
    {
        Builder::macro('customPaginate',function ($perPage = NULL,$columns = ['*'],$key = NULL,$pageName = 'page',$page = NULL) {

            $page = $page ?: Paginator::resolveCurrentPage($pageName);

            $perPage = $perPage ?: $this->model->getPerPage();

            $results = ($total = $this->toBase()->getCountForPagination())
                ? $this->forPage($page, $perPage)->get()->map($columns)
                : $this->model->newCollection();

            $results = $key ? [$key => $results] : $results;

            return $this->paginator($results,$total,$perPage,$page,[
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });
    }
}
