
const BASE_URL = 'http://localhost/BkpmWebServer/acara25/public';

const formLogin = document.getElementById('form-login');
const pesan = document.getElementById('pesan');

formLogin.addEventListener('submit', async function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    pesan.textContent = 'Sedang login...';

    try {
        const response = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            pesan.textContent = hasil.message || 'Login gagal';
            return;
        }

        localStorage.setItem('token', hasil.data.token);
        localStorage.setItem('role', hasil.data.role);

        window.location.href = 'dashboard.html';

    } catch (error) {
        pesan.textContent = 'Tidak dapat terhubung ke server';
        console.error(error);
    }
});
