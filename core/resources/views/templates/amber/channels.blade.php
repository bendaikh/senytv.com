@extends($activeTemplate . '.layouts.app')

@section('content')
    <div class="blog">
        <div class="blog-overlay"></div>
        <h1 class="blog-title">{{ __('general.channels_title') }}</h1>
    </div>

    <section class="channels">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="search-box position-relative">
                        <input type="text" id="channelSearch" placeholder="{{ __('general.placeholder_keyword') }}" autocomplete="off">
                        <svg class="search-icon" width="15" height="15" viewBox="0 0 15 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.6339 12.8658L11.3687 9.60043C12.952 7.19675 12.6874 3.92472 10.5743 1.8117C8.15848 -0.603818 4.22745 -0.603984 1.81177 1.8117C-0.603922 4.22756 -0.603922 8.15826 1.81177 10.5743C3.01978 11.7821 4.60629 12.386 6.19297 12.386C7.38198 12.386 8.57082 12.0468 9.6005 11.3686L12.8657 14.6338C13.1099 14.878 13.4299 15.0001 13.7497 15.0001C14.0695 15.0001 14.3897 14.878 14.6339 14.634C15.122 14.1456 15.122 13.3541 14.6339 12.8658ZM3.57995 8.80626C2.1391 7.36525 2.1391 5.02073 3.57995 3.57988C4.30045 2.85954 5.24663 2.49938 6.19314 2.49938C7.13948 2.49938 8.08582 2.85954 8.80616 3.57988C10.2468 5.02056 10.247 7.36442 8.80699 8.80526C8.80666 8.80559 8.80632 8.80576 8.80616 8.80609C8.80582 8.80643 8.80566 8.80659 8.80549 8.80693C7.36398 10.2469 5.02046 10.2468 3.57995 8.80626Z"
                                fill="#B1B1B1" />
                        </svg>

                        <ul id="searchResults" class="list-group position-absolute w-100" style="display: none;"></ul>
                    </div>
                </div>
            </div>

            <div class="row channels-list mt-4">
                @foreach ($channelsByRegion as $region => $countries)
                    <div class="row channels-list mt-4">
                        <div class="text-center">
                            <div class="sec-title">{{ $region }}</div>
                        </div>

                        @foreach ($countries as $country)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="accordion">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $region }},{{ $country }}"
                                        aria-expanded="false"
                                        aria-controls="collapse{{ $region }},{{ $country }}"
                                        data-region="{{ $region }}" data-country="{{ $country }}">
                                        {{ $country }} | {{ getFullCountryName($country) }}
                                    </button>

                                    <div id="collapse{{ $region }},{{ $country }}"
                                        class="accordion-collapse collapse"
                                        data-bs-parent="#channelAccordion{{ $region }},{{ $country }}">
                                        <div class="accordion-body" id="channels-{{ $region }},{{ $country }}">
                                            <div class="loader" style="display:none;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadChannelsUrl = @json(route('channels.load', ['region' => 'dummy', 'country' => 'dummy']));
            const searchUrl = @json(route('channels.search'));
            const searchInput = document.getElementById('channelSearch');
            const searchResults = document.getElementById('searchResults');
            const loadedChannels = new Set();

            // Hide search results and clear when an accordion button is clicked
            document.querySelectorAll('.accordion-button').forEach(button => {
                button.addEventListener('click', () => {
                    // Clear search results and input value
                    searchResults.style.display = 'none';
                    searchResults.innerHTML = '';
                    searchInput.value = '';

                    const region = button.dataset.region;
                    const country = button.dataset.country;
                    const channelsContainer = document.getElementById(
                        `channels-${region},${country}`);
                    const loader = channelsContainer.querySelector('.loader');

                    if (!loadedChannels.has(`${region},${country}`)) {
                        loader.style.display = 'block';
                        const url = loadChannelsUrl.replace('dummy', region).replace('dummy',
                            country);

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.channels && data.channels.length > 0) {
                                    const ul = document.createElement('ul');
                                    ul.classList.add('p-0');

                                    data.channels.forEach(channel => {
                                        const li = document.createElement('li');
                                        li.classList.add('channel-item');
                                        li.setAttribute('data-channel', channel.name
                                            .toLowerCase());
                                        li.textContent =
                                            `+ ${country} - ${channel.name}`;
                                        ul.appendChild(li);
                                    });

                                    channelsContainer.appendChild(ul);
                                    loader.style.display = 'none';
                                    loadedChannels.add(`${region},${country}`);
                                }
                            })
                            .catch(console.error);
                    }
                });
            });

            // Handling search functionality
            searchInput.addEventListener('keyup', () => {
                const query = searchInput.value.trim().toLowerCase();

                if (query.length === 0) {
                    searchResults.style.display = 'none';
                    searchResults.innerHTML = '';
                    return;
                }

                fetch(`${searchUrl}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';

                        if (data.channels?.length) {
                            data.channels.forEach(channel => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item', 'text-uppercase');
                                li.textContent = `+ ${channel.country} - ${channel.name}`;
                                li.addEventListener('click', () => loadAndDisplayChannel(channel
                                    .region, channel.country));
                                searchResults.appendChild(li);
                            });

                            Object.assign(searchResults.style, {
                                display: 'block',
                                zIndex: '99',
                                borderRadius: '0'
                            });
                        } else {
                            searchResults.style.display = 'none';
                        }

                    })
                    .catch(console.error);
            });

            function loadAndDisplayChannel(region, country) {
                const button = document.querySelector(`[data-region="${region}"][data-country="${country}"]`);
                if (button && !loadedChannels.has(`${region},${country}`)) {
                    button.click();
                }
            }
        });
    </script>
@endsection
