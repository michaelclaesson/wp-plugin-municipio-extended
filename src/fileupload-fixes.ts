document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.mod-form-field').forEach((container) => {
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        /**
         * Makes sure only the first non-empty or only file input is required.
         */
        mutation.addedNodes.forEach((node) => {
          if (node instanceof HTMLInputElement && node.type === 'file') {
            const hiddenInput = node;
            const parent = hiddenInput.parentElement!;
            const allInputs = [
              ...parent.querySelectorAll('input[type="file"]'),
            ] as HTMLInputElement[];
            const required = parent.classList.contains('data-js-required');

            /*
            Helsingborg’s styleguide’s script hides the file input by setting
            style to display:none. This doesn’t work well with Safari since it
            cannot focus hidden elements. The c-fileinput__input class already
            visually hides it so we don’t need to do anything else than remove
            the style attribute.
            */
            hiddenInput.removeAttribute('style');
            hiddenInput.removeAttribute('required');

            allInputs.forEach((input, index) => {
              input.required =
                Math.min(allInputs.length - 1, 1) === index && required;
            });
          }
        });
        mutation.removedNodes.forEach((node) => {
          if (node instanceof HTMLInputElement && node.type === 'file') {
            const hiddenInput = node;
            const parent = document.getElementsByName(hiddenInput.name)[0]
              .parentElement!;
            const allInputs = [
              ...(parent.querySelectorAll('input[type="file"]') || []),
            ] as HTMLInputElement[];
            const required = parent.classList.contains('data-js-required');

            allInputs.forEach((input, index) => {
              input.required =
                Math.min(allInputs.length - 1, 1) === index && required;
            });
          }
        });
      });
    });
    observer.observe(container, { childList: true, subtree: true });
  });
});
