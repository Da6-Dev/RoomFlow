const prefadd = document.querySelector("#prefadd");
const prefless = document.querySelector("#prefless");
const divpref = document.querySelector('#preferences');

// Inicializa o contador com o número de preferências já existentes
var npref = divpref.querySelectorAll('.input-group').length;

prefadd.addEventListener('click', () => {
    npref = npref + 1;
    divpref.innerHTML += `
        <div class="col-md-12">
            <div class="input-group input-group-outline my-3 is-filled" id="pref${npref}">
                <label class="form-label">Preferência ${npref}</label>
                <input type="text" class="form-control" name="pref${npref}" required>
            </div>
        </div>
    `;
});

prefless.addEventListener('click', () => {
    if (npref > 0) {
        const lastPref = document.querySelector(`#pref${npref}`);
        lastPref.remove();
        npref = npref - 1;
    }
});

