package pt.ipleiria.estg.dei.sismedcare;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.activity.EdgeToEdge;
import pt.ipleiria.estg.dei.sismedcare.modelo.Paciente;

import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class PerfilActivity extends AppCompatActivity {

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

        // Referências dos TextViews
        TextView tvNome = findViewById(R.id.tv_nome);
        TextView tvDataNascimento = findViewById(R.id.tv_data_nascimento);
        TextView tvSexo = findViewById(R.id.tv_sexo);
        TextView tvNumUtente = findViewById(R.id.tv_num_utente);
        TextView tvMorada = findViewById(R.id.tv_morada);
        TextView tvTelemovel = findViewById(R.id.tv_telemovel);
        TextView tvEmail = findViewById(R.id.tv_email);

        // Preencher dados do paciente autenticado
        Paciente paciente = SingletonGestorAPI.getInstance(this).getPacienteAutenticado();
        if (paciente != null) {
            tvNome.setText(paciente.getNomeCompleto());
            tvDataNascimento.setText(paciente.getDataNascimento());
            tvSexo.setText(paciente.getSexo());
            tvNumUtente.setText(paciente.getNumeroUtente());
            tvMorada.setText(paciente.getMorada());
            tvTelemovel.setText(paciente.getTelemovel());
            tvEmail.setText(paciente.getEmail());
        }

        // Botão voltar para homepage fragment
        ImageView btnBack = findViewById(R.id.btnBack);
        btnBack.setOnClickListener(v -> {
            finish(); // Fecha a activity e volta à homepage
        });

        // Botão terminar sessão
        Button btnLogout = findViewById(R.id.btnLogout);
        btnLogout.setOnClickListener(v -> {
            // Limpar paciente autenticado
            SingletonGestorAPI.getInstance(this).logout(this);

            // Voltar para a activity de login
            Intent intent = new Intent(this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); // Limpa a pilha
            startActivity(intent);
            finish();
        });
    }
}