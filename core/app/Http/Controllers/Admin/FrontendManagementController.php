<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Partner;
use App\Models\Setting;

class FrontendManagementController extends Controller
{

    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.frontend-management.program-slider', compact('sliders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'img' => 'required|image',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'slider_background' => 'required|image',
            'order' => 'nullable|integer',
            'link' => 'nullable|string',
        ]);

        $imgPath = $this->uploadOrFail($request, 'img', 'sliders');
        $bgPath = $this->uploadOrFail($request, 'slider_background', 'sliders');

        Slider::create([
            'img' => $imgPath,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'slider_background' => $bgPath,
            'order' => $this->getOrder($validated['order'] ?? null),
            'link' => $validated['link'] ?? null,
        ]);

        return back()->with('success', 'Slider created successfully.');
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'img' => 'nullable|image',
            'title' => 'required|string',
            'subtitle' => 'required|string',
            'slider_background' => 'nullable|image',
            'order' => 'nullable|integer',
            'link' => 'nullable|string',
        ]);

        if ($request->hasFile('img')) {
            $this->deleteIfExists($slider->img);
            $slider->img = $this->uploadOrFail($request, 'img', 'sliders');
        }

        if ($request->hasFile('slider_background')) {
            $this->deleteIfExists($slider->slider_background);
            $slider->slider_background = $this->uploadOrFail($request, 'slider_background', 'sliders');
        }

        $slider->fill([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'order' => $this->getOrder($validated['order'] ?? null),
            'link' => $validated['link'] ?? null,
        ])->save();

        return back()->with('success', 'Slider updated successfully.');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        $this->deleteIfExists($slider->img);
        $this->deleteIfExists($slider->slider_background);

        $slider->delete();

        return back()->with('success', 'Slider deleted successfully.');
    }

    public function LandingPage()
    {
        return view('admin.frontend-management.index');

    }

    public function PartnersIndex(Request $request)
    {
        $perPage = $request->input('perPage', 15);
        $search = $request->input('search', '');

        $partners = Partner::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.frontend-management.partners', compact('partners'));
    }


    public function PartnersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:partners,name',
            'alt' => 'nullable|string',
            'logo' => 'required|image:allow_svg|mimes:png,jpg,jpeg,webp,svg',
        ]);

        $logoPath = $this->uploadOrFail($request, 'logo', 'partners');

        Partner::create([
            'name' => $validated['name'],
            'alt' => $validated['alt'] ?? null,
            'logo' => $logoPath,
        ]);

        return back()->with('success', 'Partner created successfully.');
    }

    public function PartnersUpdate(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => "required|string|unique:partners,name,{$partner->id}",
            'alt' => 'nullable|string',
            'logo' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg',
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteIfExists($partner->logo);
            $partner->logo = $this->uploadOrFail($request, 'logo', 'partners');
        }

        $partner->fill([
            'name' => $validated['name'],
            'alt' => $validated['alt'] ?? null,
        ])->save();

        return back()->with('success', 'Partner updated successfully.');
    }

    public function PartnersDestroy($id)
    {
        $partner = Partner::findOrFail($id);

        $this->deleteIfExists($partner->logo);
        $partner->delete();

        return back()->with('success', 'Partner deleted successfully.');
    }

    public function ImagesManagerIndex()
    {
        $images = Setting::whereIn('key', ['img_slider_1', 'img_slider_2', 'img_slider_3', 'best_section_img', 'our_partners_img'])
            ->pluck('value', 'key');
        return view('admin.frontend-management.images-manager', compact('images'));
    }

    public function ImagesManageUpdate(Request $request)
    {
        $request->validate([
            'img_slider_1' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'img_slider_2' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'img_slider_3' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'best_section_img' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'our_partners_img' => 'nullable|image:allow_svg|mimes:png,jpg,jpeg,webp,svg|max:2048',
        ]);

        foreach ([1, 2, 3] as $i) {
            $field = 'img_slider_' . $i;
            if ($request->hasFile($field)) {
                $old = Setting::getSetting($field);
                $this->deleteIfExists($old);
                $newPath = $this->uploadOrFail($request, $field, 'top-sliders');
                Setting::setSetting($field, $newPath);
            }
        }


        if ($request->hasFile('best_section_img')) {
            $oldBestImg = Setting::getSetting('best_section_img');
            $this->deleteIfExists($oldBestImg);
            $newBestPath = $this->uploadOrFail($request, 'best_section_img', 'best-section');
            Setting::setSetting('best_section_img', $newBestPath);
        }

        if ($request->hasFile('our_partners_img')) {
            $oldPartnersImg = Setting::getSetting('our_partners_img');
            $this->deleteIfExists($oldPartnersImg);
            $newPartnersPath = $this->uploadOrFail($request, 'our_partners_img', 'partners');
            Setting::setSetting('our_partners_img', $newPartnersPath);
        }


        return back()->with('success', 'Images updated successfully.');
    }



    /** --------------------
     * Shared Helper Methods
     * -------------------- */

    private function uploadOrFail(Request $request, string $field, string $path): string
    {
        $file = $request->file($field);
        $uploaded = uploadImage($file, $path);
        abort_unless($uploaded !== null, 422, "{$field} upload failed.");
        return $uploaded;
    }

    private function deleteIfExists(?string $path): void
    {
        if ($path)
            deleteImage($path);
    }

    private function getOrder(?int $order): int
    {
        return $order ?? (Slider::max('order') + 1 ?? 1);
    }
}
