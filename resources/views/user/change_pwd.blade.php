<form action="{{ route('user.change_password') }}" method="POST" id="change-password-form">
    @csrf
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" required>
            <span class="input-group-text" id="toggle-new-password">
                <i class="fas fa-eye"></i>
            </span>
        </div>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <div class="input-group">
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <span class="input-group-text" id="toggle-confirm-password">
                <i class="fas fa-eye"></i>
            </span>
        </div>
        <div id="password-error" class="text-danger mt-2" style="display: none;">Passwords do not match</div>
    </div>
    <button type="submit" class="btn light-2 btn-show" id="submit-button">Change Password</button>
</form>

@section('additionalscripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility for new password
            const toggleNewPasswordButton = document.getElementById('toggle-new-password');
            const newPasswordField = document.getElementById('password');
            toggleNewPasswordButton.addEventListener('click', function() {
                if (newPasswordField.type === 'password') {
                    newPasswordField.type = 'text';
                    toggleNewPasswordButton.querySelector('i').classList.remove('fa-eye');
                    toggleNewPasswordButton.querySelector('i').classList.add('fa-eye-slash');
                } else {
                    newPasswordField.type = 'password';
                    toggleNewPasswordButton.querySelector('i').classList.remove('fa-eye-slash');
                    toggleNewPasswordButton.querySelector('i').classList.add('fa-eye');
                }
            });

            // Toggle password visibility for confirm password
            const toggleConfirmPasswordButton = document.getElementById('toggle-confirm-password');
            const confirmPasswordField = document.getElementById('confirm_password');
            toggleConfirmPasswordButton.addEventListener('click', function() {
                if (confirmPasswordField.type === 'password') {
                    confirmPasswordField.type = 'text';
                    toggleConfirmPasswordButton.querySelector('i').classList.remove('fa-eye');
                    toggleConfirmPasswordButton.querySelector('i').classList.add('fa-eye-slash');
                } else {
                    confirmPasswordField.type = 'password';
                    toggleConfirmPasswordButton.querySelector('i').classList.remove('fa-eye-slash');
                    toggleConfirmPasswordButton.querySelector('i').classList.add('fa-eye');
                }
            });

            // Validate passwords match before form submission
            const form = document.getElementById('change-password-form');
            form.addEventListener('submit', function(event) {
                const newPassword = newPasswordField.value;
                const confirmPassword = confirmPasswordField.value;

                if (newPassword !== confirmPassword) {
                    event.preventDefault(); // Prevent form submission
                    document.getElementById('password-error').style.display = 'block';
                } else {
                    document.getElementById('password-error').style.display = 'none';
                }
            });
        });
    </script>
@endsection
