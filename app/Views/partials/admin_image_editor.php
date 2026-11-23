<?php
$imageEditorName = $imageEditorName ?? 'foto';
$imageEditorId = $imageEditorId ?? $imageEditorName;
$imageEditorSource = $imageEditorSource ?? '';
$imageEditorAspect = $imageEditorAspect ?? '4/3';
$imageEditorRequired = $imageEditorRequired ?? false;
?>
<div class="admin-image-editor" data-image-editor data-aspect-ratio="<?= esc($imageEditorAspect, 'attr') ?>" data-image-source="<?= esc($imageEditorSource, 'attr') ?>" <?= isset($imagePreviewKey) ? 'data-image-preview-key="' . esc($imagePreviewKey, 'attr') . '"' : '' ?>>
    <label class="form-label fw-semibold" for="<?= esc($imageEditorId, 'attr') ?>">Pilih foto</label>
    <input class="form-control" id="<?= esc($imageEditorId, 'attr') ?>" name="<?= esc($imageEditorName, 'attr') ?>" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" <?= $imageEditorRequired ? 'required' : '' ?>>
    <p class="form-text mb-2">JPG, PNG, atau WebP. Maksimal 5 MB. Setelah memilih, Anda bisa menggeser, memperbesar, atau memutar foto.</p>
    <div class="admin-image-editor-workspace" data-image-workspace hidden>
        <canvas class="admin-image-canvas" data-image-canvas tabindex="0" aria-label="Pratinjau foto. Seret untuk mengatur potongan atau gunakan tombol panah pada papan ketik."></canvas>
        <div class="admin-image-editor-controls">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-image-rotate="-90">Putar kiri</button>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-image-rotate="90">Putar kanan</button>
            <label class="admin-image-zoom">
                <span>Perbesar</span>
                <input type="range" min="1" max="3" step="0.05" value="1" data-image-zoom aria-label="Perbesar foto">
            </label>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-image-reset>Atur ulang</button>
        </div>
    </div>
    <p class="small text-danger mb-0" data-image-error role="alert" hidden></p>
</div>
