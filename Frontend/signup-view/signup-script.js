
let root = document.querySelector("#root")

function validateForm(){
    let username= document.forms["myForm"]["username"].value;
    let usermail= document.forms["myForm"]["usermail"].value;
    let userpass= document.forms["myForm"]["userpass"].value;
    // let cpass= document.forms["myForm"]["cpass"].value;
    // Check for empty fields
  if (username === "" || usermail === "" || userpass === "") {
    alert("All fields must be filled out!");
    return false;
  }

  // Check password length
  if (userpass.length < 8) {
    alert("Password must be at least 8 characters long!");
    return false;
  }

  // Check password match

  return true; // allow submission
}