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
    if (!preg_match('/^[\p{L}\s\p{P}]+$/u', $rua)) {
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
    if (!preg_match('/^[\p{L}\s\p{P}]+$/u', $cidade)) {
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

function validarTipo($tipo)
{
    // Verifica se o campo tipo não está vazio
    if (empty($tipo)) {
        return ['status' => 'error', 'msg' => 'O campo Tipo é obrigatório.'];
    }

    // Verifica se o tipo contém apenas letras e espaços
    if (!preg_match('/^[\p{L}\s\p{P}]+$/u', $tipo)) {
        return ['status' => 'error', 'msg' => 'O campo Tipo contém caracteres inválidos.'];
    }

    return ['status' => 'success', 'msg' => 'Tipo válido.'];
}

function validarDescricao($descricao)
{
    // Verifica se o campo descrição não está vazio
    if (empty($descricao)) {
        return ['status' => 'error', 'msg' => 'O campo Descrição é obrigatório.'];
    }

    // Verifica se a descrição tem pelo menos 10 caracteres
    if (strlen($descricao) < 10) {
        return ['status' => 'error', 'msg' => 'A Descrição deve ter pelo menos 10 caracteres.'];
    }

    return ['status' => 'success', 'msg' => 'Descrição válida.'];
}

function validarStatus($status)
{
    // Verifica se o campo status não está vazio
    if (empty($status)) {
        return ['status' => 'error', 'msg' => 'O campo Status é obrigatório.'];
    }

    // Verifica se o status é um dos valores permitidos
    $statusPermitidos = ['disponivel', 'ocupado', 'manutencao'];
    if (!in_array(strtolower($status), $statusPermitidos)) {
        return ['status' => 'error', 'msg' => 'O Status deve ser "Disponível", "Ocupado" ou "Manutenção".'];
    }

    return ['status' => 'success', 'msg' => 'Status válido.'];
}

function validarCapacidade($capacidade)
{
    // Verifica se o campo capacidade não está vazio
    if (empty($capacidade)) {
        return ['status' => 'error', 'msg' => 'O campo Capacidade é obrigatório.'];
    }

    // Verifica se a capacidade é um número inteiro positivo
    if (!filter_var($capacidade, FILTER_VALIDATE_INT) || $capacidade <= 0) {
        return ['status' => 'error', 'msg' => 'A Capacidade deve ser um número inteiro positivo.'];
    }

    return ['status' => 'success', 'msg' => 'Capacidade válida.'];
}

function validarPreco($preco)
{
    // Verifica se o campo preço não está vazio
    if (empty($preco)) {
        return ['status' => 'error', 'msg' => 'O campo Preço é obrigatório.'];
    }

    // Verifica se o preço é um número positivo
    if (!is_numeric($preco) || $preco <= 0) {
        return ['status' => 'error', 'msg' => 'O Preço deve ser um número positivo.'];
    }

    return ['status' => 'success', 'msg' => 'Preço válido.'];
}

function validarMinimoNoites($minimo_noites)
{
    // Verifica se o campo mínimo de noites não está vazio
    if (empty($minimo_noites)) {
        return ['status' => 'error', 'msg' => 'O campo Mínimo de Noites é obrigatório.'];
    }

    // Verifica se o mínimo de noites é um número inteiro positivo
    if (!filter_var($minimo_noites, FILTER_VALIDATE_INT) || $minimo_noites <= 0) {
        return ['status' => 'error', 'msg' => 'O Mínimo de Noites deve ser um número inteiro positivo.'];
    }

    return ['status' => 'success', 'msg' => 'Mínimo de noites válido.'];
}

function validarCamasCasal($camas_casal)
{
    // Verifica se o campo foi enviado (permite 0 ou '0', mas não vazio)
    if (!isset($camas_casal) || trim($camas_casal) === '') {
        return ['status' => 'error', 'msg' => 'O campo Camas de Casal é obrigatório.'];
    }

    // Verifica se é um número inteiro não negativo
    if (!ctype_digit(strval($camas_casal))) {
        return ['status' => 'error', 'msg' => 'O número de Camas de Casal deve ser um número inteiro maior ou igual a zero.'];
    }

    return ['status' => 'success', 'msg' => 'Camas de casal válido.'];
}

function validarCamasSolteiro($camas_solteiro)
{
    // Verifica se o campo foi enviado (permite 0 ou '0', mas não vazio)
    if (!isset($camas_solteiro) || trim($camas_solteiro) === '') {
        return ['status' => 'error', 'msg' => 'O campo Camas de Solteiro é obrigatório.'];
    }

    // Força conversão para número inteiro
    if (!ctype_digit(strval($camas_solteiro))) {
        return ['status' => 'error', 'msg' => 'O número de Camas de Solteiro deve ser um número inteiro maior ou igual a zero.'];
    }

    return ['status' => 'success', 'msg' => 'Camas de solteiro válido.'];
}

function validarCheckInTime($check_in_time)
{
    // Verifica se o campo check-in time não está vazio
    if (empty($check_in_time)) {
        return ['status' => 'error', 'msg' => 'O campo Check-in Time é obrigatório.'];
    }

    return ['status' => 'success', 'msg' => 'Check-in time válido.'];
}

function validarCheckOutTime($check_out_time)
{
    // Verifica se o campo check-out time não está vazio
    if (empty($check_out_time)) {
        return ['status' => 'error', 'msg' => 'O campo Check-out Time é obrigatório.'];
    }

    return ['status' => 'success', 'msg' => 'Check-out time válido.'];
}

