<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;

trait Services
{
    public function generateDocumentNumber(DocumentType $documentType, User $user): string
    {
        // Hitung 'no_urut'
        $numeric_part_of_no_urut = Document::where('document_type_id', $documentType->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count() + 1;
        $words = explode(' ', $documentType->name);
        $short_document_name = strtoupper(implode('', array_map(fn($word) => substr($word, 0, 1), $words)));
        $short_dept = $user->employee->subDepartment->department->short_name ?? '-';
        $doc_month = Carbon::now()->format('m');
        $doc_month = match ($doc_month) {
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
            default => '-',
        };
        $doc_year = Carbon::now()->format('Y');
        $number = $numeric_part_of_no_urut .'/'. $short_document_name .'/'. $short_dept .'/'. $doc_month .'/'. $doc_year;

        return $number;
    }
}
