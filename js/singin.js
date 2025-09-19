document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('showBankForm').onclick = function() {
        document.getElementById('bankForm').classList.remove('hidden');
        document.getElementById('userFields').classList.add('hidden');
    };

    document.getElementById('backToUserForm').onclick = function() {
        document.getElementById('bankForm').classList.add('hidden');
        document.getElementById('userFields').classList.remove('hidden');
    };
});