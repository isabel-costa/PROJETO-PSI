package pt.ipleiria.estg.dei.sismedcare;

import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Collections;
import java.util.HashSet;
import java.util.List;
import java.util.Locale;
import java.util.Set;

import pt.ipleiria.estg.dei.sismedcare.modelo.SingletonGestorAPI;

public class MarcarConsultasActivity extends AppCompatActivity {

    private Spinner spinnerTipo, spinnerProf;
    private EditText inputData, inputHora;
    private Button btnMarcar;
    private ImageView btnVoltar;

    private String medicoSelecionadoId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_marcar_consultas);

        RelativeLayout containerPadding = findViewById(R.id.containerPadding);
        ViewCompat.setOnApplyWindowInsetsListener(containerPadding, (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(
                    v.getPaddingLeft(),
                    systemBars.top,
                    v.getPaddingRight(),
                    v.getPaddingBottom()
            );
            return insets;
        });

        spinnerTipo = findViewById(R.id.spinnerTipo);
        spinnerProf = findViewById(R.id.spinnerProf);
        inputData = findViewById(R.id.inputData);
        inputHora = findViewById(R.id.inputHora);
        btnMarcar = findViewById(R.id.btnMarcar);
        btnVoltar = findViewById(R.id.btnVoltar);

        btnVoltar.setOnClickListener(v -> finish());

        inputData.setOnClickListener(v -> mostrarDatePicker());
        inputHora.setOnClickListener(v -> mostrarTimePicker());

        carregarEspecialidades();
    }

    private void mostrarDatePicker() {
        Calendar c = Calendar.getInstance();
        DatePickerDialog dpd = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
            inputData.setText(String.format(Locale.getDefault(), "%04d-%02d-%02d", year, month+1, dayOfMonth));
        }, c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH));
        dpd.show();
    }

    private void mostrarTimePicker() {
        Calendar c = Calendar.getInstance();
        TimePickerDialog tpd = new TimePickerDialog(this, (view, hourOfDay, minute) -> {
            inputHora.setText(String.format(Locale.getDefault(), "%02d:%02d:00", hourOfDay, minute));
        }, c.get(Calendar.HOUR_OF_DAY), c.get(Calendar.MINUTE), true);
        tpd.show();
    }

    private void carregarEspecialidades() {
        String url = SingletonGestorAPI.getInstance(this).getBaseApiUrl() + "/medicos";

        SingletonGestorAPI.getInstance(this).getMedicoEspecialidade(url, new SingletonGestorAPI.MedicoEspecialidadeListener() {
            @Override
            public void onSuccess(JSONArray response) {
                try {
                    Set<String> especialidadesSet = new HashSet<>();
                    for (int i = 0; i < response.length(); i++) {
                        JSONObject medico = response.getJSONObject(i);
                        String especialidade = medico.optString("especialidade", "").trim();
                        if (!especialidade.isEmpty()) {
                            especialidadesSet.add(especialidade);
                        }
                    }
                    List<String> especialidadesList = new ArrayList<>(especialidadesSet);
                    Collections.sort(especialidadesList);

                    ArrayAdapter<String> adapter = new ArrayAdapter<>(MarcarConsultasActivity.this,
                            android.R.layout.simple_spinner_item, especialidadesList);
                    adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    spinnerTipo.setAdapter(adapter);

                    spinnerTipo.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                        @Override
                        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                            String especialidadeSelecionada = especialidadesList.get(position);
                            carregarMedicosPorEspecialidade(especialidadeSelecionada, response);
                        }
                        @Override
                        public void onNothingSelected(AdapterView<?> parent) {
                            spinnerProf.setAdapter(null);
                        }
                    });

                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(MarcarConsultasActivity.this, "Erro ao processar especialidades", Toast.LENGTH_SHORT).show();
                }
            }
            @Override
            public void onError(String erro) {
                Toast.makeText(MarcarConsultasActivity.this, "Erro ao carregar especialidades", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void carregarMedicosPorEspecialidade(String especialidade, org.json.JSONArray medicosArray) {
        List<String> nomesMedicos = new ArrayList<>();
        List<String> idsMedicos = new ArrayList<>();

        try {
            for (int i = 0; i < medicosArray.length(); i++) {
                JSONObject medico = medicosArray.getJSONObject(i);
                String esp = medico.optString("especialidade", "").trim();
                if (esp.equalsIgnoreCase(especialidade)) {
                    String nome = medico.optString("nome", "Desconhecido");
                    String id = medico.optString("id", "");
                    nomesMedicos.add(nome);
                    idsMedicos.add(id);
                }
            }
        } catch (JSONException e) {
            e.printStackTrace();
            Toast.makeText(this, "Erro ao processar médicos", Toast.LENGTH_SHORT).show();
            return;
        }

        if (nomesMedicos.isEmpty()) {
            nomesMedicos.add("Nenhum médico encontrado");
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, nomesMedicos);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerProf.setAdapter(adapter);

        spinnerProf.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                if (!idsMedicos.isEmpty()) {
                    medicoSelecionadoId = idsMedicos.get(position);
                } else {
                    medicoSelecionadoId = null;
                }
            }
            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                medicoSelecionadoId = null;
            }
        });
    }
}