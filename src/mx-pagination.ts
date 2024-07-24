function range(start: number, end: number): number[] {
  return Array.from({ length: end - start }, (_, i) => start + i);
}

export default class MxPaginationElement extends HTMLElement {
  static observedAttributes = ['value', 'max'];

  protected _internals: ElementInternals;

  constructor() {
    super();
    this._internals = this.attachInternals();
  }

  get itemParent(): HTMLElement {
    return this.itemTemplate.parentElement!;
  }

  get itemTemplate(): HTMLTemplateElement {
    const el = this.querySelector(
      'template[name=item]',
    ) as HTMLTemplateElement | null;
    if (!el) {
      throw new Error(
        "<mx-pagination> must contain a template element with name='item'",
      );
    }
    return el;
  }

  get ellipsisTemplate(): HTMLTemplateElement {
    const el = this.querySelector(
      'template[name=ellipsis]',
    ) as HTMLTemplateElement | null;
    if (!el) {
      throw new Error(
        "<mx-pagination> must contain a template element with name='ellipsis'",
      );
    }
    return el;
  }

  connectedCallback() {
    this.update();
  }

  disconnectedCallback() {}

  adoptedCallback() {}

  attributeChangedCallback() {
    this.update();
  }

  protected empty() {
    [...this.itemParent.children].forEach((el) => {
      if (el.tagName === 'TEMPLATE') return;
      el.remove();
    });
  }

  set hasOptions(flag) {
    if (flag) {
      this._internals.states.add('has-options');
    } else {
      this._internals.states.delete('has-options');
    }
  }

  get hasOptions() {
    return this._internals.states.has('has-options');
  }

  update() {
    const max = Number(this.getAttribute('max'));
    if (isNaN(max) || max <= 0) {
      throw new Error('max attribute must be numerical and greater than 0');
    }
    let value = Number(this.getAttribute('value'));
    if (isNaN(value) || value <= 0) {
      throw new Error(
        'value attribute must be numerical, greater than 0 and less than or equal to max',
      );
    }
    if (value > max) {
      value = max;
    }

    const siblingCount = 1;
    const boundaryCount = 1;
    const items = [
      ...range(1, 1 + Math.min(boundaryCount, max)),
      ...range(
        Math.max(1, value - siblingCount),
        1 + Math.min(value + siblingCount, max),
      ),
      ...range(Math.max(max - boundaryCount, max), 1 + max),
    ].filter((v, i, a) => a.indexOf(v) === i);

    this.empty();

    this.hasOptions = items.length > 1;

    items.forEach((page, i) => {
      if (i > 0 && items[i - 1] !== page - 1) {
        const ellipsisEl = this.ellipsisTemplate.content.cloneNode(
          true,
        ) as HTMLElement;
        this.itemParent.appendChild(ellipsisEl);
      }
      const itemEl = this.itemTemplate.content.cloneNode(true) as HTMLElement;
      const itemButtonEl = itemEl.querySelector('[slot=button]');
      if (itemButtonEl) {
        itemButtonEl.addEventListener('click', (event) => {
          event.preventDefault();
          this.dispatchEvent(
            new CustomEvent('change', {
              detail: { value: page, originalEvent: event },
            }),
          );
        });
        itemButtonEl.textContent = String(page);
        if (page === value) {
          itemButtonEl.setAttribute('aria-current', 'page');
        }
      }
      this.itemParent.appendChild(itemEl);
    });
  }
}

customElements.define('mx-pagination', MxPaginationElement);
