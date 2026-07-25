import './bootstrap';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Chart from 'chart.js/auto';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

class LaravelUploadAdapter {
    constructor(loader, uploadUrl, csrfToken) {
        this.loader = loader;
        this.uploadUrl = uploadUrl;
        this.csrfToken = csrfToken;
        this.xhr = null;
    }

    upload() {
        return this.loader.file.then((file) => new Promise((resolve, reject) => {
            this.xhr = new XMLHttpRequest();
            this.xhr.open('POST', this.uploadUrl, true);
            this.xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
            this.xhr.setRequestHeader('Accept', 'application/json');
            this.xhr.responseType = 'json';

            this.xhr.addEventListener('error', () => reject(`Gagal upload gambar: ${file.name}.`));
            this.xhr.addEventListener('abort', () => reject());
            this.xhr.addEventListener('load', () => {
                const response = this.xhr.response;

                if (!response || this.xhr.status >= 400 || !response.url) {
                    reject(response?.message || `Gagal upload gambar: ${file.name}.`);
                    return;
                }

                resolve({ default: response.url });
            });

            const data = new FormData();
            data.append('upload', file);
            this.xhr.send(data);
        }));
    }

    abort() {
        this.xhr?.abort();
    }
}

function LaravelUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new LaravelUploadAdapter(
        loader,
        editor.sourceElement.dataset.uploadUrl,
        document.querySelector('meta[name="csrf-token"]')?.content || ''
    );
}

let postContentEditor = null;

function initPostEditor() {
    const textarea = document.querySelector('textarea#content[name="content"]');

    if (!textarea || textarea.dataset.editorReady === 'true') {
        return;
    }

    textarea.dataset.editorReady = 'true';

    ClassicEditor
        .create(textarea, {
            extraPlugins: [LaravelUploadAdapterPlugin],
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                'bulletedList',
                'numberedList',
                '|',
                'blockQuote',
                'insertTable',
                'imageUpload',
                '|',
                'undo',
                'redo',
            ],
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
            },
        })
        .then((editor) => {
            postContentEditor = editor;
            window.postContentEditor = editor;

            const syncContent = () => {
                textarea.value = editor.getData();
            };

            syncContent();
            editor.model.document.on('change:data', syncContent);
            textarea.closest('form')?.addEventListener('submit', () => {
                syncContent();
            });
        })
        .catch((error) => {
            textarea.dataset.editorReady = 'false';
            console.error(error);
        });
}

document.addEventListener('DOMContentLoaded', initPostEditor);

function insertEditorImage(url, alt) {
    if (!postContentEditor) {
        return;
    }

    if (postContentEditor.commands.get('insertImage')) {
        postContentEditor.execute('insertImage', { source: url });
        return;
    }

    postContentEditor.model.change((writer) => {
        const image = writer.createElement('imageBlock', { src: url, alt });
        postContentEditor.model.insertContent(image, postContentEditor.model.document.selection);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-editor-media-button]').forEach((button) => {
        const panel = document.querySelector('[data-editor-media-panel]');
        const results = panel?.querySelector('[data-editor-media-results]');
        const search = panel?.querySelector('[data-editor-media-search]');
        const loadButton = panel?.querySelector('[data-editor-media-load]');

        if (!panel || !results || !search || !loadButton) {
            return;
        }

        const loadMedia = async () => {
            const url = new URL(button.dataset.mediaLibraryUrl, window.location.origin);
            if (search.value) {
                url.searchParams.set('search', search.value);
            }

            results.innerHTML = '<div class="col-12 text-muted small">Memuat media...</div>';

            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
            });
            const payload = await response.json();
            const items = payload.data || [];

            if (!items.length) {
                results.innerHTML = '<div class="col-12 text-muted small">Tidak ada gambar.</div>';
                return;
            }

            results.innerHTML = items.map((item) => `
                <div class="col-6 col-md-3">
                    <button type="button" class="btn p-1 border w-100 h-100 text-start" data-insert-editor-image="${item.url}" data-image-alt="${item.alt || item.name || ''}">
                        <img src="${item.thumbnail_url || item.url}" alt="${item.alt || item.name || ''}" class="w-100 rounded-2" style="height:72px;object-fit:cover;">
                        <span class="small text-truncate d-block mt-1">${item.name || 'media'}</span>
                    </button>
                </div>
            `).join('');
        };

        button.addEventListener('click', () => {
            panel.classList.toggle('d-none');
            if (!panel.classList.contains('d-none') && !results.dataset.loaded) {
                results.dataset.loaded = 'true';
                loadMedia();
            }
        });

        loadButton.addEventListener('click', loadMedia);
        search.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                loadMedia();
            }
        });

        results.addEventListener('click', (event) => {
            const mediaButton = event.target.closest('[data-insert-editor-image]');
            if (!mediaButton) {
                return;
            }

            insertEditorImage(mediaButton.dataset.insertEditorImage, mediaButton.dataset.imageAlt || '');
            panel.classList.add('d-none');
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#monthlyPostsChart, #published7DaysChart, #published30DaysChart').forEach((canvas) => {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: JSON.parse(canvas.dataset.labels || '[]'),
                datasets: [{
                    label: 'Jumlah Berita',
                    data: JSON.parse(canvas.dataset.values || '[]'),
                    borderColor: '#d60000',
                    backgroundColor: 'rgba(214, 0, 0, .12)',
                    fill: true,
                    tension: .35,
                    pointBackgroundColor: '#d60000',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    });
});
