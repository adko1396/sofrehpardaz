function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleBtn = document.querySelector('.show-hide-btn');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = 'مخفی';
      } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = 'نمایش';
      }
    }