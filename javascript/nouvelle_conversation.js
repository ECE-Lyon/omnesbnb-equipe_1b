
document.querySelectorAll('.utilisateur').forEach(user => {
    const panel = user.querySelector('.verif-email');
    const btn = user.querySelector('.btn-verif');
    const input = user.querySelector('input');
    const erreur = user.querySelector('.msg-erreur');

    user.addEventListener('click', () => {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        input.focus();
    });

    btn.addEventListener('click', () => {
        const email = input.value.trim();
        const id = user.dataset.id;

        if (!email) {
            erreur.style.display = 'block';
            erreur.textContent = "Veuillez saisir un email.";
            return;
        }

        fetch('verif_email.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}&email=${encodeURIComponent(email)}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    erreur.style.display = 'block';
                    erreur.textContent = "Email incorrect.";
                }
            })
            .catch(() => {
                erreur.style.display = 'block';
                erreur.textContent = "Erreur serveur.";
            });
    });
});
