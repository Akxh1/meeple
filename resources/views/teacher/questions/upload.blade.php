<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h1 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-gray-100">Upload Questions Excel</h1>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-5 py-3 rounded mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('teacher.questions.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ fileName: '' }">
            @csrf

            <!-- Drag & Drop Upload Box -->
            <div class="relative border-2 border-dashed rounded-md p-6 text-center dark:border-gray-600 hover:border-blue-500 transition"
                 @dragover.prevent
                 @drop.prevent="fileName = $event.dataTransfer.files[0].name; $refs.fileInput.files = $event.dataTransfer.files"
                 @click="$refs.fileInput.click()">

                <input
                    type="file"
                    name="excel_file"
                    id="excel_file"
                    accept=".xlsx,.xls"
                    required
                    class="hidden"
                    x-ref="fileInput"
                    @change="fileName = $refs.fileInput.files[0]?.name"
                >

                <svg class="mx-auto w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 16.5V18a2.5 2.5 0 002.5 2.5h13A2.5 2.5 0 0021 18v-1.5M7.5 10.5L12 15m0 0l4.5-4.5M12 15V3" />
                </svg>

                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                    Drag & drop your <span class="font-medium">.xlsx</span> or <span class="font-medium">.xls</span> file here<br>
                    or <span class="text-blue-600 dark:text-blue-400 font-semibold cursor-pointer">click to browse</span>.
                </p>

                <template x-if="fileName">
                    <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">Selected: <span x-text="fileName"></span></p>
                </template>

                @error('excel_file')
                    <p class="text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Download Template Button -->
            <div class="text-right">
                <a href="{{ asset('sample_question_template.xlsx') }}" class="text-sm text-blue-600 dark:text-blue-400 underline hover:text-blue-800">
                    📄 Download Sample Excel Template
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 text-white font-semibold rounded-md px-6 py-3 w-full transition"
            >
                Preview Questions
            </button>
        </form>
    </div>
</x-app-layout>
