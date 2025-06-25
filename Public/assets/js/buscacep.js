// Seleciona os elementos do formulário uma única vez para melhor performance
const cepInput = document.getElementById('cep-input');
const ruaInput = document.getElementById('rua-input');
const cidadeInput = document.getElementById('cidade-input');
const estadoInput = document.getElementById('estado-input');
const numeroInput = document.getElementById('numero-input'); // Campo para onde o foco deve ir

// Função auxiliar para exibir erros abaixo do campo CEP
function showCepError(message) {
    // Remove qualquer erro anterior
    let oldError = cepInput.parentElement.querySelector('.cep-error-message');
    if (oldError) {
        oldError.remove();
    }
    // Adiciona a nova mensagem de erro
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-danger text-xs mt-n2 ms-1 cep-error-message';
    errorDiv.textContent = message;
    cepInput.parentElement.after(errorDiv);
}

// Função auxiliar para limpar os campos de endereço
function clearAddressFields() {
    ruaInput.value = '';
    cidadeInput.value = '';
    estadoInput.value = '';
    // Remove a classe que faz o label flutuar
    ruaInput.parentElement.classList.remove('is-filled');
    cidadeInput.parentElement.classList.remove('is-filled');
    estadoInput.parentElement.classList.remove('is-filled');
}

// Função para controlar o estado de "carregando" dos campos
function setFieldsLoading(isLoading) {
    const fields = [cepInput, ruaInput, cidadeInput, estadoInput];
    if (isLoading) {
        cepInput.parentElement.querySelector('.form-label').textContent = 'Buscando...';
        fields.forEach(field => field.disabled = true);
    } else {
        cepInput.parentElement.querySelector('.form-label').textContent = 'CEP';
        fields.forEach(field => field.disabled = false);
    }
}

// Substitua o script no final da sua página por este:

cepInput.addEventListener('blur', async function() {
    const cepValue = cepInput.value.replace(/\D/g, '');
    showCepError(''); 

    if (cepValue.length !== 8) {
        clearAddressFields();
        return;
    }

    setFieldsLoading(true);

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cepValue}/json/`);
        
        // Adicionada verificação de status da resposta
        if (!response.ok) {
            throw new Error(`Erro na rede: Status ${response.status}`);
        }

        const data = await response.json();

        if (data.erro) {
            clearAddressFields();
            showCepError('CEP não encontrado.');
        } else {
            ruaInput.value = data.logradouro || '';
            cidadeInput.value = data.localidade || '';
            estadoInput.value = data.uf || '';
            [ruaInput, cidadeInput, estadoInput].forEach(input => {
                if (input.value) input.parentElement.classList.add('is-filled');
            });
            numeroInput.focus();
        }
    } catch (error) {
        // Log do erro completo no console para diagnóstico
        console.error('Houve um problema com a requisição fetch:', error);
        
        clearAddressFields();
        // Mensagem mais específica para o usuário
        showCepError('Erro de comunicação. Verifique o console (F12).');

    } finally {
        setFieldsLoading(false);
    }
});