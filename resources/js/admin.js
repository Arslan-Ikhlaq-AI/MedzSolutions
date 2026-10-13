const shell = document.querySelector('.admin-shell');

document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
    button.addEventListener('click', () => shell?.classList.toggle('is-menu-open'));
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        shell?.classList.remove('is-menu-open');
        document.querySelectorAll('details.dropdown[open]').forEach((dropdown) => dropdown.removeAttribute('open'));
    }
});

document.addEventListener('click', (event) => {
    document.querySelectorAll('details.dropdown[open]').forEach((dropdown) => {
        if (!dropdown.contains(event.target)) {
            dropdown.removeAttribute('open');
        }
    });
});

// Ask before anything destructive.
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

const slugify = (text) => text.toLowerCase().normalize('NFKD').replace(/[^\w\s-]/g, '').trim().replace(/[\s_-]+/g, '-');

const postForm = document.querySelector('[data-post-form]');

if (postForm) {
    const title = postForm.querySelector('[data-slug-source]');
    const slug = postForm.querySelector('[data-slug-target]');
    let slugTouched = slug.value !== '';

    slug.addEventListener('input', () => {
        slugTouched = slug.value !== '';
    });
    title.addEventListener('input', () => {
        if (!slugTouched) {
            slug.value = slugify(title.value);
        }
    });

    postForm.querySelectorAll('[data-counter]').forEach((field) => {
        const label = postForm.querySelector(`[data-counter-for="${field.dataset.counter}"]`);
        const update = () => {
            label.textContent = `${field.value.length}/${field.maxLength}`;
        };
        field.addEventListener('input', update);
        update();
    });

    const input = postForm.querySelector('[data-image-input]');
    const preview = postForm.querySelector('[data-image-preview]');

    input?.addEventListener('change', () => {
        const file = input.files[0];

        if (!file) {
            return;
        }

        const image = document.createElement('img');
        image.alt = 'Selected image preview';
        image.src = URL.createObjectURL(file);
        preview.replaceChildren(image);
    });
}
