package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class MenuConfigActivity extends AppCompatActivity {

    private EditText txtURLServidor, txtCaminhoAPI;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_menu_config);

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        txtURLServidor = findViewById(R.id.txt_URLServidor);
        txtCaminhoAPI = findViewById(R.id.txt_CaminhoAPI);

        // Carregar config guardada se existir
        SharedPreferences prefs = getSharedPreferences("CONFIG_APP", MODE_PRIVATE);
        txtURLServidor.setText(prefs.getString("URL_SERVIDOR", ""));
        txtCaminhoAPI.setText(prefs.getString("CAMINHO_API", ""));
    }

    public void onClickGuardarConfiguracao(View view) {

        String urlServidor = txtURLServidor.getText().toString().trim();
        String caminhoAPI = txtCaminhoAPI.getText().toString().trim();

        if (urlServidor.isEmpty()) {
            txtURLServidor.setError("Introduza o URL do servidor");
            return;
        }

        if (caminhoAPI.isEmpty()) {
            txtCaminhoAPI.setError("Introduza o caminho da API");
            return;
        }

        // Guardar configuração
        SharedPreferences prefs = getSharedPreferences("CONFIG_APP", MODE_PRIVATE);
        prefs.edit().putString("URL_SERVIDOR", urlServidor).putString("CAMINHO_API", caminhoAPI).apply();

        Toast.makeText(this, "Configuração guardada com sucesso", Toast.LENGTH_SHORT).show();

        // Ir para Login
        startActivity(new Intent(this, LoginActivity.class));
        finish();
    }
}
