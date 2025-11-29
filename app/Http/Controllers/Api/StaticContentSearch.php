<?php

namespace App\Http\Controllers\Api;

/**
 * Static Content Search Index
 * Contains all searchable static content from the website
 */
class StaticContentSearch
{
    /**
     * Get all static content indexed for search
     */
    public static function getStaticContent($language = 'en')
    {
        $content = [
            // Home Page Sections
            [
                'id' => 'home-who-we-are',
                'type' => 'static_page',
                'title_en' => 'How do we build our country?',
                'title_bn' => 'কিভাবে দেশ গড়বো?',
                'content_en' => 'Together for a Better Tomorrow. When I was studying at Oxford, I observed something powerful: no matter how many divisions existed between groups, everyone came together to tackle local problems. The common good took precedence over individual interest. So why can\'t we do the same for our future?',
                'content_bn' => 'আগামী গড়ি মিলেমিশে ভোট দিন ধানের শীষে। বাংলাদেশের মানুষ সম্মানের সাথে মাথা উঁচু করে বাঁচতে চায়। কিন্তু জনস্বার্থে সকলে মিলে দীর্ঘমেয়াদী পরিকল্পনা বাস্তবায়নে আমরা কতটা সক্ষম? অক্সফোর্ডে পড়াকালিন সময় দেখেছি বিভিন্ন গোষ্ঠীর মাঝে যতই বিরোধ থাকুক, স্থানীয় সমস্যা সমাধানে সবাই মিলে কাজ করে। ক্ষুদ্র ব্যক্তিস্বার্থের তুলনায় সামষ্টিক সমৃদ্ধি বেশি প্রাধান্য পায়। নিজেদের ভবিষ্যতের স্বার্থে আমরা কেন পারবো না একযোগে কাজ করতে?',
                'url' => '/',
                'section' => 'Who We Are'
            ],
            [
                'id' => 'home-our-concern',
                'type' => 'static_page',
                'title_en' => 'Our Challenges',
                'title_bn' => 'আমাদের উদ্বেগের বিষয়গুলি',
                'content_en' => 'Identify the right job at the right time, choose the right leader. In the past two decades, the people of Panchagarh have seen few tangible solutions to their problems. Now is the opportunity to fulfil incomplete promises and bring genuine smiles to people\'s faces. The fragile state of our education and healthcare systems. Rising unemployment and economic downturn. The hardships faced by farmers and workers. Modernising public transport systems. Limited civic amenities in urban and semi-urban areas. Overall deterioration of roads and connectivity. A drastic decline in law and order.',
                'content_bn' => 'উপযুক্ত সময়ে উপযুক্ত কাজের জন্য উপযুক্ত নেতা। পঞ্চগড়ের জনগণের সমস্যাগুলো সমাধানে বিগত দুই দশকে উল্লেখযোগ্য কোন কাজ হয়নি। এবার সুযোগ এসেছে অপূর্ণ আবশ্যকতা গুলো সম্পূর্ণ করে মানুষের মুখে নিশ্চিন্ত হাসি ফোটাবার। শিক্ষা ও স্বাস্থ্যব্যবস্থার নাজুক অবস্থা। ক্রমবর্ধমান বেকারত্ব ও অর্থনৈতিক মন্দা। কৃষক-শ্রমিকের দুর্দশা। গণ যোগাযোগ ব্যবস্থা যুগপোযগীকরণ। পৌর-অঞ্চলে সীমিত নাগরিক সুবিধা। রাস্তা-ঘাট সহ যোগাযোগ ব্যবস্থার সার্বিক দুরাবস্থা। আইন-শৃঙ্খলা পরিস্থিতির তীব্র অবনতি।',
                'url' => '/',
                'section' => 'Our Concern'
            ],
            [
                'id' => 'home-why-choose-us',
                'type' => 'static_page',
                'title_en' => 'Why Stand With Me?',
                'title_bn' => 'কেন আমার পাশে দাঁড়াবেন?',
                'content_en' => 'If you believe in a prosperous future, then I ask for your vote. A country where life is peaceful, where dreams can be built without fear — this is not just a promise, it is our strong commitment. My pledges: Establishing a university and a medical college in Panchagarh. Sustainable solutions to unemployment in Panchagarh. Modern Panchagarh municipality and upgraded infrastructure. A fully‐paved road network right to the village level. Zero tolerance for corruption, and protection of the law and human rights. Fair pricing for fertiliser, seeds, and agricultural produce. Comprehensive development of the tourism industry in Panchagarh.',
                'content_bn' => 'যদি সমৃদ্ধ ভবিষ্যৎ চান, আমাকে ভোট দিন। একটি দেশ, যেখানে জীবন হবে স্বস্তির; যেখানে নির্ভয়ে স্বপ্ন গড়া যাবে, এটি শুধু প্রতিশ্রুতি নয়, আমাদের দৃঢ় অঙ্গীকার। আমার ওয়াদা: বিশ্ববিদ্যালয় ও মেডিক্যাল কলেজ স্থাপন। বেকারত্ব নিরসনে টেকসই সমাধান। আধুনিক পৌরসভা ও উন্নত অবকাঠামো। গ্রাম পর্যন্ত পাকা রাস্তার নেটওয়ার্ক। দুর্নীতি প্রতিরোধ, আইন ও মানবাধিকারের সুরক্ষা। সার, বীজ ও কৃষিজাত পণ্যের ন্যায্যমূল্য। পর্যটন শিল্পের সার্বিক উন্নয়ন।',
                'url' => '/',
                'section' => 'Why Choose Us'
            ],
            // About Page
            [
                'id' => 'about-page',
                'type' => 'static_page',
                'title_en' => 'About Me',
                'title_bn' => 'আমার সম্পর্কে',
                'content_en' => 'Muhammad Nawshad Zamir, the International Affairs Secretary of the Bangladesh Nationalist Party (BNP), is a barrister, politician and international law expert. He holds an LLM degree from the world-renowned Harvard Law School and an MSc degree in taxation from Oxford University. He is a Senior Advocate of the Supreme Court of Bangladesh and a Barrister-at-Law at Lincoln\'s Inn, London. He is the lawyer of former Prime Minister and national leader Begum Khaleda Zia and BNP Acting Chairperson Mr. Tarique Rahman.',
                'content_bn' => 'বাংলাদেশ জাতীয়তাবাদী দলের (বিএনপি) আন্তর্জাতিক বিষয়ক সম্পাদক মুহাম্মদ নওশাদ জমির একাধারে একজন ব্যারিস্টার, রাজনীতিবিদ এবং আন্তর্জাতিক আইন বিশেষজ্ঞ। তিনি বিশ্বখ্যাত হার্ভার্ড ল স্কুল থেকে এলএলএম ডিগ্রি এবং অক্সফোর্ড বিশ্ববিদ্যালয় থেকে ট্যাক্সেশনের ওপর এমএসসি ডিগ্রি অর্জন করেন। তিনি বাংলাদেশ সুপ্রিম কোর্টের একজন অ্যাডভোকেট এবং লন্ডনের লিংকনস ইন-এর ব্যারিস্টার-অ্যাট-ল হিসেবে কর্মরত আছেন। তিনি সাবেক প্রধানমন্ত্রী দেশনেত্রী বেগম খালেদা জিয়া এবং বিএনপি-র ভারপ্রাপ্ত চেয়ারপার্সন জনাব তারেক রহমানের আইনজীবী।',
                'url' => '/about',
                'section' => 'About'
            ],
            // Contact Page
            [
                'id' => 'contact-page',
                'type' => 'static_page',
                'title_en' => 'Contact Us',
                'title_bn' => 'যোগাযোগ',
                'content_en' => 'Get in touch with us. Send us a message and we will get back to you.',
                'content_bn' => 'আমাদের সাথে যোগাযোগ করুন। আমাদের একটি বার্তা পাঠান এবং আমরা আপনার কাছে ফিরে আসব।',
                'url' => '/contact-us',
                'section' => 'Contact'
            ],
            // Gallery Page
            [
                'id' => 'gallery-page',
                'type' => 'static_page',
                'title_en' => 'Gallery',
                'title_bn' => 'গ্যালারি',
                'content_en' => 'View our photo gallery and events.',
                'content_bn' => 'আমাদের ফটো গ্যালারি এবং ইভেন্ট দেখুন।',
                'url' => '/gallery',
                'section' => 'Gallery'
            ],
            // How to Build Page
            [
                'id' => 'how-to-build-page',
                'type' => 'static_page',
                'title_en' => 'How to Build',
                'title_bn' => 'কিভাবে গড়বো',
                'content_en' => 'Learn how we plan to build our country together. Building a stronger future through unity and leadership.',
                'content_bn' => 'আমরা কীভাবে একসাথে দেশ গড়ার পরিকল্পনা করি তা জানুন। মিলেমিশে নেতৃত্বের মাধ্যমে একটি শক্তিশালী ভবিষ্যত গড়া।',
                'url' => '/how-to-build',
                'section' => 'How to Build'
            ],
            // Leadership Section
            [
                'id' => 'leadership-section',
                'type' => 'static_page',
                'title_en' => 'Leadership',
                'title_bn' => 'নেতৃত্ব',
                'content_en' => 'Muhammad Nawshad Zamir - Barrister, International Affairs Secretary of BNP, Harvard Law School LLM, Oxford University MSc in Taxation, Senior Advocate of Supreme Court of Bangladesh, Barrister-at-Law at Lincoln\'s Inn London.',
                'content_bn' => 'মুহাম্মদ নওশাদ জমির - ব্যারিস্টার, বিএনপির আন্তর্জাতিক বিষয়ক সম্পাদক, হার্ভার্ড ল স্কুল এলএলএম, অক্সফোর্ড বিশ্ববিদ্যালয় ট্যাক্সেশনে এমএসসি, বাংলাদেশ সুপ্রিম কোর্টের সিনিয়র অ্যাডভোকেট, লন্ডনের লিংকনস ইন-এর ব্যারিস্টার-অ্যাট-ল।',
                'url' => '/about',
                'section' => 'Leadership'
            ],
            // Educational Career
            [
                'id' => 'educational-career',
                'type' => 'static_page',
                'title_en' => 'Educational Career',
                'title_bn' => 'শিক্ষাজীবন',
                'content_en' => 'Harvard Law School LLM, University of Oxford MSc in Taxation, Dhaka University LLB LLM First Class First.',
                'content_bn' => 'হার্ভার্ড ল স্কুল এলএলএম, অক্সফোর্ড বিশ্ববিদ্যালয় ট্যাক্সেশনে এমএসসি, ঢাকা বিশ্ববিদ্যালয় এলএলবি এলএলএম ফার্স্ট ক্লাস ফার্স্ট।',
                'url' => '/about',
                'section' => 'Education'
            ],
            // Career Section
            [
                'id' => 'career-section',
                'type' => 'static_page',
                'title_en' => 'Career',
                'title_bn' => 'কর্মজীবন',
                'content_en' => 'International Affairs Secretary BNP, Lawyer of Begum Khaleda Zia and Tarique Rahman, BNP candidate for Panchagarh-1 constituency in 11th and 13th National Parliament Election, Director General Prime Minister\'s Office 2006, Legal Advisor Ministry of Home Affairs 2006-2007, Legal expert in international arbitration Islamic finance and project finance.',
                'content_bn' => 'বিএনপি-র আন্তর্জাতিক বিষয়ক সম্পাদক, বেগম খালেদা জিয়া এবং জনাব তারেক রহমানের আইনজীবী, একাদশ ও ত্রয়োদশ জাতীয় সংসদ নির্বাচনে পঞ্চগড়-১ আসনে বিএনপির প্রার্থী, প্রধানমন্ত্রীর কার্যালয়ের মহাপরিচালক ২০০৬, স্বরাষ্ট্র মন্ত্রণালয়ের আইন উপদেষ্টা ২০০৬-২০০৭, আন্তর্জাতিক সালিশি ইসলামী অর্থায়ন ও প্রকল্প অর্থায়নের আইনি বিশেষজ্ঞ।',
                'url' => '/about',
                'section' => 'Career'
            ],
            // Become Volunteer
            [
                'id' => 'become-volunteer',
                'type' => 'static_page',
                'title_en' => 'Become a Volunteer',
                'title_bn' => 'স্বেচ্ছাসেবক হন',
                'content_en' => 'Join us as a volunteer and help build a better future for our country. Together we can make a difference.',
                'content_bn' => 'আমাদের সাথে স্বেচ্ছাসেবক হিসেবে যোগ দিন এবং আমাদের দেশের জন্য একটি উন্নত ভবিষ্যত গড়তে সাহায্য করুন। একসাথে আমরা পরিবর্তন আনতে পারি।',
                'url' => '/',
                'section' => 'Volunteer'
            ],
            // Get Involved
            [
                'id' => 'get-involved',
                'type' => 'static_page',
                'title_en' => 'Get Involved',
                'title_bn' => 'জড়িত হন',
                'content_en' => 'Get involved in our mission to build a better future. Your participation matters.',
                'content_bn' => 'একটি উন্নত ভবিষ্যত গড়ার আমাদের মিশনে জড়িত হন। আপনার অংশগ্রহণ গুরুত্বপূর্ণ।',
                'url' => '/',
                'section' => 'Get Involved'
            ],
        ];

        return $content;
    }

