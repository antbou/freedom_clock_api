<?php

namespace App\Service;

use ArrayIterator;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

final class Paginator extends DoctrinePaginator
{

    private const DEFAULT_LIMIT = 20;
    private const DEFAULT_PAGE = 1;

    private int $totalPages = 0;
    private int $totalItems = 0;
    private array $items = [];

    public function __construct(
        private QueryBuilder|Query $queryBuilder,
        private ?int $currentPage = self::DEFAULT_PAGE,
        private ?int $limit = self::DEFAULT_LIMIT,
        private string $name = 'items',
        bool $fetchJoinCollection = true
    ) {

        $this->currentPage = $this->currentPage ?? self::DEFAULT_PAGE;
        $this->limit = $this->limit ?? self::DEFAULT_LIMIT;

        $queryBuilder->setFirstResult(($this->currentPage - 1) * $this->limit);
        $queryBuilder->setMaxResults($this->limit);

        parent::__construct($queryBuilder, $fetchJoinCollection);
        $this->totalItems = $this->count();
        $this->items = iterator_to_array(parent::getIterator());

        try {
            $this->totalPages = ceil($this->totalItems / $this->limit);
        } catch (\DivisionByZeroError $e) {
            $this->totalPages = 0;
        }
    }

    public function hasNextPage(): bool
    {
        return  $this->currentPage < $this->totalPages && $this->currentPage > 0;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1 && $this->currentPage <= $this->totalPages;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator([
            $this->name => $this->items,
            'pagination' => [
                'total' => $this->totalItems,
                'count' => count($this->items),
                'items_per_page' => $this->limit,
                'total_pages' => $this->totalPages,
                'current_page' => $this->currentPage,
                'has_next_page' => $this->hasNextPage(),
                'has_previous_page' => $this->hasPreviousPage()
            ],
        ]);
    }
}
