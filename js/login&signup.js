function showAlert(message) {
    const alertBox = document.getElementById('alertBox');
    const alertMessage = document.getElementById('alertMessage');
    const overlay = document.getElementById('overlay');

    alertMessage.textContent = message;
    alertBox.style.display = 'block';
    overlay.style.display = 'block';
}


function closeAlert() {
    const alertBox = document.getElementById('alertBox');
    const overlay = document.getElementById('overlay');

    alertBox.style.display = 'none';
    overlay.style.display = 'none';
}
function formMessage(event,message){
    showAlert(message);
    event.preventDefault();
}


function validateForm(isLogin,event,invalidEmailOrPassword) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

    if(isLogin === 1){
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!emailPattern.test(email)) {
        showAlert('Invalid email format.');
        event.preventDefault();
    } else if (!passwordPattern.test(password)) {
        showAlert('Password must be at least 8 characters long and include at least one letter and one number.');
        event.preventDefault();
    } else if(!invalidEmailOrPassword) {
        showAlert('Invalid Email or passowrd!');
        event.preventDefault();
    }
}else {
    
    const email = document.getElementById('s-email').value.trim();
    const password = document.getElementById('s-password').value.trim();
    const confimPassword = document.getElementById('s-c-password').value.trim();
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const role = document.querySelector('.role');
    const namePattern = "/^[a-zA-Z]{3,}$/";
    

  

    if (firstName == "") {
        showAlert('First name is required.');
        event.preventDefault();
    }
    else if (firstName.length < 3) {
        showAlert('First name must be at least 3 characters long.');
        event.preventDefault();
    } else if (!namePattern.test(firstName)) {
        showAlert('First name can only contain alphabetic characters.');
        event.preventDefault();
    }

    if (lastName == "") {
        showAlert('Last name is required.');
        event.preventDefault();
    }
    else if (lastName.length < 3) {
        showAlert('Last name must be at least 3 characters long.');
        event.preventDefault();
    }
    else if (!namePattern.test(lastName)) {
        showAlert('Last name can only contain alphabetic characters.');
        event.preventDefault();
    }

    if (!emailPattern.test(email)) {
        showAlert('Invalid email format.');
        event.preventDefault();
    } else if (!passwordPattern.test(password)) {
        showAlert('Password must be at least 8 characters long and include at least one letter and one number.');
        event.preventDefault();
    } else if (password !== confimPassword){
        showAlert('Confim you password!.');
        event.preventDefault();
    }


    if(role.value == 2){
        const specialty = document.getElementById('specialty').value;
        const description = document.getElementById('description').value;
        const specialtyPattern = "/^[1-9]$/";

        if(!specialtyPattern.test(specialty)){
            showAlert('Choose your specialty!.');
            event.preventDefault();
        }else if (description == "" || description.length < 10){
            showAlert('Please write a biref description about your self, it must be > 10 chars.');
        event.preventDefault();
        }
    }else{
        showAlert('Please choose your Role!.');
        event.preventDefault();
    }
    
    

}
}