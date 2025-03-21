@extends('templates.master')
@section('layout')
  <section class="o-container t-customsearchpage u-margin__top--6">
    <div class="o-grid">
      <div class="o-grid-12 tailwind">

        <h1 class="sr-only">Sök på webbplatsen</h1>

        <script>
          window.mxSearch = async function(query) {
            const url = this.dataset.adminUrl;
            const nonce = this.dataset.nonce;
            const response = await fetch(url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
              },
              body: new URLSearchParams({
                action: 'mx_search',
                nonce,
                // Any additional data you want to send
                data: JSON.stringify(query)
              }),
            });
            return await response.json();
          };
        </script>

        <mx-site-search class="group space-y-10" search-callback="mxSearch"
          data-admin-url="{{ admin_url('admin-ajax.php') }}" data-nonce="{{ wp_create_nonce('mx_search') }}">
          <form part="form" id="site-search-form">
            <div class="grid grid-cols-[1fr_max-content] items-stretch h-12">
              <input type="search" name="s" placeholder="Sök på webbplatsen" aria-label="Sök på webbplatsen"
                value="{{ get_search_query() }}"
                class="appearance-none bg-white font-inherit p-3 w-full h-full border-y border-l border-border-outline" />
              <button type="submit"
                class="bg-primary hover:bg-primary-dark border-none text-white py-2 px-6 cursor-pointer h-full">Sök</button>
            </div>
          </form>

          <div class="space-y-10 scroll-my-5 focus:outline-none" part="results" tabindex="-1">
            <div
              class="hidden group-[:state(pending)]:pointer-events-none group-[:state(has-results)]:block group-[:state(pending):state(has-results)]:opacity-50">
              <h2 id="mx-site-search-results-heading">Sökresultat</h2>
              <p part="summary" class="empty:hidden"></p>
              <ol class="list-none p-0 space-y-4" aria-labelledby="mx-site-search-results-heading">
                <template name="hit">
                  <li>
                    <a href=""
                      class="flex text-inherit no-underline bg-layer-lighter p-6 [&_mark]:bg-complementary-light hover:text-inherit visited:hover:text-inherit group/hit gap-6  {{ '@container' }}"
                      slot="link">
                      <div class="flex-grow space-y-2">
                        <h3 slot="title" class="m-0 decoration-1 group-hover/hit:underline typography-h4"></h3>
                        <div class="contentless:hidden">
                          <span slot="type"
                            class="empty:hidden text-sm text-gray-700 [:not(:empty)~&:not(:empty)]:before:content-['_•_']"></span>
                          <span slot="date"
                            class="empty:hidden text-sm text-gray-700 [:not(:empty)~&:not(:empty)]:before:content-['_•_']"></span>
                        </div>
                        <p slot="excerpt" class="m-0 contentless:hidden"></p>
                      </div>
                      <img class="aspect-video w-60 flex-shrink-0 object-cover hidden self-start @[30rem]:block"
                        slot="image" alt="">
                    </a>
                  </li>
                </template>
              </ol>
            </div>

            <div class="hidden group-[:state(pending):not(:state(has-results))]:block">
              <h2 id="mx-site-search-results-heading">Söker…</h2>
            </div>

            <div class="hidden group-[:not(:state(pending)):where(:not(:state(has-results)),:state(error))]:block">
              <h2 id="mx-site-search-results-heading">Sökresultat</h2>
              <p>Din sökning gav inga träffar. Kontrollera stavningen eller försök med ett annat sökord.</p>
            </div>

            <mx-pagination value="{{ get_query_var('paged') ?: 1 }}" max="{{ get_query_var('paged') ?: 1 }}"
              part="pagination" class="hidden [&:state(has-options)]:block" aria-label="Sidor">
              <ul class="list-none p-0 flex gap-2 items-center justify-center">
                <template name="item">
                  <li>
                    <button slot="button"
                      class="aria-[current=page]:bg-primary aria-[current=page]:text-white aria-[current=page]:hover:bg-primary-dark border-none bg-white py-1 px-2 min-w-8 hover:bg-primary hover:text-white cursor-pointer text-center text-lg"></button>
                  </li>
                </template>
                <template name="ellipsis">
                  <li aria-hidden="true" role="presentation" class="p-1 text-lg">
                    ⋯
                  </li>
                </template>
              </ul>
            </mx-pagination>
          </div>
        </mx-site-search>

      </div>
    </div>
  </section>
@stop
