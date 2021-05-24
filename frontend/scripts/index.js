window.onload = function() {
    getAll();
}

function applyMask() {
    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
      },
      spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
          }
      };
      
    $('#phone').mask(SPMaskBehavior, spOptions);
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#cpf').trigger('input');
    $('#cep').mask('00000-000');
    $('#cep').trigger('input');
}

var modal = document.getElementById('registerModal');

modal.addEventListener('show.bs.modal', function () {
    applyMask();
});

modal.addEventListener('hidden.bs.modal', function () {
    cleanModal();
});

function cleanModal() {
    document.querySelector("#id").value = "";
    document.querySelector("#name").value = "";
    document.querySelector("#email").value = "";
    document.querySelector("#birthDate").value = "";
    document.querySelector("#cpf").value = "";
    document.querySelector("#phone").value = "";
    document.querySelector("#observation").value = "";
    document.querySelector("#cep").value = "";
    document.querySelector("#street").value = "";
    document.querySelector("#neighborhood").value = "";
    document.querySelector("#number").value = "";
    document.querySelector("#complement").value = "";
    document.querySelector("#city").value = "";
    document.querySelector("#state").value = "";
}

function fillModal(content) {
    document.querySelector("#id").value = content.id;
    document.querySelector("#name").value = content.name;
    document.querySelector("#email").value = content.email;
    document.querySelector("#birthDate").value = content.birthDate;
    document.querySelector("#cpf").value = content.cpf;
    document.querySelector("#phone").value = content.phone;
    document.querySelector("#observation").value = content.observation;
    document.querySelector("#cep").value = content.address.cep;
    document.querySelector("#street").value = content.address.street;
    document.querySelector("#neighborhood").value = content.address.neighborhood;
    document.querySelector("#number").value = content.address.number;
    document.querySelector("#complement").value = content.address.complement;
    document.querySelector("#city").value = content.address.city;
    document.querySelector("#state").value = content.address.state;

    applyMask();
}

function getAll() {

    var request = new XMLHttpRequest();

    request.open("GET", "http://localhost/projeto_newm/api/endpoints/clients/getAll.php");

    request.onreadystatechange = function() {
        
        if(this.readyState === 4 && this.status === 200) {    

            var resultado = JSON.parse(this.responseText);

            if(resultado.success) {

                var tbody = document.getElementById("tbody");

                tbody.innerHTML = "";
                
                for(let i = 0; i < resultado.content.length; i++) {

                    let client = resultado.content[i];

                    var tr = document.createElement("tr");

                    var tdId = document.createElement("td");
                    tdId.textContent = client.id;
                    tr.appendChild(tdId);

                    var tdName = document.createElement("td");
                    tdName.textContent = client.name;
                    tr.appendChild(tdName);

                    var tdEmail = document.createElement("td");
                    tdEmail.textContent = client.email;
                    tr.appendChild(tdEmail);

                    var tdPhone = document.createElement("td");
                    tdPhone.textContent = client.phone;
                    tr.appendChild(tdPhone);

                    var tdAddress = document.createElement("td");
                    tdAddress.textContent = client.address.city + " " + client.address.street;
                    tr.appendChild(tdAddress);

                    var tdButtons = document.createElement("td");
                    
                    var tdButtonUpdate = document.createElement("button");
                    tdButtonUpdate.setAttribute("class", "btn btn-primary btn-sm updateButton");
                    tdButtonUpdate.setAttribute("data-bs-toggle", "modal");
                    tdButtonUpdate.setAttribute("data-bs-target", "#registerModal");
                    tdButtonUpdate.setAttribute("data-content", JSON.stringify(client));
                    tdButtonUpdate.onclick = function() {
                        fillModal(JSON.parse(this.getAttribute("data-content")));
                    }
                    tdButtonUpdate.textContent = "Editar";
                    
                    var tdButtonDelete = document.createElement("button");
                    tdButtonDelete.setAttribute("class", "btn btn-danger btn-sm deleteButton");
                    tdButtonDelete.setAttribute("data-id", client.id);
                    tdButtonDelete.onclick = function() {
                        deleteClient(this.getAttribute("data-id"));
                    }
                    tdButtonDelete.textContent = "Deletar";

                    tdButtons.appendChild(tdButtonUpdate);
                    tdButtons.appendChild(tdButtonDelete);

                    tr.appendChild(tdButtons);

                    tbody.appendChild(tr);
                }
            }
        }
    };

    // Sending the request to the server
    request.send();
}

function deleteClient(id) {

    console.log(id);

    swal("Deletar registro", "Você tem certeza que deseja deletar esse registro?", {
        buttons: {
            cancel: {
                text: "Cancelar",
                value: false,
                visible: true,
                className: "",
                closeModal: true,
            },
        
            confirm: {
                text: "Deletar",
                value: true,
                visible: true,
                className: "bg-danger",
                closeModal: true
            },
        }, 
    }).then((e) => {
        if(e) {
            
            var request = new XMLHttpRequest();

            request.open("DELETE", `http://localhost/projeto_newm/api/endpoints/clients/delete.php?id=${id}`);

            request.onreadystatechange = function() {
                
                if(this.readyState === 4 && this.status === 200) {

                    var resultado = JSON.parse(this.responseText);

                    if(resultado.success) {

                        swal("Sucesso", "Registro deletado com sucesso" , "success");
                        getAll();

                    } else if(resultado.notFound) {
                        swal("Erro", "Não foi possível achar o usuário desse registro", "error");
                    } else {
                        swal("Erro", "Algo de errado aconteceu, por favor, entre em contato com o suporte", "error");
                    }
                }
            };

            // Sending the request to the server
            request.send();
        }
    });
}

