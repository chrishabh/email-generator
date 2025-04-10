<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 border-r border-gray-200 bg-white fixed h-full overflow-y-auto">
        <div class="p-6 pb-12">
            <div class="flex items-center mb-8">
                <a href="/" class="text-2xl font-bold text-laravel-red">Docs</a>
            </div>
            <nav>
                @php
                $sidebarItems = [
                    [
                        'title' => 'Introduction',
                        'items' => [
                            ['title' => 'Bouncee API Documentation', 'href' => 'overview'],
                        ]
                    ],
                    [
                        'title' => 'API usage',
                        'items' => [
                            ['title' => 'Authentication',        'href'  => 'authentication'],
                            ['title' => 'Request- URL Formats',  'href'  => 'request-url-format'],
                            ['title' => 'Responses',             'href'  => 'responses'],
                            ['title' => 'Rate Limiting',          'href' => 'rate-limiting'],
                            ['title' => 'HTTP Status Codes',      'href' => 'http-status-codes'],
                        ]
                    ],
                    [
                        'title' => 'SINGLE VALIDATION',
                        'items' => [
                            ['title' => 'Single Validation API', 'href' => 'single-validation-api'],
                        ]
                    ],
                    [
                        'title' => 'ACCOUNT',
                        'items' => [
                            ['title' => 'Get credit balance', 'href' => 'get-credit-balance'],
                        ]
                    ],
                    [
                        'title' => 'BULK VALIDATION',
                        'items' => [
                            ['title' => 'Upload a bulk email list', 'href' => 'bulk-email-list'],
                            ['title' => 'Start verifying bulk email list', 'href' => 'start-verifying-bulk-email-list'],
                            ['title' => 'Check job status of a bulk email list', 'href' => 'check-job-status-of-a-bulk-email-list'], 
                            ['title' => 'Download the result of bulk email list', 'href' => 'download-the-result-of-bulk-email-list'],
                            ['title' => 'Delete a bulk email list', 'href' => 'delete-a-bulk-email-list'],
                        ]
                    ],
                ];
                use Illuminate\Support\Str;
                $currentPath = request()->path();
                @endphp

                @foreach($sidebarItems as $section)
                    <div class="mb-8">
                        <h5 class="mb-3 text-sm font-semibold text-gray-900 uppercase">
                            {{ $section['title'] }}
                        </h5>
                        <ul class="space-y-2">
                            @foreach($section['items'] as $item)
                                @php
                                    $href=$item['href'] 
                                @endphp 
                                <li>
                                    <a href="{{ route('reference.page', ['page' => $item['href']]) }}" 
                                       class='block text-gray-600 hover:text-laravel-red text-sm {{request()->is("reference/$href") ? "text-[#00479e] px-2 py-1 rounded-sm bg-[rgba(0,82,184,0.09)]":""}}'>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>
        </div>
    </aside>
