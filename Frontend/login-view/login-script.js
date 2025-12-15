
let root = document.querySelector("#root")


function validateForm(){
    let usermail = document.forms["myForm"]["usermail"].value;
    let userpass = document.forms["myForm"]["userpass"].value;

    if (usermail === "" || userpass === "") {
        alert("All fields must be filled out!");
        return false;
    }

    if (userpass.length < 8) {
        alert("Password must be at least 8 characters long!");
        return false;
    }

    return true;
}