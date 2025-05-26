cep.addEventListener('blur', function() {
    const cepValue = cep.value.replace(/\D/g, ''); // Remove non-numeric characters
    if (cepValue.length === 8) {
        fetch(`https://viacep.com.br/ws/${cepValue}/json/`)
        .then(response => response.json())
        .then(data => {
            if (!data.erro) {
            document.getElementById('rua-input').value = data.logradouro || '';
            document.getElementById('rua-input').focus();
            document.getElementById('uf-input').value = data.uf || '';
            document.getElementById('uf-input').focus();
            document.getElementById('cidade-input').value = data.localidade || '';
            document.getElementById('cidade-input').focus();
            } else {
            document.getElementById('rua-input').value = '';
            document.getElementById('uf-input').value = '';
            document.getElementById('cidade-input').value = '';
            alert('CEP não encontrado.');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            alert('Erro ao buscar CEP. Tente novamente mais tarde.');
        });
    }
    else {
        document.getElementById('rua-input').value = '';
        document.getElementById('uf-input').value = '';
        document.getElementById('cidade-input').value = '';
    }
}
);