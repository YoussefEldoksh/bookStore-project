document.addEventListener("DOMContentLoaded", () => {
    const logoutLink = document.getElementById("logout-link");
    const logoutModal = document.getElementById("logout-modal");
    const logoutYes = document.getElementById("logout-yes");
    const logoutNo = document.getElementById("logout-no");

    logoutLink.addEventListener("click", (e) => {
        e.preventDefault();
        logoutModal.style.display = "flex";
    });

    logoutNo.addEventListener("click", () => {
        logoutModal.style.display = "none";
    });

    logoutYes.addEventListener("click", () => {
        window.location.href = "../admin-login-view/login.php";
    });
});
