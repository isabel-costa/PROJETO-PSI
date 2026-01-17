package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class LoginActivity extends AppCompatActivity {

    private EditText etUsername, etPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Se já houver token válido, ir direto para MainActivity
        if (SingletonGestorAPI.getInstance(this).isLoggedIn()) {
            startActivity(new Intent(this, MainActivity.class));
            finish();
            return;
        }

        setContentView(R.layout.activity_login);
        setTitle("Login");

        etUsername = findViewById(R.id.txt_Username);
        etPassword = findViewById(R.id.txt_Password);

        etPassword.setOnTouchListener((v, event) -> {
            if (event.getAction() == android.view.MotionEvent.ACTION_UP) {

                if (event.getRawX() >= (etPassword.getRight()
                        - etPassword.getCompoundDrawables()[2].getBounds().width())) {

                    if (etPassword.getTransformationMethod() instanceof
                            android.text.method.PasswordTransformationMethod) {

                        // mostrar
                        etPassword.setTransformationMethod(null);
                        etPassword.setCompoundDrawablesWithIntrinsicBounds(
                                0, 0, R.drawable.ic_reverlarpass, 0);

                    } else {

                        // esconder
                        etPassword.setTransformationMethod(
                                android.text.method.PasswordTransformationMethod.getInstance());
                        etPassword.setCompoundDrawablesWithIntrinsicBounds(
                                0, 0, R.drawable.ic_reverlarpass, 0);
                    }
                    etPassword.setSelection(etPassword.getText().length());
                    return true;
                }
            }
            return false;
        });

        findViewById(R.id.txt_Registar).setOnClickListener(v -> {
            startActivity(new Intent(LoginActivity.this, RegistarContaActivity.class));
        });
        findViewById(R.id.txt_Registar).setOnClickListener(v -> startActivity(new Intent(LoginActivity.this, RegistarContaActivity.class)));
    }

    public void onClickLogin(android.view.View view) {
        String username = etUsername.getText().toString().trim();
        String pass = etPassword.getText().toString().trim();

        if (!isUsernameValido(username)) {
            etUsername.setError("Username inválido");
            return;
        }

        if (!isPasswordValida(pass)) {
            etPassword.setError("Password inválida");
            return;
        }

        // Chama o Singleton para login → retorna token que será guardado
        SingletonGestorAPI.getInstance(this).login(username, pass, this);
    }

    public void onLoginSucesso() {
        startActivity(new Intent(this, MainActivity.class));
        finish();
    }

    public void onLoginErro(String erro) {
        Toast.makeText(this, erro, Toast.LENGTH_LONG).show();
    }

    private boolean isUsernameValido(String username) {
        return username != null && !username.isEmpty();
    }

    private boolean isPasswordValida(String pass) {
        return pass != null && pass.length() >= 4;
    }
}
