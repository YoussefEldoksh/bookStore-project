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

    window.removeBook = function(bookId) {
        if (confirm("Are you sure you want to remove this book?")) {
            fetch(`removeBook.php?book_id=${bookId}`, {
                method: "GET",
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert("Book removed successfully!");
                    location.reload();
                } else {
                    alert("Error removing book.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while removing the book.");
            });
        }
    };
});
