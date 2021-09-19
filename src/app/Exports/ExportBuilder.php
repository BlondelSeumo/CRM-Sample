<?php
namespace App\Exports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportBuilder implements FromCollection, WithHeadings, WithTitle
{
    protected Collection $collection;
    protected string $title = '';
    protected array $headings = [];
    public function title(): string
    {
        return $this->getTitle();
    }
    public function setTitle(string $title): ExportBuilder
    {
        $this->title = $title;
        return $this;
    }
    private function getTitle() : string
    {
        return $this->title;
    }
    public function setCollection($collection) : ExportBuilder
    {
        $this->collection = $collection;
        return $this;
    }
    public function getCollection()
    {
        return $this->collection;
    }
    public function collection()
    {
        return $this->getCollection();
    }
    public function headings(): array
    {
        return $this->getHeadings();
    }
    private function getHeadings() : array
    {
        return $this->headings;
    }
    public function setHeadings(array $headings) : ExportBuilder
    {
        $this->headings = $headings;
        return $this;
    }
    public function get() : ExportBuilder
    {
        return $this;
    }
}

