@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-red: #dc2626;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
        --neutral-900: #171717;
        --neutral-800: #262626;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--neutral-100);
    }

    .help-container {
        width: 100%;
        padding: 0 1rem;
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .eyebrow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header .eyebrow::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--uitm-amber);
        border-radius: 2px;
    }

    .page-header h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header p {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 0;
        max-width: 600px;
    }

    .header-illustration {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        opacity: 0.1;
    }

    /* Quick Links */
    .quick-link-card {
        display: flex;
        align-items: center;
        padding: 1.25rem;
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .quick-link-card:hover {
        border-color: var(--uitm-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .quick-link-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .quick-link-icon.primary {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
    }

    .quick-link-icon.green {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
    }

    .quick-link-icon.amber {
        background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
    }

    .quick-link-card h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .quick-link-card p {
        color: var(--neutral-500);
        font-size: 0.8rem;
        margin: 0;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--uitm-primary);
    }

    .category-btn {
        font-family: 'IBM Plex Sans', sans-serif;
        padding: 0.5rem 1rem;
        border: 2px solid var(--neutral-200);
        background: white;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--neutral-600);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-btn:hover {
        border-color: var(--uitm-primary);
        color: var(--uitm-primary);
    }

    .category-btn.active {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-color: transparent;
        color: white;
    }

    /* Help Article Cards */
    .help-article-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        transition: all 0.2s ease;
    }

    .help-article-card:hover {
        border-color: var(--neutral-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .article-card-wrapper.hidden {
        display: none;
    }

    .article-header {
        padding: 1.25rem;
        background: var(--neutral-50);
        border-bottom: 2px solid var(--neutral-200);
    }

    .article-category-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 12px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .article-title {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        font-size: 1rem;
        margin: 0;
    }

    .article-content {
        padding: 1.25rem;
        color: var(--neutral-600);
        line-height: 1.7;
        font-size: 0.9rem;
    }

    /* Empty Alert */
    .empty-alert {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 2px solid #0ea5e9;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .empty-alert i {
        color: #0ea5e9;
        font-size: 1.25rem;
    }

    .empty-alert span {
        color: var(--neutral-700);
        font-size: 0.9rem;
    }

    /* Contact Card */
    .contact-card-main {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 16px;
        padding: 2rem;
        margin-top: 2rem;
    }

    .contact-header h4 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .contact-header p {
        color: var(--neutral-500);
        font-size: 0.9rem;
        margin: 0;
    }

    .contact-item {
        display: flex;
        align-items: start;
        padding: 1.25rem;
        background: var(--neutral-50);
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
    }

    .contact-item-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .contact-item h6 {
        font-family: 'IBM Plex Sans', sans-serif;
        color: var(--neutral-800);
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .contact-item p {
        color: var(--neutral-500);
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .contact-item a {
        color: var(--uitm-primary);
        font-weight: 600;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .contact-item a:hover {
        text-decoration: underline;
    }

    /* Calendar Card */
    .calendar-card {
        background: linear-gradient(135deg, var(--uitm-green) 0%, #059669 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        color: white;
        margin-top: 1.5rem;
    }

    .calendar-card h5 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .calendar-card p {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
    }

    .btn-calendar {
        background: white;
        color: var(--uitm-green);
        border: none;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-calendar:hover {
        background: var(--neutral-100);
        color: var(--uitm-green);
    }

    /* Back Button */
    .btn-back {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .header-illustration {
            display: none;
        }

        .quick-link-card {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="help-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="eyebrow">Support Center</div>
            <h1><i class="fas fa-life-ring me-2"></i>Help & Support</h1>
            <p>Get assistance with your credit exemption application and learn how to use the system.</p>
        </div>
        <div class="header-illustration">
            <i class="fas fa-hands-helping"></i>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('student.faq') }}" class="quick-link-card">
                <div class="quick-link-icon primary">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div>
                    <h6>FAQ</h6>
                    <p>Find answers to common questions</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('student.terms') }}" class="quick-link-card">
                <div class="quick-link-icon green">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div>
                    <h6>Terms & Conditions</h6>
                    <p>Read our policies and guidelines</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('student.forms') }}" class="quick-link-card">
                <div class="quick-link-icon amber">
                    <i class="fas fa-download"></i>
                </div>
                <div>
                    <h6>Download Forms</h6>
                    <p>Get required application forms</p>
                </div>
            </a>
        </div>
    </div>

    @if($articles->isNotEmpty())
        <!-- Category Filter -->
        @if($categories->isNotEmpty())
        <div class="filter-card">
            <h5 class="filter-title">
                <i class="fas fa-book-open"></i>Help Articles
            </h5>
            <div class="d-flex flex-wrap gap-2">
                <button class="category-btn active" data-category="all">
                    <i class="fas fa-th-large me-1"></i> All
                </button>
                @foreach($categories as $category)
                <button class="category-btn" data-category="{{ Str::slug($category) }}">
                    {{ $category }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Help Articles -->
        <div class="row g-4" id="articlesContainer">
            @foreach($articles as $article)
            <div class="col-md-6 article-card-wrapper" data-category="{{ Str::slug($article->category ?? 'general') }}">
                <div class="help-article-card">
                    <div class="article-header">
                        @if($article->category)
                        <span class="article-category-badge">{{ $article->category }}</span>
                        @endif
                        <h5 class="article-title">{{ $article->title }}</h5>
                    </div>
                    <div class="article-content">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-alert">
            <i class="fas fa-info-circle"></i>
            <span>No help articles have been published yet. Please check back later.</span>
        </div>
    @endif

    <!-- Contact Section -->
    <div class="contact-card-main">
        <div class="contact-header">
            <h4><i class="fas fa-headset me-2"></i>Contact Support</h4>
            <p>Need additional assistance? Reach out to our support team.</p>
        </div>
        <div class="row g-4 mt-3">
            @if($contactSettings->has('hea_email'))
            <div class="col-md-4">
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h6>HEA Office</h6>
                        <p>For exemption inquiries</p>
                        <a href="mailto:{{ $contactSettings->get('hea_email')->value }}">
                            {{ $contactSettings->get('hea_email')->value }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if($contactSettings->has('support_email'))
            <div class="col-md-4">
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h6>Technical Support</h6>
                        <p>For system issues</p>
                        <a href="mailto:{{ $contactSettings->get('support_email')->value }}">
                            {{ $contactSettings->get('support_email')->value }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if($contactSettings->has('helpdesk_phone'))
            <div class="col-md-4">
                <div class="contact-item">
                    <div class="contact-item-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h6>Helpdesk</h6>
                        <p>{{ $contactSettings->get('office_hours')->value ?? 'Mon-Fri, 8:30 AM - 5:30 PM' }}</p>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactSettings->get('helpdesk_phone')->value) }}">
                            {{ $contactSettings->get('helpdesk_phone')->value }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Academic Calendar Link -->
    @if($contactSettings->has('academic_calendar_url'))
    <div class="calendar-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5><i class="fas fa-calendar-alt me-2"></i>Academic Calendar</h5>
                <p>Check important dates, deadlines, and semester schedules.</p>
            </div>
            <a href="{{ $contactSettings->get('academic_calendar_url')->value }}" target="_blank" class="btn-calendar">
                <i class="fas fa-external-link-alt me-1"></i> View Calendar
            </a>
        </div>
    </div>
    @endif

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryBtns = document.querySelectorAll('.category-btn');
    const articleCards = document.querySelectorAll('.article-card-wrapper');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;

            // Filter article cards
            articleCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>
@endpush
