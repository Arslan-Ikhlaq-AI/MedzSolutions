<?php

/*
|--------------------------------------------------------------------------
| Marketing Site Content
|--------------------------------------------------------------------------
|
| Copy for the public Medz-Solutions website: a medical billing and revenue
| cycle management company. Headings accept *text* to highlight words in
| the accent colour, short paragraphs accept **text** for bold, and the
| long-form "body" fields are Markdown.
|
| Before going live, check every business claim against what Medz-Solutions
| really offers: the contact details, service standards ("commitments"),
| compliance statements, supported software, job openings and affiliate
| terms are starter content. Testimonials stay hidden until real ones are
| added. Any entry can show a real photo instead of generated artwork by
| adding an "image" key that points to a file under public/.
|
*/

return [

    'company' => [
        'name' => 'Medz-Solutions',
        'tagline' => 'A streamlined medical billing company',
        'summary' => 'Medz-Solutions provides provider credentialing and enrollment, medical billing and revenue cycle management, and medical coding for healthcare practices across the USA, so providers can focus on patients while we make sure they are paid accurately and on time.',
        'email' => 'info@medzsolutions.com',
        'phone' => '+971 55 8531 034',
        'address' => 'Remote-first team serving practices across the USA',
        'hours' => [
            ['days' => 'Monday – Friday', 'time' => '9:00 AM – 6:00 PM (ET)'],
            ['days' => 'Saturday – Sunday', 'time' => 'Closed'],
        ],
        // International format without "+" or leading zeros: 03460766863 becomes 923460766863.
        'whatsapp' => [
            'number' => '971558531034',
            'message' => 'Hello Medz-Solutions, I would like to know more about your medical billing services.',
        ],
        'map' => [
            'query' => 'United States',
            'zoom' => 3,
        ],
        // Shown as icons in the footer. "network" picks the icon: linkedin, facebook, instagram or tiktok.
        'social' => [
            ['network' => 'linkedin', 'label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/112043392/'],
            ['network' => 'facebook', 'label' => 'Facebook', 'url' => 'https://www.facebook.com/profile.php?id=61596137231105'],
            ['network' => 'instagram', 'label' => 'Instagram', 'url' => 'https://www.instagram.com/medzsolutions/'],
            ['network' => 'tiktok', 'label' => 'TikTok', 'url' => 'https://www.tiktok.com/@medz.solutions'],
        ],
    ],

    /*
    | Real client feedback only. Each item: quote, name, role, practice.
    */
    'testimonials' => [],

    /*
    | Options for the "free practice review" checklist on the home page.
    | The keys are stored with each request, so keep them stable.
    */
    'revenue_challenges' => [
        'denials' => 'Too many denied or rejected claims',
        'aging_ar' => 'Insurance balances older than 90 days',
        'slow_payments' => 'Slow or unpredictable payments',
        'credentialing' => 'Credentialing or enrollment delays',
        'coding' => 'Coding errors or audit concerns',
        'staffing' => 'Billing staff turnover or burnout',
    ],

    /*
    | Home photos are CC0 from StockSnap (no attribution required):
    | stocksnap.io/photo/doctor-patient-7NQJS4WWQW, stocksnap.io/photo/health-care-QQ2YJGJJXJ,
    | stocksnap.io/photo/female-nurse-XYNYITNTU1, stocksnap.io/photo/analytics-charts-RCFX768X06,
    | stocksnap.io/photo/health-care-8P49QWX4US and stocksnap.io/photo/health-care-Z7PBRN5VXJ
    */
    /*
    | Cookie consent. Bump "version" whenever the policy or the cookies change so every visitor
    | is asked again. Cookies tied to an "integration" are only listed (and the category only
    | marked as in use) when that tool is configured in config/services.php. Names ending in "*"
    | match every cookie starting with that prefix; they are deleted when consent is withdrawn.
    */
    'cookies' => [
        'version' => '1',
        'days' => 180,
        'intro' => 'We use cookies to keep this website working and, with your permission, to understand how it is used and improve it. You can accept everything, reject everything that is not essential, or choose exactly what you are comfortable with. You can change your mind at any time.',
        'categories' => [
            [
                'id' => 'necessary',
                'title' => 'Strictly necessary',
                'required' => true,
                'description' => 'These keep the website secure and let forms work. They do not track you and cannot be switched off.',
                'cookies' => [
                    ['name' => '{session}', 'provider' => 'Medz-Solutions', 'purpose' => 'Keeps your visit secure and remembers confirmation messages after you submit a form.', 'duration' => 'Session'],
                    ['name' => 'XSRF-TOKEN', 'provider' => 'Medz-Solutions', 'purpose' => 'Protects our forms from forged requests.', 'duration' => 'Session'],
                    ['name' => 'medz_consent', 'provider' => 'Medz-Solutions', 'purpose' => 'Remembers the cookie choices you make here and your consent ID.', 'duration' => '6 months'],
                ],
            ],
            [
                'id' => 'analytics',
                'title' => 'Analytics',
                'required' => false,
                'description' => 'Help us understand which pages are useful and where visitors get stuck, so we can improve the website. Reports are aggregated and never identify you by name.',
                'cookies' => [
                    ['name' => 'Visit history', 'provider' => 'Medz-Solutions', 'purpose' => 'Records the pages you open here, linked only to your random consent ID. If you later contact us through a form, it is attached to your enquiry so our team knows what you were interested in. Deleted when you withdraw consent.', 'duration' => '12 months'],
                    ['name' => '_ga', 'provider' => 'Google Analytics', 'purpose' => 'Tells visits apart so we can count visitors without knowing who they are.', 'duration' => '2 years', 'integration' => 'google_analytics'],
                    ['name' => '_ga_*', 'provider' => 'Google Analytics', 'purpose' => 'Keeps track of the current visit for our statistics.', 'duration' => '2 years', 'integration' => 'google_analytics'],
                ],
            ],
            [
                'id' => 'marketing',
                'title' => 'Marketing',
                'required' => false,
                'description' => 'Measure whether our advertising works and show you relevant information about our services on other websites.',
                'cookies' => [
                    ['name' => '_fbp', 'provider' => 'Meta', 'purpose' => 'Measures visits that come from our Facebook and Instagram advertising.', 'duration' => '3 months', 'integration' => 'meta_pixel'],
                ],
            ],
            [
                'id' => 'preferences',
                'title' => 'Preferences',
                'required' => false,
                'description' => 'Remember choices such as language or display settings, so the website works the way you like on your next visit.',
                'cookies' => [],
            ],
        ],
    ],

    'cookie_policy_page' => [
        'updated' => 'October 2026',
        'intro' => 'This policy explains what cookies are, which ones Medz-Solutions uses and how you can control them.',
        'what_title' => 'What are cookies?',
        'what_text' => 'Cookies are small text files that a website stores in your browser. Some are essential for a site to work; others help the site owner understand how it is used. We only set cookies that are not strictly necessary after you have agreed.',
        'control_title' => 'Your choices',
        'control_text' => 'You can change or withdraw your consent at any time with the settings at the top of this page or the "Cookie settings" link in the footer of every page. When you withdraw consent we delete the cookies of that category straight away. You can also delete cookies in your browser settings, although parts of the site may then stop working as expected.',
        'journey_text' => 'If you allow analytics and later send us a form, we link the pages you viewed to your enquiry so we can answer more helpfully. We only ever email people who have contacted us first, and every email we send about your enquiry comes from our team.',
        'signal_text' => 'We honour the Global Privacy Control (GPC) signal. If your browser sends it, marketing cookies stay off unless you switch them on yourself.',
        'contact_text' => 'If you have questions about this policy, contact us at',
    ],

    'home' => [
        'slides' => [
            [
                'image' => 'images/photos/hero-doctors-clinic.jpg',
                'eyebrow' => 'Medical Billing & Revenue Cycle Management',
                'title' => 'Get Paid Faster for the Care *You Provide*',
                'body' => 'At **Medz-Solutions**, our billing team takes eligibility checks, claims, payment posting, AR follow-up and denials off your desk, so clean claims go out quickly and the revenue you have earned actually reaches your account.',
            ],
            [
                'image' => 'images/photos/hero-doctors-duo.jpg',
                'eyebrow' => 'Medical Coding',
                'title' => 'Accurate Codes, Fewer Denials, *Full Reimbursement*',
                'body' => 'Our certified coders assign precise ICD-10-CM, CPT and HCPCS codes and modifiers for every encounter, so claims are compliant, fully supported and paid the first time.',
            ],
            [
                'image' => 'images/photos/hero-doctors-laptop.jpg',
                'eyebrow' => 'Provider Credentialing & Enrollment',
                'title' => 'Start Seeing Insured Patients *Sooner*',
                'body' => 'From CAQH profiles to payer applications and revalidations, **Medz-Solutions** manages the paperwork and the follow-up calls, so your providers can join networks without months of back-and-forth.',
            ],
        ],
        'primary_cta' => 'Get a Free Practice Review',
        'secondary_cta' => 'Explore Services',
        'quick_links' => [
            'schedule' => ['label' => 'Book a Call', 'title' => 'Schedule a Meeting'],
            'specialities' => ['label' => 'Your Field', 'title' => 'Find Your Specialty'],
            'phone' => ['label' => 'Talk to Us', 'title' => 'Call Our Team'],
            'contact' => ['label' => 'Reach Us', 'title' => 'Contact Details'],
        ],
        'trust' => [
            'title' => 'Built for *Compliance* From the First Claim',
            'items' => [
                ['icon' => 'shield', 'title' => 'HIPAA-aligned workflows'],
                ['icon' => 'badge', 'title' => 'Certified coding staff'],
                ['icon' => 'file', 'title' => 'Business associate agreements'],
                ['icon' => 'lock', 'title' => 'Encrypted data exchange'],
            ],
        ],
        'welcome' => [
            'title' => 'Welcome to *Medz-Solutions*',
            'subtitle' => 'Billing handled properly, so your team can focus on patients.',
            'image' => 'images/photos/home-welcome.jpg',
            'image_alt' => 'A billing analyst reviewing revenue charts on a laptop',
            'link' => 'More About Us',
        ],
        'why' => [
            'title' => 'Why Practices Choose Medz-Solutions',
            'text' => 'Four things every client can count on from the day we start working together.',
            'tiles' => [
                ['icon' => 'clock', 'title' => 'Fast Claim Submission', 'text' => 'Claims go out within 48 hours of receiving your charges.'],
                ['icon' => 'users', 'title' => 'Dedicated Account Manager', 'text' => 'One named contact who knows your practice and your payers.'],
                ['icon' => 'shield', 'title' => 'HIPAA-Aligned Processes', 'text' => 'Signed BAAs, encrypted file exchange and strict access controls.'],
                ['icon' => 'plug', 'title' => 'Works With Your EHR', 'text' => 'We work inside your current software, so nothing changes for staff.'],
            ],
        ],
        'choose' => [
            'title' => 'How to Choose a Medical Billing Partner',
            'paragraphs' => [
                'The right billing partner should feel like part of your practice. Look for a team that understands your specialty, works inside the software you already use and explains your numbers in plain language.',
                'Ask how quickly claims are submitted, how denials are handled and what you will see in your monthly reports. Our free guides and checklists walk you through the questions worth asking before you sign anything.',
            ],
            'cta' => 'Read Our Free Guides',
            'image' => 'images/photos/home-choose.jpg',
            'image_alt' => 'A doctor and nurses reviewing notes together',
        ],
        'about' => [
            'eyebrow' => 'About Medz-Solutions',
            'title' => 'Your Billing Office, *Without the Overhead*',
            'paragraphs' => [
                '**Medz-Solutions** works as an extension of your practice. Our billers, coders and account managers plug into the systems you already use and take responsibility for every claim, from the moment a patient is scheduled to the day the final payment posts.',
                'Instead of hiring, training and managing an in-house billing team, you get a group of specialists, transparent reporting and one point of contact who knows your practice by name.',
            ],
            'highlights' => [
                'Dedicated account manager',
                'Certified medical coders',
                'Daily claim follow-up',
                'Clear monthly reporting',
            ],
        ],
        'meeting' => [
            'title' => 'Let\'s Schedule a Meeting',
            'text' => 'Choose a day that suits you and we will contact you to confirm a time for a free, no-obligation call.',
            'button' => 'Request a Meeting',
        ],
        'commitments' => [
            'eyebrow' => 'Why Medz-Solutions',
            'title' => 'Service Standards Built Around *Your Cash Flow*',
            'text' => 'Every practice we work with can expect these standards from the first day of our partnership.',
            'items' => [
                ['value' => 48, 'suffix' => ' hrs', 'label' => 'Maximum time to submit claims once charges reach us'],
                ['value' => 100, 'suffix' => '%', 'label' => 'Of claims checked for errors before submission'],
                ['value' => 1, 'suffix' => '', 'label' => 'Dedicated account manager for every practice'],
                ['value' => 30, 'suffix' => ' days', 'label' => 'Typical onboarding time after agreements are signed'],
                ['text' => 'Daily', 'label' => 'Follow-up on unpaid and underpaid claims'],
                ['text' => 'Monthly', 'label' => 'Performance reports and review calls'],
            ],
        ],
        'services' => [
            'eyebrow' => 'Our Services',
            'title' => 'Our Core Services',
            'text' => 'Three core services that cover every stage of the revenue cycle, available as a complete package or one piece at a time.',
            'tab_label' => 'Core Service',
            'list_title' => 'What is included',
            'more' => 'Learn More',
            'cta' => 'View All Services',
        ],
        'specialities' => [
            'eyebrow' => 'Specialities',
            'title' => 'Specialties We Support',
            'cta' => 'View All Specialities',
        ],
        'software' => [
            'eyebrow' => 'Software',
            'title' => 'We Work Inside the *Systems You Use*',
            'text' => 'No new software to buy and no disruption for your staff. Our team works directly in your EHR, practice management system and clearinghouse.',
            'cta' => 'See All Platforms',
        ],
        'review' => [
            'eyebrow' => 'Free Practice Review',
            'title' => 'Tell Us Where Your Revenue *Gets Stuck*',
            'text' => 'Tick the challenges that sound familiar and leave your details. A billing specialist will look at your situation and suggest practical next steps, free of charge.',
            'button' => 'Get My Free Review',
        ],
        'benefits' => [
            'eyebrow' => 'Why Outsource',
            'title' => 'What Changes When Billing Is *Off Your Plate*',
            'items' => [
                ['icon' => 'wallet', 'title' => 'Lower Overhead', 'text' => 'No salaries, software seats, training budgets or turnover costs for an in-house billing department.'],
                ['icon' => 'clock', 'title' => 'Faster Reimbursement', 'text' => 'Claims go out promptly and unpaid balances are worked every day, shortening the gap between the visit and the payment.'],
                ['icon' => 'check', 'title' => 'Fewer Costly Errors', 'text' => 'Coding reviews and claim checks catch problems before payers do, so fewer claims come back denied.'],
                ['icon' => 'layers', 'title' => 'Room to Grow', 'text' => 'Add providers, locations or specialties without rebuilding your back office every time you expand.'],
                ['icon' => 'shield', 'title' => 'Compliance Support', 'text' => 'Processes designed around HIPAA, payer policies and current coding guidelines, reviewed as rules change.'],
                ['icon' => 'heart', 'title' => 'More Time for Patients', 'text' => 'Your staff spend their day helping patients instead of waiting on hold with insurance companies.'],
            ],
        ],
        'support' => [
            'eyebrow' => 'Dedicated Support',
            'title' => 'Account Managers Who Know Your Practice',
            'image' => 'images/photos/home-support.jpg',
            'image_alt' => 'Members of a healthcare team smiling during a conversation',
            'paragraphs' => [
                'Every practice is paired with a named account manager who learns your providers, payers and preferences. They are your first call for any question and they keep the whole billing team pointed at your goals.',
                'Behind them, certified coders and experienced billers handle the daily work, trained in the specialty rules that matter to you.',
            ],
            'highlights' => [
                'One point of contact',
                'Specialty-trained billers',
                'Quarterly strategy reviews',
                'Same-day answers',
            ],
        ],
        'onboarding' => [
            'eyebrow' => 'Onboarding',
            'title' => 'Switching to Medz-Solutions Is Simple',
            'text' => 'A clear, four-step handover that keeps your billing running the whole time.',
            'steps' => [
                ['title' => 'Practice Review', 'text' => 'We learn how your practice works today, review a snapshot of your accounts receivable and recommend the services that will make the biggest difference.', 'tags' => ['Discovery Call', 'AR Snapshot', 'Proposal']],
                ['title' => 'Agreements & Access', 'text' => 'We sign a service agreement and a business associate agreement, then set up secure access to your EHR, practice management system and clearinghouse.', 'tags' => ['Service Agreement', 'BAA', 'System Access']],
                ['title' => 'Smooth Transition', 'text' => 'We map your workflows, confirm payer setups and start working older balances, so nothing falls through the cracks during the handover.', 'tags' => ['Workflow Mapping', 'Payer Setup', 'AR Clean-up']],
                ['title' => 'Go Live & Grow', 'text' => 'Daily billing begins, your account manager checks in regularly and monthly reports show exactly how your revenue is improving.', 'tags' => ['Daily Billing', 'Monthly Reports', 'Review Calls']],
            ],
        ],
        'faqs' => [
            'eyebrow' => 'FAQs',
            'title' => 'Frequently Asked Questions',
            'text' => 'Straight answers to the questions practices ask us most. Can\'t find yours? Give us a call.',
            'cta' => 'View All FAQs',
        ],
        'testimonials' => [
            'eyebrow' => 'Client Feedback',
            'title' => 'What Practices Say About *Medz-Solutions*',
        ],
        'visit' => [
            'eyebrow' => 'Get In Touch',
            'title' => 'We Are Here When You Need Us',
        ],
    ],

    'about' => [
        'header_text' => 'A dedicated team of billers, certified coders and account managers working as an extension of your practice.',
        'header_image' => 'images/photos/about-hero.jpg',
        'intro' => [
            'image' => 'images/photos/about-consultation.jpg',
            'image_alt' => 'Physician listening to a patient during a consultation',
            'secondary_image' => 'images/photos/about-specialist.jpg',
            'secondary_image_alt' => 'Medical specialist reviewing records on a tablet',
            'eyebrow' => 'Who We Are',
            'title' => 'Medical Billing Specialists *on Your Side*',
            'paragraphs' => [
                '**Medz-Solutions** was built for practices that are tired of chasing payments. We believe providers should spend their energy on patients, not on denial letters, hold music and spreadsheets.',
                'Our team of billers, certified coders, credentialing specialists and account managers takes ownership of the revenue cycle from start to finish. We work inside your existing systems, follow your preferences and report back in language that is easy to understand.',
                'Whether you are a solo provider opening your first clinic or a multi-location group adding new specialties, we scale our support to match where you are and where you want to go.',
            ],
            'highlights' => [
                'Specialists, not generalists',
                'Transparent reporting',
                'Security from day one',
                'Support that scales with you',
            ],
        ],
        'values' => [
            'eyebrow' => 'Why Medz-Solutions',
            'title' => 'What Makes Our Billing *Different*',
            'items' => [
                ['icon' => 'search', 'title' => 'Prevention First', 'text' => 'We fix problems at their source, from front-desk data to documentation habits, so fewer claims need rework later.'],
                ['icon' => 'chart', 'title' => 'Total Transparency', 'text' => 'You always know what was billed, what was paid and what we are doing about everything in between.'],
                ['icon' => 'users', 'title' => 'A Team That Knows You', 'text' => 'The same account manager and billing specialists work on your practice month after month.'],
                ['icon' => 'shield', 'title' => 'Careful With Data', 'text' => 'Protected health information is handled under signed agreements, strict access controls and secure systems.'],
                ['icon' => 'badge', 'title' => 'Up-to-Date Expertise', 'text' => 'Our coders and billers track annual code updates and payer policy changes so you do not have to.'],
                ['icon' => 'heart', 'title' => 'Patient-Friendly', 'text' => 'Statements and phone calls reflect the same kindness your patients experience in your clinic.'],
            ],
        ],
        'team_cta' => [
            'title' => 'Meet the People Behind *Your Billing*',
            'text' => 'Get to know the specialist teams who look after every practice we support.',
        ],
    ],

    'team_page' => [
        'header_text' => 'Billers, coders and account managers who treat your practice\'s revenue as if it were their own.',
        'header_image' => 'images/photos/team-office.jpg',
        'title' => 'The People Behind *Every Clean Claim*',
        'text' => 'Meet some of the specialists who look after our practices every day, from the first claim to the final payment.',
        // Sample profiles. Set "image" to a path such as images/team/sarah-mitchell.jpg once real photos are ready.
        'members' => [
            ['name' => 'Sarah Mitchell', 'role' => 'Operations Director', 'text' => 'Oversees every client team and keeps our service standards consistent across all practices.', 'image' => null],
            ['name' => 'Daniel Brooks', 'role' => 'Senior Account Manager', 'text' => 'Your main point of contact, setting priorities with you and reporting on results each month.', 'image' => null],
            ['name' => 'Ayesha Khan', 'role' => 'Billing Team Lead', 'text' => 'Leads charge entry and claim submission, making sure clean claims go out within 48 hours.', 'image' => null],
            ['name' => 'Michael Reyes', 'role' => 'Certified Medical Coder', 'text' => 'Reviews documentation and assigns accurate codes and modifiers for every encounter.', 'image' => null],
            ['name' => 'Emily Carter', 'role' => 'AR & Denials Specialist', 'text' => 'Chases unpaid claims, works denials and files appeals until each balance is resolved.', 'image' => null],
            ['name' => 'Omar Siddiqui', 'role' => 'Credentialing Specialist', 'text' => 'Enrolls providers with payers and keeps CAQH profiles and revalidations up to date.', 'image' => null],
        ],
        'departments_title' => 'Teams That Work *Together for You*',
        'departments_text' => 'Every practice we support is looked after by specialists from each of these teams, working together under one dedicated account manager.',
        'departments' => [
            ['icon' => 'users', 'title' => 'Account Management', 'text' => 'Your main point of contact. Account managers learn your practice, set priorities with you and keep every other team aligned with your goals.'],
            ['icon' => 'receipt', 'title' => 'Billing & Claims', 'text' => 'Charge entry, claim checks and submission, handled promptly and in line with each payer\'s requirements.'],
            ['icon' => 'code', 'title' => 'Coding & Audit', 'text' => 'Certified coders assign accurate codes, review documentation and run regular internal audits.'],
            ['icon' => 'refresh', 'title' => 'AR & Denials', 'text' => 'Specialists who chase unpaid claims, correct and appeal denials and track down underpayments.'],
            ['icon' => 'badge', 'title' => 'Credentialing & Enrollment', 'text' => 'The team that enrolls providers with payers, maintains CAQH profiles and tracks every revalidation date.'],
            ['icon' => 'clipboard', 'title' => 'Eligibility & Authorizations', 'text' => 'Specialists who verify coverage and benefits before each visit and obtain prior authorizations, so care is approved before it is given.'],
        ],
        'join_title' => 'Want to Join the *Team*?',
        'join_text' => 'We are always interested in hearing from skilled billers, coders and healthcare administrators.',
    ],

    /*
    | "header_image" is the photo behind the page title. Each service can set its own
    | "header_image" below; services without one fall back to this page's photo.
    */
    'services_page' => [
        'header_image' => 'images/photos/hero-doctors-clinic.jpg',
        'header_title' => 'Our Services',
        'primary_cta' => 'Get a Free Practice Review',
        'title' => 'Our Core Services for *Every Kind of Practice*',
        'text' => 'Three core services that cover the whole revenue cycle. Choose the support your practice needs today; every service is delivered by specialists, inside your existing systems, with one account manager accountable for results.',
        'group_label' => 'Core Service',
        'list_title' => 'What is included',
        'more' => 'Learn More',
        'why' => [
            'title' => 'One Partner for the Whole Revenue Cycle',
            'text' => 'However many services you choose, they work together under one team and one set of standards.',
            'tiles' => [
                ['icon' => 'layers', 'title' => 'Complete or Modular', 'text' => 'Hand us the whole cycle or only the stages that need help, and add more whenever you are ready.'],
                ['icon' => 'users', 'title' => 'One Accountable Team', 'text' => 'Billers, coders and AR specialists share notes and answer to one account manager.'],
                ['icon' => 'plug', 'title' => 'Inside Your Systems', 'text' => 'We work in your EHR, practice management system and clearinghouse, so nothing changes for staff.'],
                ['icon' => 'chart', 'title' => 'Results You Can See', 'text' => 'Monthly reports show collections, denials and AR in plain language, with clear next steps.'],
            ],
        ],
        'process_title' => 'Getting Started Is *Simple*',
        'process_text' => 'A clear, four-step handover that keeps your billing running the whole time.',
        'faq_title' => 'Questions About Our Services',
        'faq_text' => 'Straight answers to what practices ask before they outsource. Can\'t find yours? Our team is a phone call away.',
        'faq_cta' => 'View All FAQs',
        'cta_title' => 'Not Sure Which Services *You Need*?',
        'cta_text' => 'Tell us where your revenue gets stuck and a billing specialist will recommend practical next steps, free of charge.',
    ],

    'service_page' => [
        'primary_cta' => 'Get a Free Practice Review',
        'overview_eyebrow' => 'Service Overview',
        'sidebar_title' => 'Our Core Services',
        'features_title' => 'What This Service *Includes*',
        'features_text' => 'Every part of the service is handled by specialists, inside your existing systems.',
        'help_title' => 'Not sure where to start?',
        'help_text' => 'Tell us about your practice and we will recommend the services that will improve your collections the most, with no obligation.',
        'benefits_text' => 'What practices gain when specialists take this work off their desk.',
        'process_title' => 'How We Get *Started*',
        'process_text' => 'A clear, four-step handover that keeps your billing running the whole time.',
        'faq_title' => 'Frequently Asked Questions',
        'faq_text' => 'Quick answers about how this service works. Need more detail? Talk to our team directly.',
        'faq_cta' => 'Contact Our Team',
        'related_title' => 'Explore Our Other Core Services',
        'related_text' => 'Services that work hand in hand with this one to protect every dollar you earn.',
        'related_cta' => 'View All Services',
        'cta_title' => 'Ready to Collect More of *What You Earn*?',
        'cta_text' => 'Book a free practice review and we will show you where revenue is slipping and how to recover it.',
    ],

    'specialities_page' => [
        'eyebrow' => 'Specialities',
        'title' => 'Medical Specialities *We Support*',
        'text' => 'Every specialty has its own codes, modifiers and payer rules. Our billers and coders are trained in the details that matter to your field.',
        'cta_title' => 'Don\'t see your specialty?',
        'cta_text' => 'We support many more specialties than we can list here. Get in touch and we will tell you how we can help.',
    ],

    /*
    | Software, Compliance and Blog header photos are free to use from Pexels (no attribution
    | required): pexels.com/photo/7088530, pexels.com/photo/7578803 and pexels.com/photo/210661
    */
    'software_page' => [
        'header_image' => 'images/photos/software-hero.jpg',
        'title' => 'Software Platforms *We Work With*',
        'text' => 'Our team works directly inside the systems your practice already relies on, so there is nothing new for you to buy or learn.',
        'ehr_title' => 'EHR & Practice Management Systems',
        'ehr_text' => 'Charge entry, coding, payment posting and reporting, all done inside your own system.',
        'clearinghouse_title' => 'Clearinghouses & Payer Portals',
        'clearinghouse_text' => 'Claim submission, eligibility checks, status tracking and remittances.',
        'note' => 'Using a different system? We regularly learn new platforms. Ask us about yours.',
        'how' => [
            'title' => 'How We Work *Inside Your Systems*',
            'text' => 'Your staff keep the tools they know. We simply log in and get to work alongside them.',
            'tiles' => [
                ['icon' => 'lock', 'title' => 'Secure, Named Access', 'text' => 'Each specialist gets their own login with multi-factor authentication, and you can remove access at any time.'],
                ['icon' => 'layers', 'title' => 'Your Workflow, Unchanged', 'text' => 'We follow your templates, fee schedules and billing rules, so nothing changes for your front desk or providers.'],
                ['icon' => 'chart', 'title' => 'Reports From Your Data', 'text' => 'Monthly reports are built from your own system, so our numbers always match what you see.'],
            ],
        ],
        'form_title' => 'Ask About *Your System*',
        'form_text' => 'Tell us which software you use and how your practice is set up. We will explain exactly how we would work with it.',
        'subject' => 'Software question',
    ],

    'software' => [
        'ehr' => [
            'Epic', 'Oracle Health (Cerner)', 'athenahealth', 'eClinicalWorks',
            'NextGen Healthcare', 'Veradigm (Allscripts)', 'AdvancedMD', 'Tebra (Kareo)',
            'DrChrono', 'Practice Fusion', 'CareCloud', 'ModMed',
            'Greenway Health', 'WebPT', 'TherapyNotes', 'SimplePractice',
        ],
        'clearinghouses' => [
            'Availity', 'Office Ally', 'Waystar', 'Optum (Change Healthcare)', 'TriZetto', 'Claim.MD',
        ],
    ],

    'compliance_page' => [
        'header_image' => 'images/photos/compliance-hero.jpg',
        'highlights' => [
            ['icon' => 'file', 'label' => 'BAA signed before any access'],
            ['icon' => 'lock', 'label' => 'Multi-factor login on every system'],
            ['icon' => 'users', 'label' => 'Annual HIPAA training for all staff'],
            ['icon' => 'search', 'label' => 'Regular internal coding audits'],
        ],
        'title' => 'Protecting Patient Data Is *Part of the Job*',
        'intro' => 'Billing work means handling protected health information every single day. Here is how **Medz-Solutions** keeps that information safe and keeps our work aligned with healthcare regulations.',
        'sections' => [
            ['icon' => 'shield', 'title' => 'HIPAA Safeguards', 'text' => 'Our policies cover the administrative, physical and technical safeguards required to protect patient information.', 'points' => ['Written privacy and security policies', 'Minimum-necessary access to records', 'Regular risk assessments']],
            ['icon' => 'file', 'title' => 'Business Associate Agreements', 'text' => 'We sign a business associate agreement with every client before we access any patient data.', 'points' => ['Clear responsibilities for both parties', 'Defined breach notification steps', 'Agreements reviewed when services change']],
            ['icon' => 'lock', 'title' => 'Secure Systems & Access', 'text' => 'Access to client systems is limited, logged and protected.', 'points' => ['Unique logins with multi-factor authentication', 'Encrypted file transfer and storage', 'Access removed promptly when roles change']],
            ['icon' => 'users', 'title' => 'Staff Training', 'text' => 'Everyone on our team completes privacy and security training before starting client work and refreshes it every year.', 'points' => ['HIPAA training at onboarding', 'Annual refresher courses', 'Signed confidentiality agreements']],
            ['icon' => 'search', 'title' => 'Coding Compliance', 'text' => 'We code from documentation, follow official guidelines and audit our own work.', 'points' => ['Current ICD-10-CM, CPT and HCPCS guidance', 'NCCI edit and modifier checks', 'Internal coding audits with feedback']],
            ['icon' => 'alert', 'title' => 'Incident Response', 'text' => 'If something ever goes wrong, a documented plan guides our response.', 'points' => ['Prompt investigation of concerns', 'Notification in line with your BAA', 'Corrective actions and follow-up']],
        ],
        'form_title' => 'Contact Us for *Compliance Information*',
        'form_text' => 'Need a copy of our business associate agreement or details about our security practices? Send us a message and our team will respond.',
        'subject' => 'Compliance information request',
    ],

    /*
    | The Resources header photo is free to use from Pexels (no attribution required):
    | pexels.com/photo/48604
    */
    'resources_page' => [
        'header_image' => 'images/photos/resources-hero.jpg',
        'title' => 'Helpful Guides for *Healthcare Practices*',
        'text' => 'Practical checklists and guides to help your team improve billing, from the front desk to final payment.',
        'explore_title' => 'Explore *More Resources*',
        'explore_text' => 'Learn how we work, which systems we support and the specialties we bill for.',
        'explore' => [
            ['icon' => 'shield', 'title' => 'Compliance', 'text' => 'How we protect patient data and keep coding and billing compliant.', 'route' => 'compliance'],
            ['icon' => 'plug', 'title' => 'Software', 'text' => 'The EHR, practice management and clearinghouse systems we work in.', 'route' => 'software'],
            ['icon' => 'stethoscope', 'title' => 'Specialities', 'text' => 'The medical specialties our billers and coders support every day.', 'route' => 'specialities'],
            ['icon' => 'chat', 'title' => 'FAQs', 'text' => 'Straight answers to the questions practices ask us most often.', 'route' => 'faqs'],
        ],
        'cta_title' => 'Need Help With *Something Specific*?',
        'cta_text' => 'Our billing specialists are happy to answer your questions, with no obligation.',
        'cta_label' => 'Talk to a Specialist',
    ],

    'resources' => [
        [
            'slug' => 'front-desk-eligibility-checklist',
            'title' => 'Front-Desk Eligibility Checklist',
            'type' => 'Checklist',
            'artwork' => 'idcard',
            'excerpt' => 'A step-by-step checklist your front desk can use before every appointment to prevent eligibility denials.',
            'body' => <<<'MD'
Most eligibility denials can be prevented before the patient arrives. Use this checklist for every scheduled appointment.

## Before the visit

- Confirm the patient's full legal name and date of birth exactly as they appear on the insurance card
- Check that coverage is active on the date of service
- Identify the primary and secondary payers
- Note copay, coinsurance and remaining deductible
- Check whether a referral or prior authorization is required

## At check-in

- Scan the front and back of the insurance card and photo ID
- Ask whether the patient has changed jobs or insurance plans
- Collect the copay and any known patient balance
- Update address and phone details

## After the visit

- Make sure the encounter is closed and charges are entered the same day
- Flag any coverage questions for the billing team
MD,
        ],
        [
            'slug' => 'credentialing-document-checklist',
            'title' => 'Credentialing Document Checklist',
            'type' => 'Checklist',
            'artwork' => 'credential',
            'excerpt' => 'Everything a provider should gather before starting payer enrollment, in one list.',
            'body' => <<<'MD'
Credentialing moves much faster when every document is ready before the first application is submitted. Gather the following for each provider.

## Identity and education

- Government-issued photo ID
- Medical school diploma and residency or fellowship certificates
- Board certification details, where applicable

## Licences and registrations

- State medical licence for every state of practice
- DEA registration and any state controlled-substance registration
- National Provider Identifier (NPI)

## Practice details

- Practice tax ID (TIN) and W-9
- Practice address, phone and hours for each location
- Malpractice insurance certificate and claims history

## Professional history

- Up-to-date CV with month and year for every position
- Explanations for any gaps longer than six months
- Hospital privileges, where applicable

## Profiles to maintain

- CAQH ProView profile, attested and up to date
- Medicare enrollment through PECOS, if billing Medicare
MD,
        ],
        [
            'slug' => 'revenue-cycle-glossary',
            'title' => 'Plain-English Revenue Cycle Glossary',
            'type' => 'Glossary',
            'artwork' => 'pattern',
            'excerpt' => 'Clear definitions of the billing terms your team hears every day, from AR to ERA.',
            'body' => <<<'MD'
Billing has its own language. Here are the terms that come up most often, explained simply.

## Common terms

- **Accounts receivable (AR):** money owed to the practice for services already provided.
- **Adjustment:** a change to a balance, for example a contractual write-off agreed with a payer.
- **Clean claim:** a claim with no errors that can be processed without additional information.
- **Clearinghouse:** a service that checks claims and forwards them electronically to payers.
- **Coinsurance:** the percentage of a bill the patient pays after meeting their deductible.
- **Days in AR:** the average number of days it takes to collect payment.
- **Denial:** a claim the payer has processed but refused to pay.
- **ERA:** an electronic remittance advice, the digital explanation of a payment.
- **EOB:** an explanation of benefits, the paper or PDF version of a payment explanation.
- **Modifier:** a two-character code that adds detail to a procedure code.
- **Prior authorization:** approval from a payer before a service is provided.
- **Rejection:** a claim returned before processing, usually because of a data error.
MD,
        ],
        [
            'slug' => 'denial-prevention-playbook',
            'title' => 'Denial Prevention Playbook',
            'type' => 'Guide',
            'artwork' => 'analytics',
            'excerpt' => 'Where denials come from and the simple habits that stop most of them before they happen.',
            'body' => <<<'MD'
Working denials costs time. Preventing them costs much less. This guide groups the most common denial causes by where they start.

## Front desk

- **Eligibility:** verify coverage before every visit, not just the first one.
- **Demographics:** a single typo in a name or member ID can reject a claim.
- **Authorizations:** check requirements when the appointment is booked.

## Clinical documentation

- **Medical necessity:** make sure the diagnosis supports the service billed.
- **Signatures and dates:** unsigned notes are a frequent audit finding.

## Coding and billing

- **Modifiers:** missing or incorrect modifiers cause many bundling denials.
- **Timely filing:** know each payer's deadline and submit well before it.
- **Duplicate claims:** check claim status before resubmitting.

## Build a feedback loop

Track denials by reason every month, share the top three with the team responsible and agree on one change for each. Small, consistent fixes add up quickly.
MD,
        ],
    ],

    /*
    | Careers photos are CC0 from StockSnap (no attribution required):
    | stocksnap.io/photo/man-woman-6XELVX8KAN and stocksnap.io/photo/business-working-URDSGNSKMP
    */
    'careers_page' => [
        'header_text' => 'Help healthcare practices get paid for the care they provide, in a team that values accuracy, training and straight talk.',
        'header_image' => 'images/photos/careers-hero.jpg',
        'team_image' => 'images/photos/careers-team.jpg',
        'team_image_alt' => 'Colleagues chatting while checking their phones in a bright office',
        'perks_eyebrow' => 'Why Work With Us',
        'perks_title' => 'A Place to Build *Real Expertise*',
        'perks_text' => 'Medical billing rewards people who are careful, curious and good with people. We give you the training and the room to become excellent at it.',
        'eyebrow' => 'Careers',
        'title' => 'Build a Rewarding Career in *Healthcare Revenue*',
        'text' => 'Join a team that helps doctors, therapists and clinics get paid for the care they provide. We invest in training, certifications and clear paths to grow.',
        'perks' => [
            ['icon' => 'globe', 'title' => 'Flexible Work', 'text' => 'Remote-friendly roles with clear expectations and supportive leads.'],
            ['icon' => 'badge', 'title' => 'Certification Support', 'text' => 'Help preparing for industry credentials such as CPC and CCS.'],
            ['icon' => 'users', 'title' => 'Collaborative Team', 'text' => 'Work alongside experienced billers, coders and account managers.'],
            ['icon' => 'chart', 'title' => 'Room to Grow', 'text' => 'Defined career paths from specialist to team lead and beyond.'],
        ],
        'list_eyebrow' => 'Open Positions',
        'list_title' => 'Find *Your Role*',
        'empty' => 'No open roles match your search right now. Try another keyword or send us a general application.',
        'general_title' => 'Join Our Team Today!',
        'general_text' => 'Don\'t see the right role? We would still love to hear from experienced healthcare billing professionals.',
        'apply_title' => 'Apply for *This Role*',
        'apply_text' => 'Send us your details and CV. Our hiring team reviews every application and replies within five working days.',
    ],

    'jobs' => [
        [
            'slug' => 'medical-biller-ar-follow-up',
            'title' => 'Medical Biller – AR Follow-Up',
            'department' => 'Billing & AR',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Work unpaid and denied claims for a portfolio of practices, from payer calls to corrected claims and appeals.',
            'body' => <<<'MD'
## About the role

You will own the accounts receivable for a group of client practices, making sure every open claim moves toward payment.

## What you will do

- Review aging reports and prioritise high-value claims
- Check claim status through payer portals and phone calls
- Correct and resubmit rejected or denied claims
- Prepare appeals with the right supporting documents
- Record clear notes for every account you work

## What you will bring

- At least two years of US medical billing or AR experience
- Working knowledge of CPT, ICD-10-CM and common denial codes
- Confidence speaking with insurance representatives
- Strong attention to detail and organised note-taking
MD,
        ],
        [
            'slug' => 'certified-medical-coder',
            'title' => 'Certified Medical Coder',
            'department' => 'Coding & Audit',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Assign accurate codes for outpatient encounters across several specialties and help providers improve documentation.',
            'body' => <<<'MD'
## About the role

You will code outpatient encounters for client practices and help keep their documentation and coding accurate and compliant.

## What you will do

- Assign ICD-10-CM, CPT and HCPCS codes from clinical documentation
- Apply modifiers and check NCCI edits
- Query providers when documentation is unclear
- Take part in internal coding audits
- Keep up with annual code set and guideline updates

## What you will bring

- An active CPC, CCS or equivalent credential
- Experience coding for at least one outpatient specialty
- Excellent understanding of official coding guidelines
- Clear, professional written communication
MD,
        ],
        [
            'slug' => 'credentialing-specialist',
            'title' => 'Credentialing Specialist',
            'department' => 'Credentialing & Enrollment',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Enroll providers with commercial and government payers and keep every credential current.',
            'body' => <<<'MD'
## About the role

You will guide providers through payer enrollment and make sure their credentials never lapse.

## What you will do

- Prepare and submit commercial payer applications
- Manage CAQH ProView profiles and attestations
- Handle Medicare (PECOS) and Medicaid enrollment
- Track revalidation and expiration dates
- Follow up with payers until enrollment is complete

## What you will bring

- Two or more years of provider credentialing experience
- Familiarity with CAQH, PECOS and NPPES
- Excellent organisation and follow-through
- Comfort managing many deadlines at once
MD,
        ],
        [
            'slug' => 'eligibility-authorization-specialist',
            'title' => 'Eligibility & Authorization Specialist',
            'department' => 'Patient Access',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Verify insurance benefits and secure prior authorizations so patients are cleared before their appointments.',
            'body' => <<<'MD'
## About the role

You will make sure patients are cleared for their visits by confirming coverage and obtaining authorizations in advance.

## What you will do

- Verify eligibility and benefits for scheduled patients
- Identify services that need prior authorization
- Submit authorization requests with clinical documentation
- Follow up with payers on pending decisions
- Record results clearly in client systems

## What you will bring

- Experience with US insurance verification or authorizations
- Knowledge of payer portals such as Availity
- A calm, persistent approach on payer calls
- Accurate and timely data entry
MD,
        ],
        [
            'slug' => 'client-account-manager',
            'title' => 'Client Account Manager',
            'department' => 'Account Management',
            'location' => 'Remote',
            'type' => 'Full-time',
            'excerpt' => 'Be the trusted point of contact for client practices, turning billing data into clear advice and action.',
            'body' => <<<'MD'
## About the role

You will look after a group of client practices, understanding their goals and making sure our billing teams deliver results.

## What you will do

- Act as the main contact for client practices
- Review monthly performance reports with clients
- Coordinate billing, coding and AR teams around client priorities
- Spot trends early and recommend improvements
- Onboard new clients smoothly

## What you will bring

- Three or more years in revenue cycle management
- Strong understanding of key billing metrics
- Excellent spoken and written English
- A friendly, solutions-focused approach
MD,
        ],
    ],

    /*
    | Affiliate photos are CC0 from StockSnap (no attribution required):
    | stocksnap.io/photo/shaking-hands-GEKK2UOHCY and stocksnap.io/photo/business-meeting-SSKSJIBMMP
    |
    | Commission terms below are starter wording: confirm them against the real partner agreement.
    */
    'affiliate_page' => [
        'header_image' => 'images/photos/affiliate-hero.jpg',
        'header_title' => 'Turn Your Healthcare Network Into *Recurring Income*',
        'header_text' => 'Introduce practices to Medz-Solutions, or offer our billing services under your own brand. You build the relationships; we run the billing and keep your clients well looked after.',
        'actions' => [
            ['label' => 'Become a Partner', 'url' => '#affiliate-form', 'variant' => 'gold'],
            ['label' => 'Explore Partner Types', 'url' => '#partner-types', 'variant' => 'outline'],
        ],
        'why' => [
            'eyebrow' => 'Why Partner With Us',
            'title' => 'More Than Billing: *A Partner for Growth*',
            'text' => 'We do not just process claims. We give partners a dependable service to put their name behind, so their clients are better off and their own business grows.',
            'items' => [
                ['icon' => 'badge', 'title' => 'Expert Billing Behind Your Name', 'text' => 'Certified coders and experienced billers handle everything from coding to denials, so your clients are paid accurately.'],
                ['icon' => 'wallet', 'title' => 'A New Revenue Stream', 'text' => 'Earn recurring commissions on clients you introduce, without taking on any billing work yourself.'],
                ['icon' => 'heart', 'title' => 'Real Value for Your Clients', 'text' => 'Clients get compliant, patient-friendly billing that improves collections and keeps their staff focused on care.'],
                ['icon' => 'plug', 'title' => 'Works With Their Systems', 'text' => 'We work inside the EHR and practice management software your clients already use, with minimal disruption.'],
                ['icon' => 'layers', 'title' => 'Grows as You Grow', 'text' => 'Flexible partnership models for a solo consultant or an established company with hundreds of clients.'],
                ['icon' => 'shield', 'title' => 'Compliance You Can Rely On', 'text' => 'HIPAA-aligned processes and signed business associate agreements protect you and your clients.'],
            ],
        ],
        'tracks_eyebrow' => 'Partner Types',
        'tracks_title' => 'Programs Built Around *How You Work*',
        'tracks_text' => 'Choose the path that fits your business. Not sure which one? Apply anyway and we will help you decide.',
        'tracks' => [
            [
                'title' => 'Software Firms & Consultants',
                'label' => 'Referral partner',
                'summary' => 'Give your clients a complete billing solution without adding to your own workload.',
                'who' => 'EHR and practice management vendors, RCM consultants, healthcare technology companies and independent advisers.',
                'how' => 'You introduce your clients to Medz-Solutions. We take care of eligibility, claims, denials and collections, and keep you informed.',
                'benefits' => ['Expand what you offer without building a billing team', 'Earn recurring commissions on referred clients', 'Strengthen client loyalty with reliable results', 'Dedicated help with integrations and onboarding'],
                'cta' => 'Apply as a Referral Partner',
            ],
            [
                'title' => 'Billing & RCM Companies',
                'label' => 'White-label partner',
                'summary' => 'Use our team as your back office and keep your own brand in front of your clients.',
                'who' => 'Billing companies that want to outsource selected services or add capacity during busy periods.',
                'how' => 'We manage the work you choose, from claims and denials to AR follow-up, under your brand and your processes.',
                'benefits' => ['Extend your services without extra staff or office space', 'Scale quickly when you win new clients', 'Quality controls and accuracy checks on every account', 'More time to focus on sales and client relationships'],
                'cta' => 'Explore White-Label',
            ],
            [
                'title' => 'Marketing Agencies & Advisers',
                'label' => 'Referral or white-label',
                'summary' => 'Add medical billing to your healthcare services and let specialists run the operations.',
                'who' => 'Healthcare marketing and SEO agencies, lead generation firms, consultants and business development professionals serving US providers.',
                'how' => 'Introduce your healthcare clients to us, or choose a white-label arrangement where appropriate. You keep the client relationship.',
                'benefits' => ['Offer billing without hiring specialist staff', 'Grow revenue without day-to-day operational work', 'Structured workflows and clear reporting', 'Concentrate on campaigns and practice growth'],
                'cta' => 'Partner With Us',
            ],
            [
                'title' => 'Practices & Existing Clients',
                'label' => 'Friendly referral',
                'summary' => 'Know a colleague who is struggling with billing? Introduce them and be rewarded.',
                'who' => 'Practice owners, office managers and current Medz-Solutions clients with a network of peers.',
                'how' => 'Share a contact. We offer them a free practice review, and you are rewarded if they become a client.',
                'benefits' => ['Help a colleague fix their collections', 'A thank-you for every practice that joins', 'No sales pitch or paperwork on your side', 'Your colleague gets a free review either way'],
                'cta' => 'Refer a Practice',
            ],
        ],
        'track_labels' => ['Who it is for', 'How it works', 'What you get'],
        'steps_eyebrow' => 'Getting Started',
        'steps_title' => 'Your Path to *Partnership*',
        'steps_text' => 'Joining is simple, so you can start referring and earning quickly.',
        'steps' => [
            ['icon' => 'user', 'title' => 'Apply', 'text' => 'Send your details and choose the partnership type that suits you.'],
            ['icon' => 'chat', 'title' => 'Connect & Confirm', 'text' => 'We talk through your goals and agree the terms, including commission or white-label arrangements.'],
            ['icon' => 'wallet', 'title' => 'Start Earning', 'text' => 'You get the guidance and support you need to begin referring, and we look after the rest.'],
        ],
        'program' => [
            'image' => 'images/photos/affiliate-partners.jpg',
            'image_alt' => 'Two colleagues talking through a plan at a laptop',
            'eyebrow' => 'Why Our Affiliate Program',
            'title' => 'Your Success Depends on *Ours*',
            'paragraphs' => [
                'Medz-Solutions is a long-term growth partner for healthcare practices. We aim to keep clients for years by delivering steady, honest results, and that is exactly what protects your commissions.',
                'Practices trust us to manage their revenue. Partners trust us to look after the clients they introduce, and to keep them informed along the way.',
            ],
            'points' => ['Clear communication with no gaps or delays', 'Fast service with no hidden charges'],
            'commission_title' => 'How Commissions Work',
            'commission_text' => 'Partners earn a recurring commission for as long as a client they referred stays with us. Exact rates and any bonus arrangements are set out in your partner agreement before you refer anyone.',
            'commission_points' => ['Recurring commissions on active referrals', 'No set limit on what you can earn', 'Bonus arrangements for high-performing partners'],
        ],
        'form_eyebrow' => 'Partner With Us',
        'form_title' => 'Talk to Our *Partnership Team*',
        'form_text' => 'Fill in the form and our partnership team will answer your questions and help you get started.',
        'faq_eyebrow' => 'FAQs',
        'faq_title' => 'Affiliate Program *Questions*',
        'faqs' => [
            ['question' => 'How are my referrals tracked?', 'answer' => 'Every introduction is recorded against your partner profile, and your partner contact keeps you updated on the status of each practice you refer.'],
            ['question' => 'When do I start earning?', 'answer' => 'Commissions begin once a practice you referred has signed up and we have started billing for them. Payment timing is explained in your partner agreement.'],
            ['question' => 'Is there a limit to how many referrals I can make?', 'answer' => 'No. You can introduce as many practices as you like.'],
            ['question' => 'How long do I earn commissions?', 'answer' => 'For as long as a referred client remains active with Medz-Solutions, subject to the terms of your agreement.'],
            ['question' => 'Do I need to sell or present your services?', 'answer' => 'No. A simple introduction is enough. Our team handles the conversations, proposals and onboarding.'],
            ['question' => 'Can international partners join?', 'answer' => 'Yes, as long as the practices you introduce are based in the United States. We will explain any payment details when we connect.'],
            ['question' => 'Who can become a partner?', 'answer' => 'Consultants, software vendors, billing and marketing companies, practice managers and existing clients are all welcome to apply.'],
        ],
    ],

    'faqs_page' => [
        'eyebrow' => 'Help Centre',
        'title' => 'Answers to *Common Questions*',
        'ask_title' => 'Didn\'t find your answer?',
        'ask_text' => 'Send us your question and a member of our team will get back to you within one working day.',
    ],

    'posts_page' => [
        'header_image' => 'images/photos/blog-hero.jpg',
        'title' => 'Billing Insights for *Busy Practices*',
        'text' => 'Practical advice on billing, coding and getting paid, written by the people who do it every day.',
        'featured_label' => 'Latest Article',
        'search_label' => 'Search articles',
        'categories_title' => 'Categories',
        'latest_title' => 'Latest Articles',
        'empty' => 'No articles match your search. Try a different keyword or category.',
        'cta_title' => 'Need help with your billing?',
        'cta_text' => 'Our team is ready to answer your questions. Get in touch for a free consultation.',
    ],

    /*
    | Contact page photos are CC0 from StockSnap (no attribution required):
    | stocksnap.io/photo/woman-working-UXMLQGWQB8 and stocksnap.io/photo/doctor-working-WTWX4BZ4FD
    */
    'contact_page' => [
        'header_text' => 'Questions about billing, credentialing or switching from your current provider? Our specialists are ready to help.',
        'header_image' => 'images/photos/contact-hero.jpg',
        'eyebrow' => 'Get In Touch',
        'title' => 'Let\'s Talk About *Your Practice*',
        'body' => 'Whether you need help with denials, aging AR, credentialing or full revenue cycle management, **Medz-Solutions** would love to hear from you. Send a few details and a specialist will reply within one working day.',
        'form_title' => 'Send Us a *Message*',
        'form_text' => 'Tell us a little about your practice and the right person on our team will get back to you.',
        'side_image' => 'images/photos/contact-specialist.jpg',
        'side_image_alt' => 'A billing specialist reviewing paperwork at his desk',
        'side_badge' => 'A billing specialist replies within one working day',
        'next_steps_title' => 'What Happens Next',
        'next_steps' => [
            ['title' => 'We review your message', 'text' => 'Your enquiry goes straight to a specialist, not a sales queue.'],
            ['title' => 'We talk it through', 'text' => 'A short call to understand your practice, payers and goals.'],
            ['title' => 'You get clear recommendations', 'text' => 'A free practice review with practical next steps, no obligation.'],
        ],
        'follow_title' => 'Follow Medz-Solutions',
        'follow_text' => 'Billing tips, payer updates and news from our team. Follow us wherever you spend your time online.',
        'map_eyebrow' => 'Where We Work',
        'map_title' => 'Serving Practices *Across the USA*',
        'map_text' => 'Our remote-first team supports practices in every state. Call or email us and we will arrange a time that suits you.',
    ],

    /*
    | Messages shown after each form is submitted, keyed by form.
    */
    /*
    | Emails sent when someone submits a form. "notify" receives an alert for every new enquiry.
    | Messages are sent from MAIL_FROM_ADDRESS, and replies go to "reply_to".
    | Follow-ups go to contact and demo/review enquiries still marked "New" after "follow_up_after_days".
    */
    'emails' => [
        'notify' => env('LEADS_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')),
        'reply_to' => env('LEADS_REPLY_TO', env('MAIL_FROM_ADDRESS')),
        'follow_up_after_days' => 2,
        'confirmations' => [
            'contact' => [
                'subject' => 'We received your message',
                'body' => 'Thank you for contacting Medz-Solutions. A billing specialist has your message and will reply within one working day.',
            ],
            'meeting' => [
                'subject' => 'Your meeting request is confirmed',
                'body' => 'Thank you for requesting a meeting with Medz-Solutions. We will contact you shortly to confirm a time that suits you.',
            ],
            'review' => [
                'subject' => 'Your free practice review is on its way',
                'body' => 'Thank you for requesting a free practice review. A billing specialist is looking at your answers and will contact you within one working day with practical next steps.',
            ],
            'application' => [
                'subject' => 'We received your application',
                'body' => 'Thank you for applying to Medz-Solutions. Our hiring team reviews every application and will reply within five working days.',
            ],
            'affiliate' => [
                'subject' => 'Thanks for applying to our affiliate program',
                'body' => 'Thank you for your interest in partnering with Medz-Solutions. We review every application and will contact you within a few working days.',
            ],
        ],
        'follow_up' => [
            'subject' => 'Following up on your request',
            'body' => 'We wanted to make sure your request did not slip through the cracks. If it is easier, simply reply to this email with a good time to talk, or book a call using the button below.',
        ],
    ],

    'form_messages' => [
        'contact' => 'Thank you! Your message has been sent. We will be in touch within one working day.',
        'meeting' => 'Thank you! Your meeting request has been received. We will contact you shortly to confirm a time.',
        'review' => 'Thank you! A billing specialist will review your answers and contact you within one working day.',
        'application' => 'Thank you for applying! Our hiring team will review your application and reply within five working days.',
        'affiliate' => 'Thank you for your interest! We will review your application and contact you within a few working days.',
    ],

    /*
    | Our three core services, each with its own page. "features" are the services included
    | in it, shown as cards on the service page and as a list on the home page tabs and the
    | services page. Keep the list in the order you want it to appear.
    */
    'services' => [
        [
            'slug' => 'credentialing-enrollment',
            'title' => 'Provider Credentialing & Enrollment',
            'icon' => 'badge',
            'artwork' => 'credential',
            'header_image' => 'images/photos/hero-doctors-duo.jpg',
            'excerpt' => 'We help healthcare providers get credentialed and enrolled with insurance companies and government healthcare programs, so they can see insured patients and get paid sooner.',
            'features' => [
                ['icon' => 'badge', 'title' => 'Provider Credentialing', 'text' => 'We gather and check licences, board certifications, education, work history and malpractice cover, then prepare complete applications that meet each payer\'s credentialing standards.'],
                ['icon' => 'building', 'title' => 'Medicare and Medicaid Enrollment', 'text' => 'We complete Medicare enrollment through PECOS and state Medicaid applications for individual providers and groups, including reassignment of benefits to your practice.'],
                ['icon' => 'briefcase', 'title' => 'Commercial Insurance Enrollment', 'text' => 'We apply to the commercial payers that matter in your area and follow each application through contracting until you have an effective date and in-network status.'],
                ['icon' => 'idcard', 'title' => 'CAQH Profile Management', 'text' => 'We set up and maintain CAQH ProView profiles, upload current documents, complete regular re-attestations and authorize payers to view each profile.'],
                ['icon' => 'user', 'title' => 'NPI and PECOS Assistance', 'text' => 'We apply for or update individual and group NPIs, set up PECOS access and keep provider and practice details consistent across every record.'],
                ['icon' => 'refresh', 'title' => 'Re-credentialing and Revalidation', 'text' => 'We track every re-credentialing and Medicare revalidation date and submit early, so no provider loses network status or has claims denied for an expired enrollment.'],
                ['icon' => 'clipboard', 'title' => 'Enrollment Follow-Up and Status Tracking', 'text' => 'We follow up with payers on a set schedule, record every call and reference number, and send you clear status updates on each application.'],
            ],
            'body' => <<<'MD'
A provider who is not enrolled with a payer cannot be paid by that payer, no matter how good the care. Credentialing and enrollment involve long applications, strict document requirements and months of follow-up, and a single missing item can push your start date back. Our credentialing specialists take that work off your desk from the first application to every renewal.

## Who we help

- **New practices** opening their doors and enrolling with payers for the first time
- **Growing groups** adding new providers, locations or specialties
- **Established practices** that need their CAQH profiles, revalidations and payer records kept up to date

## How it works

1. **Document collection.** We send a clear checklist and gather licences, certifications, insurance and practice details in one secure place.
2. **Applications.** We complete and submit CAQH, Medicare, Medicaid and commercial payer applications in the order that gets you billing soonest.
3. **Follow-up.** We contact payers regularly, answer their requests quickly and keep you updated on the status of every application.
4. **Ongoing maintenance.** Once you are enrolled, we keep a calendar of expirations, re-attestations and revalidations so nothing lapses.

## Why it matters

Delays in enrollment mean visits you cannot bill and patients you may have to turn away. With one team tracking every application and deadline, your providers join networks faster and stay in network for as long as they practise with you.
MD,
            'faqs' => [
                ['question' => 'How long does credentialing take?', 'answer' => 'It varies by payer and state, and commercial payers can take several months. Starting early and submitting complete applications is the best way to avoid gaps in billing.'],
                ['question' => 'Can you help a brand-new practice?', 'answer' => 'Yes. We can enroll a new group and its providers and plan the order of applications so you can start billing as soon as possible.'],
                ['question' => 'Do you maintain credentials after enrollment?', 'answer' => 'Yes. We track revalidations, CAQH re-attestations and licence renewals for as long as we work together.'],
                ['question' => 'Can we use this service without your billing services?', 'answer' => 'Yes. Credentialing and enrollment can be used on its own or together with our billing and coding services.'],
            ],
        ],
        [
            'slug' => 'medical-billing-rcm',
            'title' => 'Medical Billing & Revenue Cycle Management (RCM)',
            'icon' => 'cycle',
            'artwork' => 'analytics',
            'header_image' => 'images/photos/home-welcome.jpg',
            'excerpt' => 'We manage the complete revenue cycle to help healthcare providers improve collections, reduce denials and maintain consistent cash flow.',
            'features' => [
                ['icon' => 'idcard', 'title' => 'Patient Eligibility and Benefits Verification', 'text' => 'We check coverage, copays, deductibles and benefit limits before each visit, so your front desk knows what to collect and claims are not denied for eligibility.'],
                ['icon' => 'clipboard', 'title' => 'Prior Authorization', 'text' => 'We identify services that need approval, submit requests with the right clinical documents and follow up until a decision is received, so care is not delayed.'],
                ['icon' => 'receipt', 'title' => 'Charge Entry and Claim Submission', 'text' => 'We enter charges from your EHR or superbills, check every claim against payer and coding edits and submit clean claims electronically within 48 hours.'],
                ['icon' => 'wallet', 'title' => 'Payment Posting', 'text' => 'We post insurance and patient payments from ERAs, EOBs and deposits every business day, so your balances and reports are always accurate.'],
                ['icon' => 'clock', 'title' => 'Accounts Receivable (AR) Follow-Up', 'text' => 'We work unpaid claims by age and value every day, so balances are resolved before timely filing limits and older AR keeps shrinking.'],
                ['icon' => 'refresh', 'title' => 'Denial Management and Appeals', 'text' => 'We find out why each claim was denied, correct and resubmit it or file a written appeal, and fix the root cause so the same denial does not return.'],
                ['icon' => 'phone', 'title' => 'Insurance Follow-Up', 'text' => 'Our team calls payers and uses their portals to chase claims with no response, missing information or processing errors, and records every outcome.'],
                ['icon' => 'check', 'title' => 'Payment Reconciliation', 'text' => 'We match payments to deposits and to your contracted rates, flag underpayments and overpayments, and resolve credit balances.'],
                ['icon' => 'chart', 'title' => 'Revenue Cycle Reporting', 'text' => 'Monthly reports show collections, denial trends, AR aging and payer performance in plain language, with clear recommendations for next steps.'],
            ],
            'body' => <<<'MD'
Revenue rarely leaks through one big mistake. It slips through small gaps between scheduling, verification, billing, posting and follow-up. Our medical billing and revenue cycle management service closes those gaps by putting one accountable team in charge of every step, from the moment a patient is scheduled to the day the final payment posts.

## From appointment to payment

1. **Before the visit.** We verify eligibility and benefits and obtain prior authorizations, so the visit is covered before care is given.
2. **After the visit.** Charges are entered, checked and submitted as clean claims within 48 hours.
3. **When payers respond.** Payments are posted and reconciled daily, and every underpayment or denial is flagged.
4. **Until it is paid.** Unpaid and denied claims are followed up, corrected and appealed until each balance is resolved.

## Why it works

Because the same team sees every stage, problems are fixed where they start instead of being patched one claim at a time. We work inside your practice management system, follow your fee schedules and billing rules, and give you one point of contact and a clear view of performance every month.

## Complete or modular

Hand us the whole revenue cycle, or only the stages that need help today, such as AR follow-up or denial management, and add more whenever you are ready.
MD,
            'faqs' => [
                ['question' => 'Do we have to outsource every part of the revenue cycle?', 'answer' => 'No. Many practices choose full revenue cycle management, but we can also take on individual stages, such as eligibility checks or AR follow-up, and expand later.'],
                ['question' => 'Can you help with old unpaid claims?', 'answer' => 'Yes. We review your aging accounts receivable during onboarding and work older balances alongside new claims, as long as they are still within payer deadlines.'],
                ['question' => 'Which payers do you bill?', 'answer' => 'We bill Medicare, Medicaid and commercial insurers, as well as workers\' compensation and auto claims when your practice needs it.'],
                ['question' => 'How do we see what is happening with our claims?', 'answer' => 'You receive monthly reports and can ask your account manager for an update at any time. You also keep full visibility in your own practice management system.'],
                ['question' => 'How soon will we see results?', 'answer' => 'Most practices notice faster claim turnaround within the first month. Improvements in older balances depend on how much AR is outstanding when we begin.'],
            ],
        ],
        [
            'slug' => 'medical-coding',
            'title' => 'Medical Coding',
            'icon' => 'code',
            'artwork' => 'security',
            'header_image' => 'images/photos/hero-doctors-laptop.jpg',
            'excerpt' => 'We provide accurate and compliant medical coding services to support proper claim submission and reimbursement.',
            'features' => [
                ['icon' => 'stethoscope', 'title' => 'ICD-10-CM Diagnosis Coding', 'text' => 'We assign diagnosis codes to the highest level of specificity the documentation supports, so medical necessity is clear to every payer.'],
                ['icon' => 'code', 'title' => 'CPT Procedure Coding', 'text' => 'We code procedures, evaluation and management visits and services accurately, so you are paid in full for the work your providers perform.'],
                ['icon' => 'pill', 'title' => 'HCPCS Level II Coding', 'text' => 'We code drugs, supplies, durable medical equipment and other services with the correct HCPCS codes and units.'],
                ['icon' => 'tag', 'title' => 'Modifier Assignment', 'text' => 'We apply the right modifiers and check National Correct Coding Initiative (NCCI) edits, avoiding bundling denials and underpayments.'],
                ['icon' => 'file', 'title' => 'Medical Record Review', 'text' => 'We review clinical notes to make sure documentation supports the codes billed, and share practical feedback with your providers.'],
                ['icon' => 'users', 'title' => 'Specialty-Specific Coding', 'text' => 'Coders are matched to your specialties, so the person coding your claims knows the rules, procedures and payer policies that apply to them.'],
                ['icon' => 'search', 'title' => 'Coding Audits and Reviews', 'text' => 'We audit a sample of your claims and documentation and give you a written report with findings, risks and recommended corrections.'],
                ['icon' => 'shield', 'title' => 'Coding Accuracy and Compliance', 'text' => 'We follow official coding guidelines and annual code set updates, and check our own work, protecting you from denials and compliance risk.'],
            ],
            'body' => <<<'MD'
Coding is where clinical care becomes a claim. Small mistakes, such as a missing modifier or an unspecified diagnosis, can lead to denials, underpayments or compliance risk. Our certified coders make sure every encounter is coded completely and correctly, using current ICD-10-CM, CPT and HCPCS Level II guidelines.

## How it works

1. **Documentation review.** Coders read the provider's notes for each encounter, not just the charges.
2. **Code assignment.** Diagnosis, procedure and supply codes and modifiers are assigned and checked against payer edits.
3. **Queries and feedback.** When documentation is unclear or incomplete, we ask the provider before the claim goes out.
4. **Regular audits.** We audit our own work and share results with you, so accuracy keeps improving.

## Better documentation, better claims

When we notice documentation gaps, we share practical feedback with your providers, helping future notes fully support the care they deliver and reducing the risk of payer audits.
MD,
            'faqs' => [
                ['question' => 'Are your coders certified?', 'answer' => 'Our coding team holds industry credentials such as CPC or CCS and completes training on every annual code set update.'],
                ['question' => 'Can you code for several specialties?', 'answer' => 'Yes. Coders are matched to your specialties, so the person coding your claims understands the rules that apply to them.'],
                ['question' => 'Do you offer standalone coding audits?', 'answer' => 'Yes. We can audit a sample of your claims and documentation and give you a written report, even if we do not handle your billing.'],
                ['question' => 'How quickly are encounters coded?', 'answer' => 'Turnaround is agreed with you during onboarding, so coding never holds up claim submission.'],
            ],
        ],
    ],

    /*
    | Addresses of service pages that were merged into the core services above. Visitors and
    | search engines following an old link are sent permanently to the page that replaced it.
    */
    'service_redirects' => [
        'credentialing' => 'credentialing-enrollment',
        'revenue-cycle-management' => 'medical-billing-rcm',
        'medical-billing' => 'medical-billing-rcm',
        'eligibility-verification' => 'medical-billing-rcm',
        'prior-authorization' => 'medical-billing-rcm',
        'denial-management' => 'medical-billing-rcm',
        'ar-follow-up' => 'medical-billing-rcm',
        'payment-posting' => 'medical-billing-rcm',
        'patient-billing' => 'medical-billing-rcm',
        'practice-reporting' => 'medical-billing-rcm',
        'billing-audit' => 'medical-coding',
    ],

    'specialities' => [
        ['name' => 'Family & Internal Medicine', 'icon' => 'stethoscope', 'text' => 'Preventive visits, chronic care management and high daily volumes billed accurately.'],
        ['name' => 'Cardiology', 'icon' => 'heart', 'text' => 'Diagnostic testing, device monitoring and procedures coded with the right modifiers.'],
        ['name' => 'Orthopedics', 'icon' => 'bone', 'text' => 'Surgical global periods, injections and bracing billed in line with payer rules.'],
        ['name' => 'Physical Therapy', 'icon' => 'pulse', 'text' => 'Timed-unit coding, KX modifier thresholds and plan-of-care tracking.'],
        ['name' => 'Behavioral Health', 'icon' => 'brain', 'text' => 'Psychotherapy, psychiatric evaluations and telehealth sessions billed correctly.'],
        ['name' => 'Dermatology', 'icon' => 'spark', 'text' => 'Biopsies and lesion removals, with cosmetic and medical services kept separate.'],
        ['name' => 'Pediatrics', 'icon' => 'baby', 'text' => 'Well-child visits, vaccines and administration codes captured in full.'],
        ['name' => 'Gastroenterology', 'icon' => 'stethoscope', 'text' => 'Screening versus diagnostic procedure rules and anesthesia coordination.'],
        ['name' => 'Neurology', 'icon' => 'brain', 'text' => 'EEG, EMG and nerve conduction studies with precise documentation.'],
        ['name' => 'Oncology', 'icon' => 'pill', 'text' => 'Infusion coding hierarchies, drug units and authorizations for treatment.'],
        ['name' => 'Obstetrics & Gynecology', 'icon' => 'baby', 'text' => 'Global maternity billing, ultrasounds and gynecologic procedures.'],
        ['name' => 'Urology', 'icon' => 'stethoscope', 'text' => 'In-office procedures, diagnostics and supplies billed together correctly.'],
        ['name' => 'Radiology & Imaging', 'icon' => 'scan', 'text' => 'Professional and technical components with authorization tracking.'],
        ['name' => 'Anesthesiology', 'icon' => 'clock', 'text' => 'Base units, time units and medical direction modifiers.'],
        ['name' => 'Pain Management', 'icon' => 'pulse', 'text' => 'Injections, nerve blocks and the documentation payers expect for them.'],
        ['name' => 'Chiropractic', 'icon' => 'bone', 'text' => 'Manipulation levels, active-care documentation and visit limits.'],
        ['name' => 'Podiatry', 'icon' => 'bone', 'text' => 'Routine foot care rules, wound care and supplies.'],
        ['name' => 'Ophthalmology & Optometry', 'icon' => 'eye', 'text' => 'Medical versus vision plan billing, diagnostic testing and surgery.'],
        ['name' => 'Urgent Care', 'icon' => 'plus', 'text' => 'Quick turnaround for walk-in visits and high patient volumes.'],
        ['name' => 'Laboratory & Pathology', 'icon' => 'flask', 'text' => 'Test panels, medical necessity checks and accurate test coding.'],
        ['name' => 'Telehealth', 'icon' => 'video', 'text' => 'Place-of-service codes, modifiers and payer-specific virtual care rules.'],
        ['name' => 'Ambulatory Surgery Centers', 'icon' => 'building', 'text' => 'Facility fees, implants and multiple-procedure payment rules.'],
    ],

    'faqs' => [
        ['question' => 'What does Medz-Solutions do?', 'answer' => 'We offer three core services: provider credentialing and enrollment, medical billing and revenue cycle management (RCM), and medical coding. Together they cover the work needed to get your practice paid, from enrolling providers with payers to verifying insurance, coding, submitting claims, posting payments, following up on unpaid claims and appealing denials. You stay in control and receive regular reports.'],
        ['question' => 'How much do your services cost?', 'answer' => 'Pricing depends on your specialty, claim volume and which core services you need. Many practices choose a percentage of collections, so our success is tied to yours. We provide a clear quote after a free practice review.'],
        ['question' => 'How long does it take to get started?', 'answer' => 'A typical onboarding takes around 30 days from signed agreements, depending on your systems and the number of providers. We plan the transition so billing never stops.'],
        ['question' => 'Do we need to change our software?', 'answer' => 'No. We work inside your existing EHR, practice management system and clearinghouse.'],
        ['question' => 'Is our patient data safe with you?', 'answer' => 'Yes. We sign a business associate agreement before accessing any data, limit access to the people who need it and use secure, encrypted systems.'],
        ['question' => 'Will we still be able to see our billing data?', 'answer' => 'Always. Your data stays in your systems and you receive monthly reports, with your account manager available to answer questions at any time.'],
        ['question' => 'Can you help with old unpaid claims?', 'answer' => 'Yes. We review your aging accounts receivable during onboarding and work older balances alongside new claims, as long as they are still within payer deadlines.'],
        ['question' => 'Are we locked into a long contract?', 'answer' => 'Our agreements include a clear notice period, and we aim to keep your business by delivering results, not by locking you in.'],
    ],

];
