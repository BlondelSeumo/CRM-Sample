<?php


	namespace App\Helpers\CRM\Traits;


	use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Collection;

    trait PaginateTraits
	{
        public function paginate($items, $perPage = 15, $page = null, $options = [])
        {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

            $items = $items instanceof Collection ? $items : Collection::make($items);

            return (new LengthAwarePaginator(
                $items->forPage($page, $perPage)->values(),
                $items->count(),
                $perPage,
                $page,
                $options
            ))->withPath(
                url()->current()
            );
        }
	}
