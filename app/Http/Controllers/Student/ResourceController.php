<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FaqItem;
use App\Models\HelpArticle;
use App\Models\ContactSetting;
use App\Models\TermsVersion;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        $faqs = FaqItem::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = $faqs->pluck('category')->unique()->filter()->values();

        return view('student.faq', compact('faqs', 'categories'));
    }

    /**
     * Display the Help & Support page.
     */
    public function help()
    {
        $articles = HelpArticle::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = $articles->pluck('category')->unique()->filter()->values();

        $contactSettings = ContactSetting::all()->keyBy('key');

        return view('student.help', compact('articles', 'categories', 'contactSettings'));
    }

    /**
     * Display the Terms & Conditions page.
     */
    public function terms()
    {
        $terms = TermsVersion::getCurrent();

        return view('student.terms', compact('terms'));
    }

    /**
     * Display the Download Forms page.
     */
    public function forms()
    {
        return view('student.forms');
    }
}
