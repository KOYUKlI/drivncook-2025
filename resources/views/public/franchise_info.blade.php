<x-app-layout>
    <x-slot name="title">{{ __('ui.titles.franchise_info') }}</x-slot>

    <!-- Hero -->
    <section class="bg-gradient-to-r from-orange-50 to-orange-100 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                {{ __('ui.titles.franchise_info') }}
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('ui.public.franchise_info.subtitle') }}
            </p>
            <div class="mt-8">
                <a href="{{ route('public.applications.create') }}" class="inline-flex items-center px-8 py-3 bg-orange-600 hover:bg-orange-700 text-white text-lg font-semibold rounded-lg">
                    {{ __('ui.public.franchise_info.cta_button') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Highlights -->
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-xl border border-gray-200 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.sections.model') }}</h3>
                </div>
                <div class="p-6 rounded-xl border border-gray-200 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.sections.appro') }}</h3>
                </div>
                <div class="p-6 rounded-xl border border-gray-200 bg-white shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('ui.sections.how_it_works') }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Eligibility & 80/20 -->
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('ui.sections.model') }}</h2>
                    <div class="space-y-2 text-gray-700">
                        <p>{{ __('ui.public.franchise_info.investment_desc') }}</p>
                        <p><span class="font-medium">{{ __('ui.public.franchise_info.fees_desc') }}</span> : {{ __('ui.public.franchise_info.fees_amount') }}</p>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">{{ __('ui.sections.appro') }}</h3>
                    <p class="text-gray-700">{{ __('ui.public.franchise_info.ratio_desc') }} ({{ __('ui.public.franchise_info.ratio_title') }})</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('ui.eligibility_conditions') }}</h2>
                    <ul class="mt-4 space-y-2 text-gray-700 list-disc list-inside">
                        <li>{{ __('ui.eligibility_1') }}</li>
                        <li>{{ __('ui.eligibility_2') }}</li>
                        <li>{{ __('ui.eligibility_3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Process -->
    <section class="py-16 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ __('ui.application_process') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $steps = [
                        ['n' => 1, 't' => __('ui.public.application.step_identity')],
                        ['n' => 2, 't' => __('ui.public.application.step_zone')],
                        ['n' => 3, 't' => __('ui.public.application.step_acknowledgments')],
                        ['n' => 4, 't' => __('ui.public.application.step_documents')],
                    ];
                @endphp
                @foreach($steps as $s)
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold">{{ $s['n'] }}</div>
                        <div class="pt-1">
                            <p class="font-semibold text-gray-900">{{ $s['t'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Required Documents -->
    <section class="py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('ui.required_documents') }}</h2>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <li class="p-4 bg-white border rounded-lg">{{ __('ui.labels.cv') }}</li>
                <li class="p-4 bg-white border rounded-lg">{{ __('ui.labels.identity') }}</li>
                <li class="p-4 bg-white border rounded-lg">{{ __('ui.labels.motivation_letter') }}</li>
                <li class="p-4 bg-white border rounded-lg">{{ __('ui.labels.financial_statement') }}</li>
            </ul>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-orange-500 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">{{ __('ui.public.franchise_info.cta_title') }}</h2>
            <p class="text-orange-100 mb-8">{{ __('ui.public.franchise_info.cta_subtitle') }}</p>
            <a href="{{ route('public.applications.create') }}" class="bg-white hover:bg-gray-100 text-orange-600 px-8 py-4 rounded-lg text-lg font-semibold">
                {{ __('ui.public.franchise_info.cta_button') }}
            </a>
        </div>
    </section>
</x-app-layout>
