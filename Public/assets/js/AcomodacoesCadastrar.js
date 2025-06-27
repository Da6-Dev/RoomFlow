document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('accommodation-form');
    const steps = [...document.querySelectorAll('.stepper-step')];
    const panels = [...document.querySelectorAll('.step-panel')];
    let currentStep = 1;

    function goToStep(stepNumber) {
        currentStep = stepNumber;
        steps.forEach(step => step.classList.toggle('active', parseInt(step.dataset.step) === currentStep));
        panels.forEach(panel => panel.classList.toggle('active', parseInt(panel.dataset.step) === currentStep));
        window.scrollTo(0, 0);
    }

    function validateCurrentStep() {
        const currentPanel = panels[currentStep - 1];
        const inputs = [...currentPanel.querySelectorAll('input[required], textarea[required], select[required]')];

        for (const input of inputs) {
            if (input.type === 'file' && input.files.length === 0) {
                Swal.fire('Campo Obrigatório', 'Por favor, selecione pelo menos uma imagem.', 'warning');
                return false;
            }
            if (!input.value.trim()) {
                input.focus();
                const label = input.closest('.input-group')?.querySelector('label');
                const labelText = label ? label.textContent.trim() : 'este campo';
                Swal.fire('Campo Obrigatório', `Por favor, preencha o campo "${labelText}"`, 'warning');
                return false;
            }
        }
        // Validação para amenidades na etapa 3
        if (currentStep === 3) {
            if (currentPanel.querySelectorAll('input[name="amenidades[]"]:checked').length === 0) {
                Swal.fire('Campo Obrigatório', 'Selecione pelo menos uma amenidade.', 'warning');
                return false;
            }
        }
        return true;
    }

    form.addEventListener('click', function (e) {
        if (e.target.matches('.next-step-btn')) {
            if (validateCurrentStep()) {
                goToStep(currentStep + 1);
            }
        } else if (e.target.matches('.prev-step-btn')) {
            goToStep(currentStep - 1);
        }
    });

    form.addEventListener('submit', function (e) {
        // Revalida a etapa atual antes de submeter
        if (!validateCurrentStep()) {
            e.preventDefault();
        }
    });

    const dropZone = document.getElementById('drop-zone');
    const imageUpload = document.getElementById('image-upload');
    const previewContainer = document.getElementById('image-preview-container');

    dropZone.addEventListener('click', () => imageUpload.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('is-dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('is-dragover'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('is-dragover');
        if (e.dataTransfer.files.length) {
            imageUpload.files = e.dataTransfer.files;
            handleFiles(imageUpload.files);
        }
    });
    imageUpload.addEventListener('change', (e) => handleFiles(e.target.files));

    function handleFiles(files) {
        previewContainer.innerHTML = '';
        if (files.length > 0) {
            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'col-md-3 col-sm-4 mb-3';
                    previewCard.innerHTML = `<div class="card"><img src="${e.target.result}" class="img-fluid border-radius-lg" style="height: 150px; object-fit: cover;"></div>`;
                    previewContainer.appendChild(previewCard);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    if (typeof IMask !== 'undefined') {
        const priceInput = document.getElementById('preco-input');
        if (priceInput) {
            IMask(priceInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        radix: ','
                    }
                }
            });
        }
    }
});