// O JavaScript não precisa de alterações funcionais.
document.addEventListener('DOMContentLoaded', function () {
    IMask(document.getElementById('cpf-input'), { mask: '000.000.000-00' });
    IMask(document.getElementById('cep-input'), { mask: '00000-000' });
    IMask(document.getElementById('telefone-input'), { mask: '(00) 00000-0000' });


    const addPrefBtn = document.getElementById('add-preference-btn');
    const preferencesContainer = document.getElementById('preferences-container');
    addPrefBtn.addEventListener('click', function () {
        const newPreferenceItem = document.createElement('div');
        newPreferenceItem.classList.add('row', 'align-items-center', 'preference-item', 'mb-2');
        newPreferenceItem.innerHTML = `
            <div class="col-10">
                <div class="input-group input-group-outline is-filled my-3">
                    <label class="form-label">Preferência</label>
                    <input type="text" class="form-control" name="preferencias[]">
                </div>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-icon-only btn-link text-danger remove-pref-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            </div>`;
        preferencesContainer.appendChild(newPreferenceItem);
        newPreferenceItem.querySelector('input').focus();
    });
    preferencesContainer.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.remove-pref-btn');
        if (removeButton) {
            removeButton.closest('.preference-item').remove();
        }
    });

    const imageUpload = document.getElementById('image-upload');
    const imagePreview = document.getElementById('image-preview');
    const fileName = document.getElementById('file-name');
    imageUpload.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
            fileName.textContent = file.name;
        }
    });
});