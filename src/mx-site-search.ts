type Query = {
  s: string;
};

type Hit = {
  title?: string;
  href?: string;
  excerpt?: string;
  image?: string;
  date?: string;
  type?: string;
};

type Results = {
  hits?: Hit[];
  total?: number;
  totalPages?: number;
};

export type SearchCallbackData = Query & {
  page: number | null;
  hitsPerPage: number;
};

export type SearchCallback = (
  this: MxSiteSearchElement,
  data: SearchCallbackData,
) => Promise<Results>;

function callGlobalFunction<T extends (this: any, ...args: any[]) => any>(
  callbackName: string,
  thisArg: ThisParameterType<T>,
  ...args: Parameters<T>
): ReturnType<T> {
  const callback = (window as any)[callbackName];
  if (typeof callback === 'function') {
    return (callback as T).call(thisArg, ...args);
  }
  throw new Error(`Global function "${callbackName}" not found`);
}

class MxSiteSearchElement extends HTMLElement {
  protected hitsPerPage = 24;

  protected _internals: ElementInternals;

  get form(): HTMLFormElement {
    const el = this.querySelector('form[part=form]') as HTMLFormElement | null;
    if (!el) {
      throw new Error(
        "<mx-site-search> must contain a form element with part='form'",
      );
    }
    return el;
  }

  get hitParent(): HTMLElement {
    return this.hitTemplate.parentElement!;
  }

  get hitTemplate(): HTMLTemplateElement {
    const el = this.querySelector(
      'template[name=hit]',
    ) as HTMLTemplateElement | null;
    if (!el) {
      throw new Error(
        "<mx-site-search> must contain a template element with name='hit'",
      );
    }
    return el;
  }

  get paginationElement(): HTMLElement | null {
    const el = this.querySelector('[part=pagination]') as HTMLElement | null;
    return el;
  }

  get summaryElements(): HTMLElement[] {
    const el = this.querySelectorAll(
      '[part=summary]',
    ) as NodeListOf<HTMLElement>;
    return [...el];
  }

  get resultsElement(): HTMLElement | null {
    const el = this.querySelector('[part=results]') as HTMLElement | null;
    return el;
  }

  constructor() {
    super();
    this._internals = this.attachInternals();
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  connectedCallback() {
    this.form.addEventListener('submit', this.handleSubmit);
    (
      this.form.querySelector('input[name=s]') as HTMLInputElement | null
    )?.focus();
    if (this.paginationElement) {
      this._page = Number(this.paginationElement.getAttribute('value'));
      this.paginationElement.addEventListener('change', (event) => {
        if (event instanceof CustomEvent) {
          const { value } = event.detail;
          this.page = value;
        }
      });
    }
    this.updateQuery();
    this.performSearch();
  }

  disconnectedCallback() {
    this.form.removeEventListener('submit', this.handleSubmit);
  }

  getFormData() {
    return new FormData(this.form);
  }

  protected _error: Error | null = null;

  set error(error) {
    this._error = error;
    if (error) {
      this._internals.states.add('error');
    } else {
      this._internals.states.delete('error');
    }
  }

  get error() {
    return this._error;
  }

  set pending(flag) {
    if (flag) {
      this._internals.states.add('pending');
    } else {
      this._internals.states.delete('pending');
    }
    // this.update();
  }

  get pending() {
    return this._internals.states.has('pending');
  }

  set hasResults(flag) {
    if (flag) {
      this._internals.states.add('has-results');
    } else {
      this._internals.states.delete('has-results');
    }
    // this.update();
  }

  get hasResults() {
    return this._internals.states.has('has-results');
  }

  protected _query: Query | null = null;

  set query(query) {
    this._query = query;
    this.performSearch();
  }

  get query() {
    return this._query;
  }

  protected _page: number | null = null;

  set page(page) {
    page = !page || page < 2 ? null : page;
    this._page = page;
    this.paginationElement?.setAttribute('value', String(page || 1));
    this.performSearch();
  }

  get page() {
    return this._page;
  }

  protected _results: Results | null = null;

  set results(results) {
    this._results = results;
    const hitTemplate = this.hitTemplate;
    [...this.hitParent.children]
      .filter((child) => child !== hitTemplate)
      .forEach((child) => child.remove());
    this.hasResults = !!this._results?.hits?.length;
    if (results?.hits) {
      results.hits.forEach((hit) => {
        const el = hitTemplate.content.cloneNode(true) as DocumentFragment;
        const linkEl = el.querySelector(
          '[slot=link]',
        ) as HTMLAnchorElement | null;
        if (linkEl && hit.href) {
          linkEl.href = hit.href;
        }
        const titleEl = el.querySelector('[slot=title]') as HTMLElement | null;
        if (titleEl && hit.title) {
          titleEl.innerHTML = hit.title;
        }
        const excerptEl = el.querySelector(
          '[slot=excerpt]',
        ) as HTMLElement | null;
        if (excerptEl && hit.excerpt) {
          excerptEl.innerHTML = hit.excerpt;
        }
        const imageEl = el.querySelector(
          'img[slot=image]',
        ) as HTMLElement | null;
        if (imageEl) {
          if (hit.image) {
            imageEl.setAttribute('src', hit.image);
          } else {
            imageEl.remove();
          }
        }
        const dateEl = el.querySelector('[slot=date]') as HTMLElement | null;
        if (dateEl && hit.date) {
          dateEl.innerHTML = new Date(hit.date).toLocaleDateString('default', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
          });
        }
        this.hitParent.append(el);
      });
    }
    this.paginationElement?.setAttribute(
      'max',
      String(results?.totalPages || 1),
    );
    this.summaryElements.forEach((el) => {
      el.innerHTML = `<b>${results?.total || 0}</b> träffar på <b>"${this.query?.s ?? ''}"</b>`;
    });
  }

  get results() {
    return this._results;
  }

  protected updateQuery() {
    const formData = this.getFormData();
    const s = formData.get('s') as string;
    this.query = { s };
  }

  search() {
    this.updateQuery();
    this.page = null;
  }

  protected updateQueryParams(values: Record<string, string | null>) {
    const params = new URLSearchParams();
    for (const [key, value] of Object.entries(values)) {
      if (value != null) {
        params.set(key, value);
      }
    }
    // TODO: Use history.pushState instead of history.replaceState
    history.replaceState({}, '', `?${params}`);
  }

  get searchCallbackName(): string | undefined {
    const name = this.getAttribute('search-callback') as string | null;
    return name ?? undefined;
  }

  protected async performSearch() {
    this.updateQueryParams({
      ...this.query,
      paged: this.page ? String(this.page) : null,
    });
    this.resultsElement?.focus();
    this.resultsElement?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    this.pending = true;
    this.error = null;
    if (!this.query?.s) {
      this.results = null;
    } else {
      if (this.searchCallbackName) {
        try {
          this.results = await callGlobalFunction<SearchCallback>(
            this.searchCallbackName,
            this,
            {
              ...this.query,
              page: this.page,
              hitsPerPage: this.hitsPerPage,
            },
          );
        } catch (error) {
          if (!(error instanceof Error)) {
            error = new Error(String(error));
          }
          this.error = error as Error;
          this.results = null;
        }
      }
    }
    this.pending = false;
  }

  handleSubmit(event: SubmitEvent) {
    event.preventDefault();
    this.search();
  }
}

customElements.define('mx-site-search', MxSiteSearchElement);
