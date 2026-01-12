package pt.ipleiria.estg.dei.sismedcare;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputEditText;

import java.util.Calendar;
import java.util.HashMap;
import java.util.Map;
import java.util.Objects;

import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class RegistarContaActivity extends AppCompatActivity {

    // Campos do formulário
    private TextInputEditText etNomeCompleto, etDataNascimento, etNumeroUtente, etTelemovel, etMorada, etUsername, etEmail, etPassword;
    private RadioGroup radioSexo;
    private Button btnRegistar;
    private TextView txtLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_registar_conta);

        // Ligações ao layout
        etNomeCompleto   = findViewById(R.id.inputNomeCompleto);
        etDataNascimento = findViewById(R.id.inputData);
        etNumeroUtente   = findViewById(R.id.inputNumeroUtente);
        etTelemovel      = findViewById(R.id.inputTelemovel);
        etMorada         = findViewById(R.id.inputMorada);
        etUsername       = findViewById(R.id.inputUsername);
        etEmail          = findViewById(R.id.inputEmail);
        etPassword       = findViewById(R.id.inputPassword);

        radioSexo  = findViewById(R.id.radioSexo);
        btnRegistar = findViewById(R.id.btnRegistar);
        txtLogin    = findViewById(R.id.txtRedirecionarIniciarSessao);

        // Botão registar
        btnRegistar.setOnClickListener(v -> registar());

        // Voltar ao login
        txtLogin.setOnClickListener(v -> startActivity(new Intent(this, LoginActivity.class)));

        // ➜ Configurar DatePicker para Data de Nascimento
        etDataNascimento.setFocusable(false);
        etDataNascimento.setClickable(true);

        etDataNascimento.setOnClickListener(v -> {
            // Guardar a data atual do campo
            final Calendar calendar = Calendar.getInstance();
            String dataAtual = Objects.requireNonNull(etDataNascimento.getText()).toString();
            if(!dataAtual.isEmpty()) {
                String[] partes = dataAtual.split("/");
                if(partes.length == 3){
                    calendar.set(Calendar.DAY_OF_MONTH, Integer.parseInt(partes[0]));
                    calendar.set(Calendar.MONTH, Integer.parseInt(partes[1]) - 1);
                    calendar.set(Calendar.YEAR, Integer.parseInt(partes[2]));
                }
            }

            int ano = calendar.get(Calendar.YEAR);
            int mes = calendar.get(Calendar.MONTH);
            int dia = calendar.get(Calendar.DAY_OF_MONTH);

            DatePickerDialog datePickerDialog = new DatePickerDialog(
                    this,
                    (view, selectedYear, selectedMonth, selectedDay) -> {
                        // Atualiza o input com a data selecionada
                        String dataFormatada = String.format("%02d/%02d/%04d", selectedDay, selectedMonth + 1, selectedYear);
                        etDataNascimento.setText(dataFormatada);
                    },
                    ano, mes, dia
            );
            datePickerDialog.show();
        });
    }

    // Valida e envia os dados para a API
    private void registar() {
        String nome = Objects.requireNonNull(etNomeCompleto.getText()).toString().trim();
        String data = Objects.requireNonNull(etDataNascimento.getText()).toString().trim();
        String numeroUtente = Objects.requireNonNull(etNumeroUtente.getText()).toString().trim();
        String telemovel = Objects.requireNonNull(etTelemovel.getText()).toString().trim();
        String morada = Objects.requireNonNull(etMorada.getText()).toString().trim();
        String email = Objects.requireNonNull(etEmail.getText()).toString().trim();
        String username = Objects.requireNonNull(etUsername.getText()).toString().trim();
        String password = Objects.requireNonNull(etPassword.getText()).toString().trim();

        int sexoSelecionadoId = radioSexo.getCheckedRadioButtonId();

        if (nome.isEmpty() || data.isEmpty() || numeroUtente.isEmpty()
                || telemovel.isEmpty() || morada.isEmpty() || username.isEmpty()
                || email.isEmpty() || password.isEmpty()
                || sexoSelecionadoId == -1) {

            Toast.makeText(this, "Preencha todos os campos", Toast.LENGTH_LONG).show();
            return;
        }

        // Converter o sexo em valores lidos pelo back
        RadioButton rbSexo = findViewById(sexoSelecionadoId);
        String sexoTexto = rbSexo.getText().toString().trim();

        String sexo;
        if (sexoTexto.equalsIgnoreCase("Masculino")) {
            sexo = "M";
        } else if (sexoTexto.equalsIgnoreCase("Feminino")) {
            sexo = "F";
        } else {
            sexo = "O";
        }

        // Formatar data para YYYY-MM-DD
        String dataFormatadaAPI = "";
        try {
            String[] partes = data.split("/"); // dd/MM/yyyy
            if (partes.length == 3) {
                dataFormatadaAPI = partes[2] + "-" + partes[1] + "-" + partes[0]; // YYYY-MM-DD
            }
        } catch (Exception e) {
            Toast.makeText(this, "Data de nascimento inválida", Toast.LENGTH_LONG).show();
            return;
        }

        // Dados para a API
        Map<String, String> dados = new HashMap<>();
        dados.put("username", username);
        dados.put("email", email);
        dados.put("password", password);
        dados.put("nome_completo", nome);
        dados.put("data_nascimento", dataFormatadaAPI);
        dados.put("sexo", sexo);
        dados.put("numero_utente", numeroUtente);
        dados.put("telemovel", telemovel);
        dados.put("morada", morada);

        SingletonGestorAPI.getInstance(this).registarPaciente(this, dados, this);
    }

    // Callbacks da API
    public void onRegistoSucesso() {
        Toast.makeText(this, "Conta criada com sucesso", Toast.LENGTH_LONG).show();
        finish(); // volta para login
    }

    public void onRegistoErro(String erro) {
        Toast.makeText(this, erro, Toast.LENGTH_LONG).show();
    }
}
