const firstname = document.getElementById('firstname')
const lastname = document.getElementById('lastname')
const password = document.getElementById('pass1_field')
const form = document.getElementById('formular')
const errorElement = document.getElementById('error')


form.addEventListener('submit', (e) => {
    let messages = []
    if (firstname.value === ''){
        messages.push('Jméno je povinné')
    }
    if (messages.lenght > 0){
        e.preventDefault()
        errorElement.innerText = messages.join(',')
    }
})