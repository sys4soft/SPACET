// main.js

// ================================================================
function gerarPassword(numLetras){
    //gerar uma password aleatória
    let text_password = document.getElementById('text_password');

    let caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?()-%';
    let codigo = '';
    for(let i=0; i < numLetras ; i++){
        let r = Math.floor( Math.random() * caracteres.length) + 1;
        codigo += caracteres.substr(r,1);
    }

    //coloca o código no campo da password
    text_password.value = codigo;    
}

// ================================================================
function checks(estado){
    $('input:checkbox').prop('checked', estado);
}