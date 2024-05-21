<?php

namespace App\View;

use Symfony\Component\Serializer\Annotation\Groups;

#[Groups(['paginator:read'])]
final class PaginatorView
{
    public function __construct(
        public int $total,
        public int $count,
        public int $items_per_page,
        public int $total_pages,
        public int $current_page,
        public bool $has_next_page,
        public bool $has_previous_page,
    ) {
    }
}
