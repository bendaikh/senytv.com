
    <label class="form-label">{{ $label ?? 'Language' }}</label>
    <select class="form-select" name="{{ $name }}" required>
        @foreach ($languages as $langCode)
            <option value="{{ $langCode }}"
                {{ old($name, $value ?? '') == $langCode ? 'selected' : '' }}>
                {{ $languageNames[$langCode] ?? strtoupper($langCode) }}
                ({{ strtoupper($langCode) }})
            </option>
        @endforeach
    </select>
