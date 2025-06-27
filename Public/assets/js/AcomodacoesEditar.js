document.addEventListener('DOMContentLoaded', function() {
    const precoInput = document.getElementById('preco-input');
    if (precoInput) {
        IMask(precoInput, {
            mask: 'R$ num',
            blocks: {
                num: { mask: Number, scale: 2, thousandsSeparator: '.', padFractionalZeros: true, radix: ',', mapToRadix: ['.'] }
            }
        });
    }

    const uploadInput = document.getElementById('image-upload');
    if (uploadInput) {
        FilePond.create(uploadInput, {
            labelIdle: `Arraste e solte seus arquivos ou <span class="filepond--label-action">Procure</span>`,
            allowMultiple: true,
            acceptedFileTypes: ['image/*'],
        });
    }

    const sortableContainer = document.getElementById('sortable-images');
    if (sortableContainer) {
        new Sortable(sortableContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            handle: '.image-card-handle',
        });
    }
});