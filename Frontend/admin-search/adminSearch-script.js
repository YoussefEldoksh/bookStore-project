document.addEventListener("DOMContentLoaded", () => {
    // --- LOGOUT HANDLING ---
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

    // --- MODIFY / REMOVE BUTTON HANDLING ---
    window.modifyBook = function(bookId) {
        window.location.href = `modifyBook.php?book_id=${bookId}`;
    };

});
