<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h1 class="text-4xl font-extrabold mb-8 text-gray-900 dark:text-gray-100">Preview Questions Before Upload</h1>

        <form action="{{ route('teacher.questions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="overflow-x-auto border border-gray-300 dark:border-gray-700 rounded-md shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Question Text
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Answers
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($questions as $question)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 align-top text-gray-900 dark:text-gray-100 whitespace-normal break-words max-w-md">
                                    {{ $question['question_text'] }}
                                </td>
                                <td class="px-6 py-4 align-top whitespace-nowrap">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 capitalize">
                                        {{ $question['type'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top text-gray-800 dark:text-gray-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($question['answers'] as $answer)
                                            <li class="{{ $answer['is_correct'] ? 'font-semibold text-green-700 dark:text-green-400' : '' }}">
                                                {{ $answer['answer_text'] }}
                                                @if ($answer['is_correct'])
                                                    <span class="ml-1 inline-block bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300 px-2 py-0.5 text-xs rounded-full">
                                                        Correct
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Hidden JSON field -->
            <input type="hidden" name="questions_json" value='@json($questions)'>

            <div class="flex items-center space-x-4">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 text-white font-semibold rounded-md px-6 py-3 transition"
                >
                    Confirm and Upload Questions
                </button>

                <a href="{{ route('teacher.questions.upload') }}" class="text-blue-600 hover:underline dark:text-blue-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
