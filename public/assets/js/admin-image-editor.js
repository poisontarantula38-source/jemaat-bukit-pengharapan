(() => {
    const editors = document.querySelectorAll('[data-image-editor]');

    editors.forEach((editor) => {
        const fileInput = editor.querySelector('input[type="file"]');
        const workspace = editor.querySelector('[data-image-workspace]');
        const canvas = editor.querySelector('[data-image-canvas]');
        const zoomInput = editor.querySelector('[data-image-zoom]');
        const errorMessage = editor.querySelector('[data-image-error]');
        const context = canvas.getContext('2d');
        const aspectParts = (editor.dataset.aspectRatio || '4/3').split('/');
        const aspectRatio = Number(aspectParts[0]) / Number(aspectParts[1]);
        const outputWidth = 1200;

        canvas.width = outputWidth;
        canvas.height = Math.round(outputWidth / aspectRatio);
        canvas.style.maxWidth = `${28 * aspectRatio}rem`;

        let image = null;
        let rotation = 0;
        let zoom = 1;
        let offsetX = 0;
        let offsetY = 0;
        let dirty = false;
        let pointer = null;
        let objectUrl = null;
        let previewTimer = 0;

        const queuePreview = () => {
            if (!editor.dataset.imagePreviewKey || !dirty) {
                return;
            }

            window.clearTimeout(previewTimer);
            previewTimer = window.setTimeout(() => {
                editor.dispatchEvent(new CustomEvent('admin:image-preview', {
                    bubbles: true,
                    detail: {
                        key: editor.dataset.imagePreviewKey,
                        source: canvas.toDataURL('image/jpeg', 0.82),
                    },
                }));
            }, 120);
        };

        const clampOffset = () => {
            if (!image) {
                return;
            }

            const radians = (rotation * Math.PI) / 180;
            const rotatedWidth = Math.abs(image.width * Math.cos(radians)) + Math.abs(image.height * Math.sin(radians));
            const rotatedHeight = Math.abs(image.width * Math.sin(radians)) + Math.abs(image.height * Math.cos(radians));
            const scale = Math.max(canvas.width / rotatedWidth, canvas.height / rotatedHeight) * zoom;
            const maxX = Math.max(0, (rotatedWidth * scale - canvas.width) / 2);
            const maxY = Math.max(0, (rotatedHeight * scale - canvas.height) / 2);

            offsetX = Math.min(maxX, Math.max(-maxX, offsetX));
            offsetY = Math.min(maxY, Math.max(-maxY, offsetY));
        };

        const draw = () => {
            if (!image) {
                return;
            }

            clampOffset();
            context.fillStyle = '#ffffff';
            context.fillRect(0, 0, canvas.width, canvas.height);
            context.save();
            context.translate(canvas.width / 2 + offsetX, canvas.height / 2 + offsetY);
            context.rotate((rotation * Math.PI) / 180);
            const radians = (rotation * Math.PI) / 180;
            const rotatedWidth = Math.abs(image.width * Math.cos(radians)) + Math.abs(image.height * Math.sin(radians));
            const rotatedHeight = Math.abs(image.width * Math.sin(radians)) + Math.abs(image.height * Math.cos(radians));
            const scale = Math.max(canvas.width / rotatedWidth, canvas.height / rotatedHeight) * zoom;
            context.scale(scale, scale);
            context.drawImage(image, -image.width / 2, -image.height / 2);
            context.restore();
            queuePreview();
        };

        const loadImage = (source, isNewFile) => {
            const nextImage = new Image();
            nextImage.onload = () => {
                image = nextImage;
                rotation = 0;
                zoom = 1;
                offsetX = 0;
                offsetY = 0;
                zoomInput.value = '1';
                dirty = dirty || isNewFile;
                workspace.hidden = false;
                errorMessage.hidden = true;
                draw();
            };
            nextImage.onerror = () => {
                errorMessage.textContent = 'Foto tidak dapat dibuka. Pilih file gambar lain.';
                errorMessage.hidden = false;
            };
            nextImage.src = source;
        };

        if (editor.dataset.imageSource) {
            loadImage(editor.dataset.imageSource, false);
        }

        fileInput.addEventListener('change', () => {
            const file = fileInput.files?.[0];
            if (!file) {
                return;
            }

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
            }
            objectUrl = URL.createObjectURL(file);
            loadImage(objectUrl, true);
        });

        editor.querySelectorAll('[data-image-rotate]').forEach((button) => {
            button.addEventListener('click', () => {
                rotation = (rotation + Number(button.dataset.imageRotate) + 360) % 360;
                dirty = true;
                draw();
            });
        });

        zoomInput.addEventListener('input', () => {
            zoom = Number(zoomInput.value);
            dirty = true;
            draw();
        });

        editor.querySelector('[data-image-reset]').addEventListener('click', () => {
            rotation = 0;
            zoom = 1;
            offsetX = 0;
            offsetY = 0;
            zoomInput.value = '1';
            dirty = true;
            draw();
        });

        canvas.addEventListener('pointerdown', (event) => {
            pointer = { id: event.pointerId, x: event.clientX, y: event.clientY };
            canvas.setPointerCapture(event.pointerId);
        });

        canvas.addEventListener('pointermove', (event) => {
            if (!pointer || pointer.id !== event.pointerId) {
                return;
            }

            const bounds = canvas.getBoundingClientRect();
            offsetX += ((event.clientX - pointer.x) * canvas.width) / bounds.width;
            offsetY += ((event.clientY - pointer.y) * canvas.height) / bounds.height;
            pointer.x = event.clientX;
            pointer.y = event.clientY;
            dirty = true;
            draw();
        });

        canvas.addEventListener('pointerup', () => {
            pointer = null;
        });
        canvas.addEventListener('pointercancel', () => {
            pointer = null;
        });

        canvas.addEventListener('keydown', (event) => {
            const step = canvas.width * 0.02;
            const moves = {
                ArrowLeft: [-step, 0],
                ArrowRight: [step, 0],
                ArrowUp: [0, -step],
                ArrowDown: [0, step],
            };
            const movement = moves[event.key];
            if (!movement) {
                return;
            }

            event.preventDefault();
            offsetX += movement[0];
            offsetY += movement[1];
            dirty = true;
            draw();
        });

        const form = fileInput.closest('form');
        form.addEventListener('submit', (event) => {
            if (!image || !dirty) {
                return;
            }

            event.preventDefault();
            canvas.toBlob((blob) => {
                if (!blob) {
                    errorMessage.textContent = 'Foto belum dapat diproses. Coba pilih file gambar lain.';
                    errorMessage.hidden = false;
                    return;
                }

                if (blob.size > 5 * 1024 * 1024) {
                    errorMessage.textContent = 'Hasil foto lebih dari 5 MB. Kurangi ukuran atau detail potongan foto.';
                    errorMessage.hidden = false;
                    return;
                }

                try {
                    const file = new File([blob], 'foto-edit.jpg', { type: 'image/jpeg' });
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    fileInput.files = transfer.files;
                    form.submit();
                } catch (error) {
                    errorMessage.textContent = 'Browser ini belum mendukung penyimpanan foto hasil edit.';
                    errorMessage.hidden = false;
                }
            }, 'image/jpeg', 0.88);
        });
    });
})();
