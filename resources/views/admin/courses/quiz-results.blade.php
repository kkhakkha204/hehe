@php
    $results = [];
    if ($getRecord()) {
        $results = \App\Models\QuizResult::whereHas('quiz.chapter', function($q) use ($getRecord) {
            $q->where('course_id', $getRecord()->id);
        })->with(['user', 'quiz'])->orderBy('created_at', 'desc')->get();
    }
@endphp

<div class="space-y-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <h3 class="text-lg font-medium text-gray-950 dark:text-white">Thống kê Kế quả kiểm tra</h3>
    
    @if(count($results) > 0)
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">Học viên</th>
                        <th scope="col" class="p-4">Bài thi</th>
                        <th scope="col" class="p-4">Điểm số</th>
                        <th scope="col" class="p-4">Số câu đúng</th>
                        <th scope="col" class="p-4">Thời gian nộp</th>
                        <th scope="col" class="p-4">Chi tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($results as $res)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="p-4 font-medium text-gray-900 dark:text-white">{{ $res->user->name ?? 'N/A' }}</td>
                        <td class="p-4">{{ $res->quiz->title ?? 'N/A' }}</td>
                        <td class="p-4 font-bold @if($res->score >= ($res->quiz->pass_score ?? 50)) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                            {{ $res->score }}%
                        </td>
                        <td class="p-4">{{ $res->total_correct }} / {{ $res->total_questions }}</td>
                        <td class="p-4 whitespace-nowrap">{{ $res->completed_at ? $res->completed_at->format('d/m/Y H:i') : '' }}</td>
                        <td class="p-4">
                            <button 
                                type="button" 
                                x-data 
                                x-on:click="$dispatch('open-modal', { id: 'quiz-result-{{ $res->id }}' })"
                                class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                Xem bài làm
                            </button>

                            <x-filament::modal id="quiz-result-{{ $res->id }}" width="4xl">
                                <x-slot name="heading">
                                    Chi tiết bài làm: {{ $res->quiz->title ?? 'N/A' }}
                                </x-slot>
                                <x-slot name="description">
                                    Học viên: {{ $res->user->name ?? 'N/A' }} - Đạt: {{ $res->score }}%
                                </x-slot>

                                <div class="mt-4 space-y-4 h-96 overflow-y-auto">
                                    @php
                                        $answersData = is_string($res->answers_data) ? json_decode($res->answers_data, true) : $res->answers_data;
                                    @endphp
                                    
                                    @if($answersData && is_array($answersData))
                                        @foreach($answersData as $index => $qa)
                                            <div class="p-4 rounded-lg border @if($qa['is_correct']) border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 @else border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 @endif">
                                                <p class="font-medium mb-2">Câu {{ $index + 1 }}: {{ $qa['question'] ?? '' }}</p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <strong>Học viên chọn:</strong> 
                                                    {{ is_array($qa['user_answer']) ? implode(', ', $qa['user_answer']) : $qa['user_answer'] }}
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    <strong>Đáp án đúng:</strong> 
                                                    {{ is_array($qa['correct_answer']) ? implode(', ', $qa['correct_answer']) : $qa['correct_answer'] }}
                                                </p>
                                                <div class="mt-2 text-sm font-bold @if($qa['is_correct']) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                                                    @if($qa['is_correct'])
                                                        Hiển nhiên đúng! ✓
                                                    @else
                                                        Làm sai rồi! ✗
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu câu trả lời chi tiết.</p>
                                    @endif
                                </div>
                            </x-filament::modal>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">Chưa có kết quả kiểm tra nào cho khóa học này.</p>
        </div>
    @endif
</div>
