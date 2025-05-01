document.addEventListener('DOMContentLoaded', function (e){
    const btn = document.querySelector('#button-view-pwd');
    btn.addEventListener('click', function (e){
        let inputPwd = document.getElementById('password');
        showPassword(inputPwd, this);
    });
});

// for show password
function showPassword(inputPwd, ele) {
    inputPwd.type = inputPwd.type === "password" ? "text" : "password";
    ele.childNodes[0].classList.toggle("ri-eye-line");
    ele.childNodes[0].classList.toggle("ri-eye-off-line");
}
