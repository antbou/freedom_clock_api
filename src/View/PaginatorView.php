<?php

namespace App\View;

use Symfony\Component\Serializer\Annotation\Groups;

#[Groups(['paginator:read'])]
final class PaginatorView
{
    public function __construct(
        public readonly int $total,
        public readonly int $count,
        public readonly int $items_per_page,
        public readonly int $total_pages,
        public readonly int $current_page,
        public readonly bool $has_next_page,
        public readonly bool $has_previous_page,
    ) {
    }
}
