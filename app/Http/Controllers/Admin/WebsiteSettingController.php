<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function __construct(private WebsiteSettingService $settings) {}

    public function edit()
    {
        return view('admin.settings.website', $this->settings->allWebsite());
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'hours' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'max:150'],
            'domain' => ['nullable', 'string', 'max:150'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:150'],
            'hero_tagline' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'carousel_title' => ['nullable', 'array'],
            'carousel_title.*' => ['nullable', 'string', 'max:150'],
            'carousel_text' => ['nullable', 'array'],
            'carousel_text.*' => ['nullable', 'string'],
            'carousel_image' => ['nullable', 'array'],
            'carousel_image.*' => ['nullable', 'image', 'max:4096'],
            'banner_title' => ['nullable', 'array'],
            'banner_title.*' => ['nullable', 'string', 'max:150'],
            'banner_link' => ['nullable', 'array'],
            'banner_link.*' => ['nullable', 'string', 'max:255'],
            'banner_image' => ['nullable', 'array'],
            'banner_image.*' => ['nullable', 'image', 'max:4096'],
            'why_choose_image' => ['nullable', 'image', 'max:5120'],
            'why_choose_bg' => ['nullable', 'image', 'max:5120'],
        ]);

        $branding = $this->settings->get('site_branding');
        $branding['site_name'] = $data['site_name'];
        $branding['tagline'] = $data['tagline'] ?? '';
        if ($path = $this->settings->storeUpload($request->file('logo'), 'settings')) {
            $branding['logo_path'] = $path;
        }
        if ($path = $this->settings->storeUpload($request->file('favicon'), 'settings')) {
            $branding['favicon_path'] = $path;
        }

        $header = $this->settings->get('site_header');
        foreach (['hours', 'location', 'phone', 'facebook', 'instagram', 'youtube'] as $field) {
            $header[$field] = $data[$field] ?? '';
        }

        $footer = $this->settings->get('site_footer');
        foreach (['about', 'email', 'domain', 'copyright'] as $field) {
            $footer[$field] = $data[$field] ?? '';
        }

        $hero = $this->settings->get('landing_hero');
        $hero['title'] = $data['hero_title'] ?? $hero['title'];
        $hero['tagline'] = $data['hero_tagline'] ?? $hero['tagline'];
        $hero['subtitle'] = $data['hero_subtitle'] ?? $hero['subtitle'];

        $existingSlides = $this->settings->get('landing_carousel')['slides'] ?? [];
        $slides = [];
        foreach ($data['carousel_title'] ?? [] as $i => $title) {
            $text = $data['carousel_text'][$i] ?? '';
            $hasImage = $request->hasFile("carousel_image.$i");
            if (! filled($title) && ! filled($text) && ! $hasImage && empty($existingSlides[$i]['image'])) {
                continue;
            }
            $image = $existingSlides[$i]['image'] ?? null;
            if ($hasImage) {
                $image = $this->settings->storeUpload($request->file("carousel_image.$i"), 'carousel');
            }
            $slides[] = compact('title', 'text', 'image') + ['title' => $title, 'text' => $text, 'image' => $image];
        }

        $existingBanners = $this->settings->get('landing_banners')['items'] ?? [];
        $bannerItems = [];
        foreach ($data['banner_title'] ?? [] as $i => $title) {
            $hasImage = $request->hasFile("banner_image.$i");
            if (! filled($title) && ! $hasImage && empty($existingBanners[$i]['image'])) {
                continue;
            }
            $image = $existingBanners[$i]['image'] ?? null;
            if ($hasImage) {
                $image = $this->settings->storeUpload($request->file("banner_image.$i"), 'carousel');
            }
            $bannerItems[] = [
                'title' => $title,
                'link' => $data['banner_link'][$i] ?? '',
                'image' => $image,
            ];
        }

        $this->settings->put('site_branding', $branding);
        $this->settings->put('site_header', $header);
        $this->settings->put('site_footer', $footer);
        $this->settings->put('landing_hero', $hero);
        $this->settings->put('landing_carousel', ['slides' => $slides]);
        $this->settings->put('landing_banners', ['items' => $bannerItems]);

        $sections = $this->settings->get('landing_sections');
        if ($path = $this->settings->storeUpload($request->file('why_choose_image'), 'sections')) {
            $sections['why_choose_image'] = $path;
        }
        if ($path = $this->settings->storeUpload($request->file('why_choose_bg'), 'sections')) {
            $sections['why_choose_bg'] = $path;
        }
        $this->settings->put('landing_sections', $sections);

        return back()->with('status', 'Pengaturan website disimpan.');
    }
}
