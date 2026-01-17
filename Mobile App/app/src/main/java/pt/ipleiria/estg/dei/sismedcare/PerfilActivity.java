package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.activity.EdgeToEdge;

import java.util.HashMap;
import java.util.Map;

import pt.ipleiria.estg.dei.sismedcare.modelo.Paciente;
import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class PerfilActivity extends AppCompatActivity {

    private EditText etMorada, etTelemovel;
    private TextView tvNome, tvDataNascimento, tvSexo, tvNumUtente, tvEmail;
    private Button btnGuardar, btnLogout;
    private ImageView btnBack;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_perfil);

        // Ajusta o padding para barras de sistema
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        // Inicializar views
        tvNome = findViewById(R.id.tv_nome);
        tvDataNascimento = findViewById(R.id.tv_data_nascimento);
        tvSexo = findViewById(R.id.tv_sexo);
        tvNumUtente = findViewById(R.id.tv_num_utente);
        tvEmail = findViewById(R.id.tv_email);

        etMorada = findViewById(R.id.et_morada);
        etTelemovel = findViewById(R.id.et_telemovel);

        btnGuardar = findViewById(R.id.btnGuardar);
        btnLogout = findViewById(R.id.btnLogout);
        btnBack = findViewById(R.id.btnBack);

        // Preencher dados do paciente autenticado
        Paciente paciente = SingletonGestorAPI.getInstance(this).getPacienteAutenticado();
        if (paciente != null) {
            tvNome.setText(paciente.getNomeCompleto());
            tvNumUtente.setText(paciente.getNumeroUtente());

            // Carrega os restantes dados da API
            SingletonGestorAPI.getInstance(this).getPerfilPaciente(this, new SingletonGestorAPI.PerfilListener() {
                @Override
                public void onSuccess(Paciente p) {
                    tvDataNascimento.setText(p.getDataNascimento());
                    tvSexo.setText(p.getSexo());
                    tvEmail.setText(p.getEmail());
                    etMorada.setText(p.getMorada());
                    etTelemovel.setText(p.getTelemovel());
                }

                @Override
                public void onError(String erro) {
                    Toast.makeText(PerfilActivity.this, erro, Toast.LENGTH_SHORT).show();
                }
            });
        }

        // Botão Guardar alterações
        btnGuardar.setOnClickListener(v -> {
            String novaMorada = etMorada.getText().toString().trim();
            String novoTelemovel = etTelemovel.getText().toString().trim();

            if (novaMorada.isEmpty() && novoTelemovel.isEmpty()) {
                Toast.makeText(this, "Não há alterações para guardar", Toast.LENGTH_SHORT).show();
                return;
            }

            Map<String, String> dados = new HashMap<>();
            dados.put("morada", novaMorada);
            dados.put("telemovel", novoTelemovel);

            SingletonGestorAPI.getInstance(this).atualizarPerfil(this, dados, new SingletonGestorAPI.PerfilUpdateListener() {
                @Override
                public void onSuccess() {
                    Toast.makeText(PerfilActivity.this, "Perfil atualizado com sucesso", Toast.LENGTH_SHORT).show();
                }

                @Override
                public void onError(String erro) {
                    Toast.makeText(PerfilActivity.this, "Erro ao atualizar: " + erro, Toast.LENGTH_LONG).show();
                }
            });
        });

        // Botão voltar
        btnBack.setOnClickListener(v -> finish());

        // Botão logout
        btnLogout.setOnClickListener(v -> {
            SingletonGestorAPI.getInstance(this).logout(this);
            Intent intent = new Intent(this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}