    /**
     * Search static content
     */
    public static function search($query, $language = 'en')
    {
        $allContent = self::getStaticContent($language);
        $searchWords = preg_split('/\s+/', mb_strtolower($query, 'UTF-8'));
        $searchWords = array_filter($searchWords, function($word) {
            return mb_strlen(trim($word), 'UTF-8') > 0;
        });

        if (empty($searchWords)) {
            $searchWords = [mb_strtolower($query, 'UTF-8')];
        }

        $results = [];

        foreach ($allContent as $item) {
            $titleField = $language === 'bn' ? 'title_bn' : 'title_en';
            $contentField = $language === 'bn' ? 'content_bn' : 'content_en';
            
            $title = $item[$titleField] ?? $item['title_en'];
            $content = $item[$contentField] ?? $item['content_en'];
            
            $titleLower = mb_strtolower($title, 'UTF-8');
            $contentLower = mb_strtolower($content, 'UTF-8');
            $queryLower = mb_strtolower($query, 'UTF-8');

            $matched = false;
            $relevanceScore = 0;

            // Check full query match
            if (mb_strpos($titleLower, $queryLower) !== false) {
                $matched = true;
                $relevanceScore += 100;
            }
            if (mb_strpos($titleLower, $queryLower) === 0) {
                $relevanceScore += 50;
            }
            if (mb_strpos($contentLower, $queryLower) !== false) {
                $matched = true;
                $relevanceScore += 30;
            }

            // Check individual words
            foreach ($searchWords as $word) {
                if (mb_strpos($titleLower, $word) !== false) {
                    $matched = true;
                    $relevanceScore += 10;
                }
                if (mb_strpos($contentLower, $word) !== false) {
                    $matched = true;
                    $relevanceScore += 5;
                }
            }

            if ($matched) {
                $description = mb_substr(strip_tags($content), 0, 150, 'UTF-8');
                if (mb_strlen($content, 'UTF-8') > 150) {
                    $description .= '...';
                }

                $results[] = [
                    'id' => $item['id'],
                    'type' => 'static_page',
                    'title' => $title,
                    'description' => $description,
                    'url' => $item['url'],
                    'section' => $item['section'] ?? '',
                    'created_at' => now(),
                    'relevance_score' => $relevanceScore,
                ];
            }
        }

        // Sort by relevance
        usort($results, function($a, $b) {
            return $b['relevance_score'] - $a['relevance_score'];
        });

        return $results;
    }
}

