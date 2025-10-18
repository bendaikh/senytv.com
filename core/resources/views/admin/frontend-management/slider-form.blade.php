<div class="mb-3">
    <label class="form-label">Slider Background</label>
    <input type="file" name="slider_background" class="form-control" {{ isset($slider) ? '' : 'required' }}>
    @if (isset($slider) && $slider->slider_background)
        <img src="{{ asset($slider->slider_background) }}" width="50" height="50" class="mt-2">
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="img" class="form-control" {{ isset($slider) ? '' : 'required' }}>
    @if (isset($slider) && $slider->img)
        <img src="{{ asset($slider->img) }}" width="50" height="50" class="mt-2">
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title ?? '') }}" required>
    <small class="form-text text-info">
        Please add <code>&lt;span&gt;Text Here&lt;/span&gt;</code> if you want to colorize it with the primary color.
    </small>
</div>


<div class="mb-3">
    <label class="form-label">Subtitle</label>
    <input type="text" name="subtitle" class="form-control"
        value="{{ old('subtitle', isset($slider) ? $slider->subtitle : '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Order</label>
    <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order ?? '') }}">
</div>
