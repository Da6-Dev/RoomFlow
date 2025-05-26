const cpf = document.getElementById('cpf-input');
const maskOptionsCpf = {
  mask: '000.000.000-00'
};
const maskCpf = IMask(cpf, maskOptionsCpf);

const telefone = document.getElementById('telefone-input');
const maskOptionsTelefone = {
  mask: '(00)00000-0000'
};
const maskTelefone = IMask(telefone, maskOptionsTelefone);