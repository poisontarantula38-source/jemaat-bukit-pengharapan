(() => {
    const editor = document.querySelector('[data-live-editor]');
    if (!editor) {
        return;
    }

    const form = editor.querySelector('[data-live-editor-form]');
    const frame = editor.querySelector('[data-preview-frame]');
    const previewPanel = editor.querySelector('.live-preview-panel');
    const previewState = editor.querySelector('[data-preview-state]');
    const previewStateText = editor.querySelector('[data-preview-state-text]');
    const loading = editor.querySelector('[data-preview-loading]');
    const saveState = editor.querySelector('[data-save-state]');
    const pageSelect = editor.querySelector('[data-preview-page]');
    let updateQueued = false;
    let galleryPreviewSource = '';
    const imagePreviewSources = {};
    const galleryCaptionInput = document.querySelector('[data-live-gallery-caption]');
    const galleryAltInput = document.querySelector('[data-live-gallery-alt]');
    const normalizeRoute = (route) => {
        const url = new URL(route, window.location.href);
        const pathname = url.pathname.replace(/\/+$/u, '') || '/';
        return `${url.origin}${pathname}`;
    };
    const editablePreviewRoutes = new Set([
        editor.dataset.previewRoute,
        ...Array.from(pageSelect?.options ?? [], (option) => option.value),
    ].filter(Boolean).map(normalizeRoute));

    const isEditablePreview = () => {
        try {
            return editablePreviewRoutes.has(normalizeRoute(frame.contentWindow.location.href));
        } catch {
            return false;
        }
    };

    const valuesFromForm = () => {
        const values = {};
        new FormData(form).forEach((value, name) => {
            if (name.startsWith('content_') && typeof value === 'string') {
                values[name.slice('content_'.length)] = value;
            }
        });

        return values;
    };

    const listItems = (key, value) => {
        const lines = value.split(/\r?\n/u).map((line) => line.trim()).filter(Boolean);
        if (key !== 'board_rows') {
            return lines;
        }

        return lines.flatMap((line) => {
            const separator = line.indexOf('|');
            if (separator < 1 || separator === line.length - 1) {
                return [];
            }

            return [[line.slice(0, separator).trim(), line.slice(separator + 1).trim()]];
        });
    };

    const setPreviewText = (document, values) => {
        document.querySelectorAll('[data-live-preview]').forEach((target) => {
            const key = target.dataset.livePreview;
            if (!Object.prototype.hasOwnProperty.call(values, key)) {
                return;
            }

            const value = values[key];
            if (target.dataset.livePreviewFormat === 'paragraphs') {
                const paragraphClass = target.querySelector('p')?.className ?? 'section-intro';
                const paragraphs = value.split(/\r?\n\s*\r?\n/u).map((text) => text.trim()).filter(Boolean);
                target.replaceChildren(...paragraphs.map((text) => {
                    const paragraph = document.createElement('p');
                    paragraph.className = paragraphClass;
                    paragraph.style.whiteSpace = 'pre-line';
                    paragraph.textContent = text;

                    return paragraph;
                }));
                return;
            }

            target.textContent = value;
            target.style.whiteSpace = 'pre-line';
        });

        document.querySelectorAll('[data-live-preview-combine]').forEach((target) => {
            const keys = target.dataset.livePreviewCombine.split(',');
            const normalizedKeys = keys.map((key) => key.trim());
            if (!normalizedKeys.every((key) => Object.prototype.hasOwnProperty.call(values, key))) {
                return;
            }

            target.textContent = normalizedKeys.map((key) => values[key]).join(' & ');
        });

        document.querySelectorAll('[data-live-preview-list]').forEach((target) => {
            const key = target.dataset.livePreviewList;
            if (!Object.prototype.hasOwnProperty.call(values, key)) {
                return;
            }

            const items = listItems(key, values[key]);
            if (key === 'board_rows') {
                target.replaceChildren(...items.map(([name, role]) => {
                    const row = document.createElement('tr');
                    const nameCell = document.createElement('th');
                    nameCell.scope = 'row';
                    nameCell.textContent = name;
                    const roleCell = document.createElement('td');
                    roleCell.textContent = role;
                    row.append(nameCell, roleCell);

                    return row;
                }));
            } else {
                target.replaceChildren(...items.map((text) => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item bg-transparent px-0 py-3';
                    item.textContent = text;

                    return item;
                }));
            }
        });

        document.querySelectorAll('[data-live-preview-empty-if], [data-live-preview-present-if]').forEach((target) => {
            const key = target.dataset.livePreviewEmptyIf ?? target.dataset.livePreviewPresentIf;
            if (!Object.prototype.hasOwnProperty.call(values, key)) {
                return;
            }

            const value = values[key];
            const present = key === 'board_rows' || key === 'service_items' || key === 'sector_items'
                ? listItems(key, value).length > 0
                : value.trim() !== '';

            target.hidden = target.hasAttribute('data-live-preview-empty-if') ? present : !present;
        });

        if (values.page_title) {
            document.title = `${values.page_title} | Jemaat GPM Bukit Pengharapan`;
        }
    };

    const updateGalleryPreview = (document, imageSource) => {
        const feature = document.querySelector('[data-live-gallery-feature]');
        if (feature?.dataset.liveGalleryFeatureEmpty === 'true') {
            const image = feature.querySelector('img');
            const caption = feature.querySelector('[data-live-gallery-feature-caption]');
            const link = feature.querySelector('a');
            const captionText = galleryCaptionInput?.value.trim() || 'Foto baru';
            const altText = galleryAltInput?.value.trim() || captionText;
            if (imageSource && image && caption && link) {
                image.src = imageSource;
                image.alt = altText;
                caption.textContent = captionText;
                link.href = imageSource;
                link.setAttribute('aria-label', `Buka foto ukuran penuh: ${captionText}`);
                feature.hidden = false;
                feature.dataset.liveGalleryFeatureEmpty = 'false';
                document.querySelector('[data-live-gallery-empty]')?.setAttribute('hidden', '');
                return;
            }
        }

        const grid = document.querySelector('[data-live-gallery]');
        if (!grid) {
            return;
        }

        const emptyState = document.querySelector('[data-live-gallery-empty]');
        if (emptyState) {
            emptyState.hidden = true;
        }
        grid.hidden = false;
        const archive = document.querySelector('[data-live-gallery-archive]');
        if (archive) {
            archive.hidden = false;
        }

        let figure = grid.querySelector('[data-live-gallery-new]');
        if (!figure) {
            figure = document.createElement('figure');
            figure.dataset.liveGalleryNew = '';
            const image = document.createElement('img');
            image.loading = 'lazy';
            const caption = document.createElement('figcaption');

            if (grid.classList.contains('majelis-grid')) {
                figure.className = 'majelis-photo';
                caption.append(document.createElement('strong'));
            } else {
                figure.className = 'gallery-card';
                const link = document.createElement('a');
                link.className = 'gallery-card-image';
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.setAttribute('aria-label', 'Buka foto ukuran penuh');
                link.append(image);
                figure.append(link);
            }

            if (grid.classList.contains('majelis-grid')) {
                figure.append(image, caption);
            } else {
                figure.append(caption);
            }
            grid.prepend(figure);
        }

        const image = figure.querySelector('img');
        const caption = figure.querySelector('figcaption');
        if (imageSource) {
            image.src = imageSource;
        }
        if (caption) {
            const captionText = galleryCaptionInput?.value.trim() || 'Foto baru';
            const strongCaption = caption.querySelector('strong');
            if (strongCaption) {
                strongCaption.textContent = captionText;
            } else {
                caption.textContent = captionText;
            }
            image.alt = galleryAltInput?.value.trim() || captionText;
            const link = figure.querySelector('a');
            if (link) {
                link.href = image.src;
                link.setAttribute('aria-label', `Buka foto ukuran penuh: ${captionText}`);
            }
        }
    };

    const setPreviewImage = (key, source) => {
        try {
            imagePreviewSources[key] = source;
            const document = frame.contentDocument;
            if (!document) {
                return;
            }

            if (key === 'gallery_new') {
                galleryPreviewSource = source;
                updateGalleryPreview(document, source);
                return;
            }

            document.querySelectorAll(`[data-live-image="${key}"]`).forEach((image) => {
                image.src = source;
            });
            window.document.querySelectorAll(`[data-live-media-image="${key === 'hero_image' ? 'hero' : 'profile'}"]`).forEach((image) => {
                image.src = source;
            });
        } catch {
            previewState.dataset.state = 'error';
            previewStateText.textContent = 'Foto belum dapat ditampilkan di pratinjau';
        }
    };

    const updatePreview = () => {
        updateQueued = false;
        try {
            const document = frame.contentDocument;
            if (!document || document.readyState !== 'complete') {
                return;
            }

            const isEditingCurrentPage = isEditablePreview();
            if (isEditingCurrentPage) {
                setPreviewText(document, valuesFromForm());
            }
            Object.entries(imagePreviewSources).forEach(([key, source]) => {
                if (key === 'gallery_new') {
                    updateGalleryPreview(document, source);
                    return;
                }

                document.querySelectorAll(`[data-live-image="${key}"]`).forEach((image) => {
                    image.src = source;
                });
            });
            previewState.dataset.state = 'ready';
            const previewTitle = document.querySelector('main h1')?.textContent.trim();
            previewStateText.textContent = isEditingCurrentPage
                ? `Pratinjau ${previewTitle || 'halaman'} diperbarui`
                : `Menjelajahi ${previewTitle || 'halaman publik'}`;
            loading.hidden = true;
        } catch {
            previewState.dataset.state = 'error';
            previewStateText.textContent = 'Preview tidak dapat diperbarui';
            loading.hidden = true;
        }
    };

    const queuePreviewUpdate = () => {
        if (updateQueued) {
            return;
        }
        updateQueued = true;
        requestAnimationFrame(updatePreview);
    };

    const bindPreviewInteractions = () => {
        try {
            const document = frame.contentDocument;
            if (!document) {
                return;
            }

            document.addEventListener('submit', (event) => {
                event.preventDefault();
                previewStateText.textContent = 'Form tidak dikirim dari pratinjau';
            });
        } catch {
            previewState.dataset.state = 'error';
            previewStateText.textContent = 'Preview tidak dapat dibuka';
        }
    };

    frame.addEventListener('load', () => {
        bindPreviewInteractions();
        if (pageSelect) {
            try {
                const pathname = new URL(frame.contentWindow.location.href).pathname.replace(/\/+$/u, '') || '/';
                const matchingOption = Array.from(pageSelect.options).find((option) => {
                    const optionPath = new URL(option.value, window.location.href).pathname.replace(/\/+$/u, '') || '/';
                    return optionPath === pathname;
                });
                if (matchingOption) {
                    pageSelect.value = matchingOption.value;
                } else {
                    pageSelect.selectedIndex = -1;
                }
            } catch {
                previewState.dataset.state = 'error';
                previewStateText.textContent = 'Halaman pratinjau tidak dapat dibaca';
            }
        }
        queuePreviewUpdate();
    });

    if (frame.contentDocument?.readyState === 'complete') {
        bindPreviewInteractions();
        queuePreviewUpdate();
    }

    pageSelect?.addEventListener('change', () => {
        loading.hidden = false;
        previewState.dataset.state = 'loading';
        previewStateText.textContent = 'Memuat halaman';
        frame.src = pageSelect.value;
    });

    editor.querySelectorAll('[data-preview-width]').forEach((button) => {
        button.addEventListener('click', () => {
            const isMobile = button.dataset.previewWidth === 'mobile';
            previewPanel.dataset.previewSize = isMobile ? 'mobile' : 'desktop';
            editor.querySelectorAll('[data-preview-width]').forEach((option) => {
                option.setAttribute('aria-pressed', String(option === button));
            });
        });
    });

    form.addEventListener('input', (event) => {
        if (!event.target.name?.startsWith('content_')) {
            return;
        }

        saveState.dataset.state = 'dirty';
        saveState.textContent = 'Ada perubahan belum disimpan';
        queuePreviewUpdate();
    });

    form.addEventListener('change', (event) => {
        if (event.target.name?.startsWith('content_')) {
            saveState.dataset.state = 'dirty';
            saveState.textContent = 'Ada perubahan belum disimpan';
            queuePreviewUpdate();
        }
    });

    window.document.addEventListener('admin:image-preview', (event) => {
        setPreviewImage(event.detail.key, event.detail.source);
    });

    const updateGalleryFields = () => {
        try {
            const document = frame.contentDocument;
            if (document && galleryPreviewSource) {
                updateGalleryPreview(document, galleryPreviewSource);
            }
        } catch {
            previewState.dataset.state = 'error';
            previewStateText.textContent = 'Keterangan foto belum dapat ditampilkan di pratinjau';
        }
    };
    galleryCaptionInput?.addEventListener('input', updateGalleryFields);
    galleryAltInput?.addEventListener('input', updateGalleryFields);

    form.addEventListener('submit', (event) => {
        const submitter = event.submitter;
        if (submitter?.name === 'move') {
            saveState.textContent = 'Menyimpan perubahan dan urutan…';
        } else {
            saveState.textContent = 'Menyimpan perubahan…';
        }
    });
})();