function save() {

    if(!validate()) return false;

    let content = getData();

    let method = content.id == "" || content.id == null
        ? "POST"
        : "PUT";

    let url = content.id == "" || content.id == null
        ? "http://localhost/projeto_newm/api/endpoints/clients/insert.php"
        : "http://localhost/projeto_newm/api/endpoints/clients/update.php";

    console.log(url);
    console.log(content);

    let request = new XMLHttpRequest();
    request.open("POST", url, true);
    request.setRequestHeader("Content-Type", "application/json");

    request.onreadystatechange = function() {
        
        if(request.readyState === XMLHttpRequest.DONE && request.status === 200) {

            console.log(request);
            var resultado = JSON.parse(request.responseText);

            if(resultado.success) {

                swal("Sucesso", "Registro salvo com sucesso" , "success");
                getAll();

            } else if(resultado.error) {
                swal("Erro", resultado.messages.join("\n"), "error");
            } else if(resultado.notFound) {
                swal("Erro", "Não foi possível achar o usuário desse registro", "error");
            } else {
                swal("Erro", "Algo de errado aconteceu no servidor, por favor, entre em contato com o suporte", "error");
            }

            document.getElementsByClassName('btn-close')[0].click();
            cleanModal();
        }
    };

    request.send(JSON.stringify(content));
}

function getData() {
    return {
        "id":  document.querySelector("#id").value,
        "name":  document.querySelector("#name").value,
        "email":  document.querySelector("#email").value,
        "birthDate":  document.querySelector("#birthDate").value,
        "cpf":  document.querySelector("#cpf").value.replaceAll(".","").replaceAll("-",""),
        "phone":  document.querySelector("#phone").value.replaceAll("(","").replaceAll(")","").replaceAll(" ","").replaceAll("-",""),
        "observation":  document.querySelector("#observation").value,
        "address" : {
            "cep":  document.querySelector("#cep").value.replaceAll("-",""),
            "street":  document.querySelector("#street").value,
            "neighborhood":  document.querySelector("#neighborhood").value,
            "number":  document.querySelector("#number").value,
            "complement":  document.querySelector("#complement").value,
            "city":  document.querySelector("#city").value,
            "state":  document.querySelector("#state").value,
        }
    }
}

function validate() {

    let name = document.querySelector("#name").value;
    let email = document.querySelector("#email").value;
    let birthDate = document.querySelector("#birthDate").value;
    let cpf = document.querySelector("#cpf").value;
    let phone = document.querySelector("#phone").value;
    let observation = document.querySelector("#observation").value;
    let cep = document.querySelector("#cep").value;
    let street = document.querySelector("#street").value;
    let neighborhood = document.querySelector("#neighborhood").value;
    let number = document.querySelector("#number").value;
    let complement = document.querySelector("#complement").value;
    let city = document.querySelector("#city").value;
    let state = document.querySelector("#state").value;

    let errors = [];
    
    if(!validateName(name)) errors.push("Nome é obrigatório e somente letras são aceitas");
    if(!validateEmail(email)) errors.push("Email é obrigatório");
    if(!validateBirthDate(birthDate)) errors.push("Data de nascimento é obrigatório");
    if(!validateCpf(cpf)) errors.push("CPF é obrigatório e precisa ser válido");
    if(!validatePhone(phone)) errors.push("Celular é obrigatório");
    if(!validateObservation(observation)) errors.push("Observação é obrigatório");
    if(!validateCep(cep)) errors.push("CEP é obrigatório");
    if(!validateStreet(street)) errors.push("Rua é obrigatório");
    if(!validateNeighborhood(neighborhood)) errors.push("Bairro é obrigatório");
    if(!validateNumber(number)) errors.push("Número é obrigatório");
    if(!validateComplement(complement)) errors.push("Complemento é obrigatório");
    if(!validateCity(city)) errors.push("Cidade é obrigatório");
    if(!validateState(state)) errors.push("Estado é obrigatório");

    if(errors.length > 0) {
        swal("Erro na validação", errors.join("\n"), "warning");
        return false;
    }

    return true;
}

function validateName(value) {

    if(!value.match(/[a-zA-ZÀ-Ā]+/g)) {
        return false;
    }

    return (value == null || value == "") ? false : true;
}

function validateEmail(value) {
    return (value == null || value == "") ? false : true;
}

function validateBirthDate(value) {
    return (value == null || value == "") ? false : true;
}

function validateCpf(value) {

    if(!isValidCPF(value)) return false;

    return (value == null || value == "") ? false : true;
}

function validatePhone(value) {
    return (value == null || value == "") ? false : true;
}

function validateObservation(value) {
    return (value.length > 300) ? false : true;
}

function validateCep(value) {
    return (value == null || value == "") ? false : true;
}

function validateStreet(value) {
    return (value == null || value == "") ? false : true;
}

function validateNeighborhood(value) {
    return (value == null || value == "") ? false : true;
}

function validateNumber(value) {
    return (value == null || value == "") ? false : true;
}

function validateComplement(value) {
    return (value == null || value == "") ? false : true;
}

function validateCity(value) {
    return (value == null || value == "") ? false : true;
}

function validateState(value) {
    return (value == null || value == "") ? false : true;
}

// Função pega do site: https://gist.github.com/joaohcrangel/8bd48bcc40b9db63bef7201143303937
function isValidCPF(cpf) {
    if (typeof cpf !== "string") return false
    cpf = cpf.replace(/[\s.-]*/igm, '')
    if (
        !cpf ||
        cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999" 
    ) {
        return false
    }
    var soma = 0
    var resto
    for (var i = 1; i <= 9; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(9, 10)) ) return false
    soma = 0
    for (var i = 1; i <= 10; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(10, 11) ) ) return false
    return true
}