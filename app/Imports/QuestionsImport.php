<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionsImport implements ToCollection
{
    public $questions = [];

    /**
     * This method runs for each sheet row collection
     */
    public function collection(Collection $rows)
    {
        // Skip header row by starting from index 1
        foreach ($rows->slice(1) as $row) {
            if ($row->filter()->isEmpty()) {
                // skip empty rows
                continue;
            }

            // Read question text and type from first 2 columns
            $questionText = trim($row[0] ?? '');
            $type = strtolower(trim($row[1] ?? ''));

            // Parse answers from remaining columns in pairs (answer, is_correct)
            $answers = [];
            for ($i = 2; $i < count($row); $i += 2) {
                $answerText = trim($row[$i] ?? '');
                $isCorrect = isset($row[$i + 1]) ? strtolower(trim($row[$i + 1])) : 'false';

                if ($answerText === '') {
                    continue; // skip empty answers
                }

                $answers[] = [
                    'answer_text' => $answerText,
                    'is_correct' => in_array($isCorrect, ['true', '1', 'yes'], true)
                ];
            }

            // Only add question if it has text and answers
            if ($questionText && !empty($answers)) {
                $this->questions[] = [
                    'question_text' => $questionText,
                    'type' => $type,
                    'answers' => $answers
                ];
            }
        }
    }
}
