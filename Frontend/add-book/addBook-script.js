const form = document.getElementById("addBookForm");
const sidebarLinks = document.querySelectorAll(".menu a");

form.addEventListener("submit", function (e) {
    const isbn = document.getElementById("isbn");
    const title = document.getElementById("title");
    const publisher = document.getElementById("pub_id");
    const pubYear = document.getElementById("pub_year");
    const price = document.getElementById("price");
    const quantity = document.getElementById("quantity");
    const threshold = document.getElementById("threshold");
    const category = document.getElementById("category");

    let valid = true;

    // Required fields
    const fields = [title, publisher, pubYear, price, quantity, threshold, category];
    fields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = "red";
            valid = false;
        } else {
            field.style.borderColor = "#ccc";
        }
    });

    if (!valid) {
        e.preventDefault();
        alert("Please fill in or choose all required fields.");
        return;
    }

    if (!/^\d{10}(\d{3})?$/.test(isbn.value)) {
        isbn.style.borderColor = "red";
        alert("Invalid ISBN format");
        e.preventDefault();
        return;
    }

    // Convert numeric values
    const sellingPrice = parseFloat(price.value);
    const qty = parseInt(quantity.value);
    const minStock = parseInt(threshold.value);

    // Price validation
    if (sellingPrice <= 0) {
        e.preventDefault();
        alert("Selling price must be greater than 0.");
        price.style.borderColor = "red";
        return;
    }

    // Quantity & threshold validation
    if (qty < 0 || minStock < 0) {
        e.preventDefault();
        alert("Quantity and stock threshold cannot be negative.");
        return;
    }

    // Quantity must be >= threshold
    if (qty < minStock) {
        e.preventDefault();
        alert("Quantity must be greater than or equal to the stock threshold.");
        quantity.style.borderColor = "red";
        threshold.style.borderColor = "red";
        return;
    }

    const authorInputs = document.querySelectorAll('input[name="authors[]"]');
    for (const author of authorInputs) {
        if (!author.value.trim()) {
            alert("Please fill in all author names.");
            author.style.borderColor = "red";
            e.preventDefault();
            return;
        } else {
            author.style.borderColor = "#ccc";
        }
    }
    
});



sidebarLinks.forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();

        // Remove active class from all links
        sidebarLinks.forEach(l => l.classList.remove("active"));
        // Add active class to clicked link
        link.classList.add("active");

        const section = link.dataset.section.toLowerCase();

        switch (section) {
            case "home":
                window.location.href = "../admin-page/adminPage.php"; // replace with actual profile page
                break;
            case "profile":
                window.location.href = "../admin-profile.php"; // replace with actual profile page
                break;
            case "search":
                window.location.href = "../search.php"; // replace with actual search page
                break;
            case "logout":
                window.location.href = "../logout.php"; // logout page
                break;
        }
    });
});