@extends('layouts.app')

@section('content')
<div class="help-container">
    <!-- Header Section -->
    <div class="help-header">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h1 class="help-title">Help & Support</h1>
                <p class="help-subtitle">
                    <i class="fas fa-life-ring me-2"></i>
                    Credit Exemption Management System
                </p>
                <p class="help-description">
                    Get assistance with your credit exemption application and learn how to use the system.
                </p>
            </div>
            <div class="col-lg-3 text-end">
                <div class="help-illustration">
                    <i class="fas fa-hands-helping"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('student.faq') }}" class="quick-link-card">
                <div class="quick-link-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
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
                <div class="quick-link-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
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
                <div class="quick-link-icon" style="background: linear-gradient(135deg, #ed8936, #dd6b20);">
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
        <div class="card mb-4" style="border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <div class="card-body" style="padding: 1.5rem;">
                <h5 class="mb-3" style="color: #2d3748; font-weight: 600;">
                    <i class="fas fa-book-open me-2" style="color: #667eea;"></i>Help Articles
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
        <div class="alert alert-info" style="border-radius: 12px;">
            <i class="fas fa-info-circle me-2"></i>
            No help articles have been published yet. Please check back later.
        </div>
    @endif

    <!-- Contact Section -->
    <div class="contact-card mt-5">
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
                        <p class="text-muted mb-1">For exemption inquiries</p>
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
                        <p class="text-muted mb-1">For system issues</p>
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
                        <p class="text-muted mb-1">{{ $contactSettings->get('office_hours')->value ?? 'Mon-Fri, 8:30 AM - 5:30 PM' }}</p>
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
    <div class="calendar-card mt-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5><i class="fas fa-calendar-alt me-2"></i>Academic Calendar</h5>
                <p class="mb-0">Check important dates, deadlines, and semester schedules.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ $contactSettings->get('academic_calendar_url')->value }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i> View Calendar
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px; padding: 1rem 3rem; font-weight: 600; border: none; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<style>
.help-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.help-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
}

.help-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.help-subtitle {
    font-size: 1.15rem;
    margin-bottom: 1rem;
    opacity: 0.95;
}

.help-description {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
}

.help-illustration {
    font-size: 5rem;
    opacity: 0.2;
    text-align: center;
}

/* Quick Links */
.quick-link-card {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    background: white;
    border-radius: 16px;
    border: 2px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.3s ease;
}

.quick-link-card:hover {
    border-color: #667eea;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.quick-link-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.quick-link-card h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.quick-link-card p {
    color: #718096;
    font-size: 0.85rem;
    margin: 0;
}

/* Category Filter */
.category-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 20px;
    font-weight: 500;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.category-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: white;
}

/* Help Article Cards */
.help-article-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #e2e8f0;
    height: 100%;
    transition: all 0.3s ease;
}

.help-article-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.article-card-wrapper.hidden {
    display: none;
}

.article-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    border-bottom: 1px solid #e2e8f0;
}

.article-category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.article-title {
    color: #2d3748;
    font-weight: 700;
    font-size: 1.1rem;
    margin: 0;
}

.article-content {
    padding: 1.5rem;
    color: #4a5568;
    line-height: 1.8;
    font-size: 0.95rem;
}

/* Contact Card */
.contact-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
}

.contact-header h4 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.contact-header p {
    color: #718096;
    margin: 0;
}

.contact-item {
    display: flex;
    align-items: start;
    padding: 1.25rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.contact-item-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.contact-item h6 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.contact-item a {
    color: #667eea;
    font-weight: 600;
    text-decoration: none;
}

.contact-item a:hover {
    text-decoration: underline;
}

/* Calendar Card */
.calendar-card {
    background: linear-gradient(135deg, #38b2ac, #319795);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    color: white;
}

.calendar-card h5 {
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.calendar-card p {
    opacity: 0.9;
}

.calendar-card .btn-primary {
    background: white;
    color: #319795;
    border: none;
    font-weight: 600;
}

.calendar-card .btn-primary:hover {
    background: #f0f0f0;
}

/* Responsive */
@media (max-width: 768px) {
    .help-header {
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .help-title {
        font-size: 1.8rem;
    }

    .help-illustration {
        font-size: 3rem;
        margin-top: 1rem;
    }

    .quick-link-card {
        padding: 1rem;
    }
}
</style>

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
@endsection
