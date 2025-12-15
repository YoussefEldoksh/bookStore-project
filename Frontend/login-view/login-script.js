let root = document.querySelector("#root");


document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.login-form');
    
    if (!form) {
        console.error('Form with id "login-form" not found!');
        return;
    }
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.querySelector('input[name="usermail"]').value;
        const password = document.querySelector('input[name="userpass"]').value;
        
        // Validate form
        if (email === "" || password === "") {
            alert("All fields must be filled out!");
            return;
        }

        if (password.length < 7) {
            alert("Password must be at least 7 characters long!");
            return;
        }
        
        try {
            console.log('Sending login request...');
            
            const response = await fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `usermail=${encodeURIComponent(email)}&userpass=${encodeURIComponent(password)}&submit=1`
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get text first to debug
            const text = await response.text();
            console.log('Raw response:', text);
            
            // Check if response is empty
            if (!text || text.length === 0) {
                alert('Server returned empty response. Check PHP file.');
                return;
            }
            
            // Try to parse as JSON
            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Response was:', text);
                alert('Invalid server response. Check browser console.');
                return;
            }
            
            console.log('Parsed data:', data);
            
            if (data.success) {
                // Store token in localStorage
                localStorage.setItem('authToken', data.token);
                localStorage.setItem('user_id', data.user_id);
                localStorage.setItem('username', data.username);
                localStorage.setItem('userType', data.type);
                
                console.log('Login successful, redirecting...');
                
                // Redirect based on user type
                setTimeout(() => {
                    if (data.type === 'Admin') {
                        window.location.href = '../admin-dashboard/dashboard.php';
                    } else {
                        window.location.href = '../home-page/index.html';
                    }
                }, 500);
            } else {
                alert(data.message || 'Login failed');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Login failed: ' + error.message);
        }
    });
});