<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Finpulse',
                'content' => '<div class="space-y-6">
<h2 class="text-2xl font-bold text-white">Who We Are</h2>
<p class="text-gray-300 leading-relaxed">Finpulse is a cutting-edge financial sentiment intelligence platform built to decode the true sentiment behind thousands of unstructured fintech app reviews. By leveraging state-of-the-art Natural Language Processing (NLP) and Machine Learning models, Finpulse transforms raw user feedback into crystal-clear, actionable intelligence.</p>

<h2 class="text-2xl font-bold text-white">Our Mission</h2>
<p class="text-gray-300 leading-relaxed">We believe that understanding user sentiment is the key to building better financial products. Our mission is to empower product managers, analysts, and executives with the tools they need to listen to their users at scale, identify emerging trends, and make data-driven decisions that improve financial services for everyone.</p>

<h2 class="text-2xl font-bold text-white">What We Do</h2>
<ul class="list-disc list-inside text-gray-300 space-y-2">
<li>Aggregate and analyze thousands of fintech app reviews from multiple sources</li>
<li>Classify sentiment using advanced NLP pipelines and machine learning</li>
<li>Generate comprehensive reports with actionable insights</li>
<li>Provide real-time dashboards for monitoring user sentiment trends</li>
<li>Enable role-based access for analysts, viewers, and administrators</li>
</ul>

<h2 class="text-2xl font-bold text-white">Contact Us</h2>
<p class="text-gray-300 leading-relaxed">Have questions or feedback? Reach out to our team at <a href="mailto:support@finpulse.io" class="text-emerald-400 hover:text-emerald-300 underline">support@finpulse.io</a>.</p>
</div>',
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'content' => '<div class="space-y-6">
<p class="text-gray-400 text-sm">Last updated: July 2026</p>

<h2 class="text-2xl font-bold text-white">1. Information We Collect</h2>
<p class="text-gray-300 leading-relaxed">We collect information you provide directly to us, such as when you create an account, use our services, or contact us for support. This may include your name, email address, and professional role.</p>

<h2 class="text-2xl font-bold text-white">2. How We Use Your Information</h2>
<p class="text-gray-300 leading-relaxed">We use the information we collect to provide, maintain, and improve our services, to communicate with you, and to protect the security of our platform and users.</p>

<h2 class="text-2xl font-bold text-white">3. Data Security</h2>
<p class="text-gray-300 leading-relaxed">We implement industry-standard security measures to protect your personal information, including encryption, secure access controls, and regular security audits. All data is transmitted over encrypted connections (TLS/SSL).</p>

<h2 class="text-2xl font-bold text-white">4. Data Retention</h2>
<p class="text-gray-300 leading-relaxed">We retain your personal information for as long as your account is active or as needed to provide you with our services. You may request deletion of your account and associated data at any time.</p>

<h2 class="text-2xl font-bold text-white">5. Third-Party Services</h2>
<p class="text-gray-300 leading-relaxed">We may use third-party services for analytics, NLP processing, and infrastructure. These services are bound by their own privacy policies and we ensure they meet our data protection standards.</p>

<h2 class="text-2xl font-bold text-white">6. Contact</h2>
<p class="text-gray-300 leading-relaxed">If you have any questions about this Privacy Policy, please contact us at <a href="mailto:privacy@finpulse.io" class="text-emerald-400 hover:text-emerald-300 underline">privacy@finpulse.io</a>.</p>
</div>',
            ],
            [
                'slug' => 'terms-of-service',
                'title' => 'Terms of Service',
                'content' => '<div class="space-y-6">
<p class="text-gray-400 text-sm">Last updated: July 2026</p>

<h2 class="text-2xl font-bold text-white">1. Acceptance of Terms</h2>
<p class="text-gray-300 leading-relaxed">By accessing or using the Finpulse platform, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>

<h2 class="text-2xl font-bold text-white">2. Use of Services</h2>
<p class="text-gray-300 leading-relaxed">You agree to use Finpulse only for lawful purposes and in accordance with these terms. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>

<h2 class="text-2xl font-bold text-white">3. Intellectual Property</h2>
<p class="text-gray-300 leading-relaxed">All content, features, and functionality of the Finpulse platform, including but not limited to text, graphics, logos, and software, are the exclusive property of Finpulse and are protected by intellectual property laws.</p>

<h2 class="text-2xl font-bold text-white">4. Data and Analytics</h2>
<p class="text-gray-300 leading-relaxed">The sentiment analysis, reports, and insights generated by Finpulse are provided for informational purposes only. While we strive for accuracy, we do not guarantee that all analysis results are error-free. Users should exercise their own judgment when making business decisions.</p>

<h2 class="text-2xl font-bold text-white">5. Limitation of Liability</h2>
<p class="text-gray-300 leading-relaxed">Finpulse shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use or inability to use our services, including but not limited to loss of data, profits, or business opportunities.</p>

<h2 class="text-2xl font-bold text-white">6. Termination</h2>
<p class="text-gray-300 leading-relaxed">We reserve the right to suspend or terminate your access to Finpulse at any time, with or without cause, and with or without notice. Upon termination, your right to use the platform will immediately cease.</p>

<h2 class="text-2xl font-bold text-white">7. Changes to Terms</h2>
<p class="text-gray-300 leading-relaxed">We may update these Terms of Service from time to time. We will notify you of any changes by posting the new terms on this page. Your continued use of the platform after changes are posted constitutes your acceptance of the updated terms.</p>
</div>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
