<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArticleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    public function collection()
    {
        return Article::with('Classification')->get();

    }

    public function map($article): array {
        return [
            $article->article,
            $article->classification->classification
        ];
    }

    public function headings(): array
    {
        return [
            'Article',
            'Classification'
        ];
    }
}
