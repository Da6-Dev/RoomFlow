<?php

// Função de validação para o nome
function validarNome($nome)
{
    $nome = trim($nome);

    if (empty($nome)) {
        return ['status' => 'error', 'msg' => 'O campo nome é obrigatório.'];
    }

    if (strlen($nome) < 3) {
        return ['status' => 'error', 'msg' => 'O nome deve ter pelo menos 3 caracteres.'];
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $nome)) {
        return ['status' => 'error', 'msg' => 'O nome deve conter apenas letras e espaços.'];
    }

    return ['status' => 'success', 'msg' => 'Nome válido.'];
}

// Função de validação para o email
function validarEmail($email)
{
    $email = trim($email);

    // Verifica se o campo email está vazio
    if (empty($email)) {
        return ['status' => 'error', 'msg' => 'O campo e-mail é obrigatório.'];
    }

    // Verifica se o e-mail tem um formato válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['status' => 'error', 'msg' => 'O e-mail informado é inválido.'];
    }
    
    return ['status' => 'success', 'msg' => 'E-mail válido.'];
}

function existsEmail($email)
{
    $email = trim($email);
    $guest = new GuestModel();
    if ($guest->emailExists($email)) {
        return ['status' => 'error', 'msg' => 'O e-mail informado já está cadastrado.'];
    }
    return['status' => 'success', 'msg' => 'E-mail válido.'];
}

function validarTelefone($telefone)
{
    // Verifica se o telefone tem o formato correto: (XX) XXXXX-XXXX ou (XX) XXXX-XXXX
    if (preg_match('/^\(\d{2}\)\s?\d{4,5}-\d{4}$/', $telefone)) {
        return ['status' => 'success', 'msg' => 'Telefone válido.'];
    } else {
        return ['status' => 'error', 'msg' => 'Formato de telefone inválido. Use (XX) XXXXX-XXXX ou (XX) XXXX-XXXX.'];
    }
}

function validarCpf($cpf)
{
    // Remover formatação do CPF (caso o usuário insira com pontos ou traços)
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return ['status' => 'error', 'msg' => 'O CPF deve ter 11 dígitos.'];
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return ['status' => 'error', 'msg' => 'O CPF não pode conter dígitos repetidos.'];
    }

    // Faz o cálculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;

        // Verifica se o dígito calculado bate com o do CPF
        if ($cpf[$c] != $d) {
            return ['status' => 'error', 'msg' => 'CPF inválido.'];
        }
    }

    // Se passar em todas as validações, é um CPF válido
    return ['status' => 'success', 'msg' => 'CPF válido.'];
}

function validarRua($rua)
{
    // Verifica se o campo rua não está vazio
    if (empty($rua)) {
        return ['status' => 'error', 'msg' => 'O campo Rua é obrigatório.'];
    }

    // Verifica se a rua contém apenas letras, números e espaços
    if (!preg_match('/^[a-zA-Z0-9\s,\.]+$/', $rua)) {
        return ['status' => 'error', 'msg' => 'O campo Rua contém caracteres inválidos.'];
    }

    return ['status' => 'success', 'msg' => 'Rua válida.'];
}

function validarCidade($cidade)
{
    // Verifica se o campo cidade não está vazio
    if (empty($cidade)) {
        return ['status' => 'error', 'msg' => 'O campo Cidade é obrigatório.'];
    }

    // Verifica se a cidade contém apenas letras e espaços
    if (!preg_match('/^[a-zA-Z\s]+$/', $cidade)) {
        return ['status' => 'error', 'msg' => 'O campo Cidade contém caracteres inválidos.'];
    }

    return ['status' => 'success', 'msg' => 'Cidade válida.'];
}

function validarEstado($estado)
{
    // Verifica se o campo estado não está vazio
    if (empty($estado)) {
        return ['status' => 'error', 'msg' => 'O campo Estado é obrigatório.'];
    }

    // Verifica se o estado tem exatamente 2 letras maiúsculas
    if (!preg_match('/^[A-Z]{2}$/', $estado)) {
        return ['status' => 'error', 'msg' => 'O campo Estado deve conter uma sigla válida de 2 letras.'];
    }

    return ['status' => 'success', 'msg' => 'Estado válido.'];
}

function validarNumero($numero)
{
    // Verifica se o campo numero não está vazio
    if (empty($numero)) {
        return ['status' => 'error', 'msg' => 'O campo Número é obrigatório.'];
    }

    // Verifica se o número é válido (apenas números inteiros positivos)
    if (!filter_var($numero, FILTER_VALIDATE_INT) || $numero <= 0) {
        return ['status' => 'error', 'msg' => 'O campo Número deve ser um valor inteiro positivo.'];
    }

    return ['status' => 'success', 'msg' => 'Número válido.'];
}

function validarCep($cep)
{
    // Remove caracteres não numéricos
    $cep = preg_replace('/\D/', '', $cep);

    // Verifica se o CEP tem exatamente 8 dígitos
    if (strlen($cep) != 8) {
        return ['status' => 'error', 'msg' => 'O CEP deve conter 8 dígitos numéricos.'];
    }

    // Verifica se o CEP contém apenas números
    if (!preg_match('/^[0-9]{8}$/', $cep)) {
        return ['status' => 'error', 'msg' => 'CEP inválido. Deve conter 8 dígitos numéricos.'];
    }

    return ['status' => 'success', 'msg' => 'CEP válido.'];
}

function validarDataNascimento($dataNasc)
{
    // Verifica se o campo data de nascimento não está vazio
    if (empty($dataNasc)) {
        return ['status' => 'error', 'msg' => 'O campo Data de Nascimento é obrigatório.'];
    }

    // Verifica se a data de nascimento está no formato correto (Y-m-d)
    $dataNascimento = DateTime::createFromFormat('Y-m-d', $dataNasc);
    if (!$dataNascimento || $dataNascimento->format('Y-m-d') !== $dataNasc) {
        return ['status' => 'error', 'msg' => 'A Data de Nascimento é inválida. Use o formato AAAA-MM-DD.'];
    }

    // Verifica se o usuário tem pelo menos 18 anos
    $hoje = new DateTime();
    $idade = $hoje->diff($dataNascimento)->y;

    if ($idade < 18) {
        return ['status' => 'error', 'msg' => 'O hóspede deve ter pelo menos 18 anos.'];
    }

    return ['status' => 'success', 'msg' => 'Data de nascimento válida.'];
}
